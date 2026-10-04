<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'روش درخواست معتبر نیست.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (!str_contains(strtolower($contentType), 'application/json')) {
    http_response_code(415);
    echo json_encode(['ok' => false, 'message' => 'قالب درخواست معتبر نیست.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$raw = file_get_contents('php://input');
if ($raw === false || strlen($raw) > 8192) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'درخواست نامعتبر است.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$payload = json_decode($raw, true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'اطلاعات ارسالی کامل نیست.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$allowed = [
    'priceRange' => ['under_50', '50_80', '80_120', '120_150', 'over_150', 'undecided'],
    'monthlyPayment' => ['under_5', '5_8', '8_12', '12_20', 'over_20'],
    'term' => ['6', '12', '18', '24', '36', 'depends'],
    'downPayment' => ['none', 'under_25', '25_50', 'over_50', 'depends'],
];

foreach ($allowed as $field => $values) {
    if (!isset($payload[$field]) || !is_string($payload[$field]) || !in_array($payload[$field], $values, true)) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'message' => 'لطفاً به همه سؤال‌ها پاسخ دهید.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$token = $payload['respondentToken'] ?? '';
if (!is_string($token) || strlen($token) < 16 || strlen($token) > 128) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'شناسه پاسخ معتبر نیست.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$source = $payload['source'] ?? 'direct';
$source = is_string($source) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $source) : 'direct';
$source = substr($source ?: 'direct', 0, 40);

try {
    $defaultPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'survey.sqlite';
    $dbPath = getenv('SURVEY_DB_PATH') ?: $defaultPath;
    $dbDirectory = dirname($dbPath);
    if (!is_dir($dbDirectory) && !mkdir($dbDirectory, 0770, true) && !is_dir($dbDirectory)) {
        throw new RuntimeException('Database directory could not be created.');
    }

    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA busy_timeout = 5000');
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS survey_responses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            respondent_hash TEXT NOT NULL UNIQUE,
            price_range TEXT NOT NULL,
            monthly_payment TEXT NOT NULL,
            term TEXT NOT NULL,
            down_payment TEXT NOT NULL,
            source TEXT NOT NULL DEFAULT "direct",
            created_at TEXT NOT NULL,
            updated_at TEXT NOT NULL
        )'
    );

    $now = gmdate('c');
    $respondentHash = hash('sha256', $token);
    $statement = $pdo->prepare(
        'INSERT INTO survey_responses
            (respondent_hash, price_range, monthly_payment, term, down_payment, source, created_at, updated_at)
         VALUES
            (:respondent_hash, :price_range, :monthly_payment, :term, :down_payment, :source, :created_at, :updated_at)
         ON CONFLICT(respondent_hash) DO UPDATE SET
            price_range = excluded.price_range,
            monthly_payment = excluded.monthly_payment,
            term = excluded.term,
            down_payment = excluded.down_payment,
            source = excluded.source,
            updated_at = excluded.updated_at'
    );
    $statement->execute([
        ':respondent_hash' => $respondentHash,
        ':price_range' => $payload['priceRange'],
        ':monthly_payment' => $payload['monthlyPayment'],
        ':term' => $payload['term'],
        ':down_payment' => $payload['downPayment'],
        ':source' => $source,
        ':created_at' => $now,
        ':updated_at' => $now,
    ]);

    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    error_log('Survey submit error: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'ثبت پاسخ در حال حاضر امکان‌پذیر نیست. لطفاً دوباره تلاش کنید.'], JSON_UNESCAPED_UNICODE);
}
