<!doctype html>
<html lang="fa" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#e30613" />
    <meta
      name="description"
      content="نیازسنجی طرح اعتبار خرید اقساطی تلفن همراه ویژه اعضای دانشگاه یزد"
    />
    <title>نیازسنجی خرید اقساطی تلفن همراه | یزدان‌پلاس</title>
    <link rel="stylesheet" href="{{ asset('/css/survey.css') }}" />
  </head>
  <body>
    <header class="site-header">
      <div class="header-inner">
        <a class="brand" href="/" aria-label="صفحه اصلی یزدان‌پلاس">
          <img src="/img/core-img/aneto-logo2.png" alt="یزدان‌پلاس" />
        </a>
        <a class="home-link" href="/">
          <span aria-hidden="true">⌂</span>
          بازگشت به یزدان‌پلاس
        </a>
      </div>
    </header>

    <main>
      <section class="hero" aria-labelledby="page-title">
        <div class="hero-copy">
          <span class="eyebrow">ویژه اعضای هیأت علمی و کارکنان دانشگاه یزد</span>
          <h1 id="page-title">نیازسنجی خرید اقساطی تلفن همراه</h1>
          <p>
            یزدان‌پلاس در حال آماده‌سازی شرایطی منعطف و به‌صرفه‌تر از خدمات اعتباری رایج
            و شرایط متداول موبایل‌فروشی‌های سطح شهر است. پاسخ شما به طراحی بهتر این طرح
            کمک می‌کند.
          </p>
          <div class="hero-meta" aria-label="مشخصات نظرسنجی">
            <span>۴ سؤال کوتاه</span>
            <span>حدود ۱ دقیقه</span>
            <span>بدون دریافت اطلاعات هویتی</span>
          </div>
        </div>
        <div class="hero-mark" aria-hidden="true">
          <div class="phone-shape">
            <span class="phone-speaker"></span>
            <span class="phone-plus">+</span>
          </div>
        </div>
      </section>

      <section class="survey-shell" aria-labelledby="survey-heading">
        <div class="progress-block">
          <div class="progress-copy">
            <h2 id="survey-heading">نظر شما برای ما مهم است</h2>
            <span id="progressText">۰ از ۴ پاسخ داده شده</span>
          </div>
          <div
            class="progress-track"
            role="progressbar"
            aria-valuemin="0"
            aria-valuemax="4"
            aria-valuenow="0"
            aria-label="میزان تکمیل نظرسنجی"
          >
            <span id="progressBar"></span>
          </div>
        </div>

        <form id="surveyForm" novalidate>
          <div class="question-card" data-question="priceRange">
            <div class="question-number">۱</div>
            <fieldset>
              <legend>قیمت تقریبی تلفن همراه موردنظر شما در کدام محدوده است؟</legend>
              <p class="question-help">اگر مبلغ خرید بیشتر از سقف اعتبار باشد، امکان پرداخت مابه‌التفاوت وجود خواهد داشت.</p>
              <div class="options-grid">
                <label class="option"><input type="radio" name="priceRange" value="under_50" /><span>کمتر از ۵۰ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="priceRange" value="50_80" /><span>۵۰ تا ۸۰ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="priceRange" value="80_120" /><span>۸۰ تا ۱۲۰ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="priceRange" value="120_150" /><span>۱۲۰ تا ۱۵۰ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="priceRange" value="over_150" /><span>بیشتر از ۱۵۰ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="priceRange" value="undecided" /><span>هنوز مبلغ مشخصی ندارم</span></label>
              </div>
            </fieldset>
          </div>

          <div class="question-card" data-question="monthlyPayment">
            <div class="question-number">۲</div>
            <fieldset>
              <legend>چه مبلغی برای کسر ماهانه از حقوق برای شما مناسب‌تر است؟</legend>
              <p class="question-help">این پاسخ فقط برای طراحی دامنه اقساط است و سقف نهایی هر فرد جداگانه تعیین می‌شود.</p>
              <div class="options-grid">
                <label class="option"><input type="radio" name="monthlyPayment" value="under_5" /><span>کمتر از ۵ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="monthlyPayment" value="5_8" /><span>۵ تا ۸ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="monthlyPayment" value="8_12" /><span>۸ تا ۱۲ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="monthlyPayment" value="12_20" /><span>۱۲ تا ۲۰ میلیون تومان</span></label>
                <label class="option"><input type="radio" name="monthlyPayment" value="over_20" /><span>بیشتر از ۲۰ میلیون تومان</span></label>
              </div>
            </fieldset>
          </div>

          <div class="question-card" data-question="term">
            <div class="question-number">۳</div>
            <fieldset>
              <legend>کدام دوره بازپرداخت را ترجیح می‌دهید؟</legend>
              <p class="question-help">مبلغ قسط بر اساس مبلغ اعتبار و مدت انتخاب‌شده محاسبه خواهد شد.</p>
              <div class="options-grid options-grid--compact">
                <label class="option"><input type="radio" name="term" value="6" /><span>۶ ماه</span></label>
                <label class="option"><input type="radio" name="term" value="12" /><span>۱۲ ماه</span></label>
                <label class="option"><input type="radio" name="term" value="18" /><span>۱۸ ماه</span></label>
                <label class="option"><input type="radio" name="term" value="24" /><span>۲۴ ماه</span></label>
                <label class="option"><input type="radio" name="term" value="36" /><span>۳۶ ماه</span></label>
                <label class="option option--wide"><input type="radio" name="term" value="depends" /><span>به مبلغ قسط بستگی دارد</span></label>
              </div>
            </fieldset>
          </div>

          <div class="question-card" data-question="downPayment">
            <div class="question-number">۴</div>
            <fieldset>
              <legend>برای کاهش مبلغ اقساط، امکان پرداخت چه میزان پیش‌پرداختی را دارید؟</legend>
              <div class="options-grid">
                <label class="option"><input type="radio" name="downPayment" value="none" /><span>ترجیح می‌دهم بدون پیش‌پرداخت باشد</span></label>
                <label class="option"><input type="radio" name="downPayment" value="under_25" /><span>تا ۲۵٪ قیمت گوشی</span></label>
                <label class="option"><input type="radio" name="downPayment" value="25_50" /><span>بین ۲۵٪ تا ۵۰٪ قیمت گوشی</span></label>
                <label class="option"><input type="radio" name="downPayment" value="over_50" /><span>بیشتر از ۵۰٪ قیمت گوشی</span></label>
                <label class="option"><input type="radio" name="downPayment" value="depends" /><span>به قیمت و شرایط اقساط بستگی دارد</span></label>
              </div>
            </fieldset>
          </div>

          <div class="honeypot" aria-hidden="true">
            <label>این فیلد را خالی بگذارید <input type="text" name="website" tabindex="-1" autocomplete="off" /></label>
          </div>

          <div id="formError" class="form-message form-message--error" role="alert" hidden></div>
          <button id="submitButton" class="submit-button" type="submit" disabled>
            ثبت پاسخ‌ها
          </button>
          <p class="privacy-note">شرکت در این نظرسنجی به‌معنای ثبت درخواست یا ایجاد اولویت دریافت اعتبار نیست.</p>
        </form>

        <section id="successPanel" class="success-panel" hidden aria-live="polite">
          <div class="success-icon" aria-hidden="true">✓</div>
          <h2>پاسخ شما ثبت شد</h2>
          <p>از همراهی شما در طراحی بهتر طرح خرید اقساطی تلفن همراه سپاسگزاریم.</p>
          <a href="https://yazdanplus.ir/">بازگشت به یزدان‌پلاس</a>
        </section>
      </section>
    </main>

    <footer>
      <img src="/img/core-img/aneto-logo2.png" alt="" />
      <p>© تمامی حقوق متعلق به شرکت توسعه‌گران آرتان پویا یزد است.</p>
    </footer>

    <script>
    window.YAZDAN_SURVEY_CONFIG = {
        endpoint: "{{ route('survey.submit') }}"
    };
</script>

<script src="{{ asset('js/survey.js') }}" defer></script>
  </body>
</html>
