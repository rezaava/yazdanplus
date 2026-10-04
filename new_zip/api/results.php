<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'روش درخواست معتبر نیست.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$configuredToken = getenv('SURVEY_ADMIN_TOKEN');
$authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$providedToken = str_starts_with($authorization, 'Bearer ') ? substr($authorization, 7) : '';

if (!$configuredToken || !$providedToken || !hash_equals($configuredToken, $providedToken)) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'دسترسی غیرمجاز است.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $defaultPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'survey.sqlite';
    $dbPath = getenv('SURVEY_DB_PATH') ?: $defaultPath;
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $total = (int) $pdo->query('SELECT COUNT(*) FROM survey_responses')->fetchColumn();
    $dimensions = [
        'priceRange' => 'price_range',
        'monthlyPayment' => 'monthly_payment',
        'term' => 'term',
        'downPayment' => 'down_payment',
        'source' => 'source',
    ];
    $results = [];

    foreach ($dimensions as $key => $column) {
        $statement = $pdo->query("SELECT {$column} AS value, COUNT(*) AS count FROM survey_responses GROUP BY {$column} ORDER BY count DESC");
        $results[$key] = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['ok' => true, 'total' => $total, 'results' => $results], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Throwable $exception) {
    error_log('Survey results error: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'دریافت نتایج امکان‌پذیر نیست.'], JSON_UNESCAPED_UNICODE);
}
