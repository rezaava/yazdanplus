document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('surveyForm');
    const submitButton = document.getElementById('submitButton');

    const progressText = document.getElementById('progressText');
    const progressBar = document.getElementById('progressBar');

    const formError = document.getElementById('formError');
    const successPanel = document.getElementById('successPanel');

    const questions = [
        'priceRange',
        'monthlyPayment',
        'term',
        'downPayment'
    ];

    function getAnsweredCount() {

        let count = 0;

        questions.forEach(function (question) {

            const selected = document.querySelector(
                `input[name="${question}"]:checked`
            );

            if (selected) {
                count++;
            }
        });

        return count;
    }

    function updateProgress() {

        const answered = getAnsweredCount();

        progressText.textContent =
            `${answered} از ۴ پاسخ داده شده`;

        const percentage = (answered / 4) * 100;

        progressBar.style.width = percentage + '%';

        const progressTrack =
            document.querySelector('.progress-track');

        if (progressTrack) {
            progressTrack.setAttribute(
                'aria-valuenow',
                answered
            );
        }

        submitButton.disabled = answered !== 4;
    }

    document.querySelectorAll(
        '#surveyForm input[type="radio"]'
    ).forEach(function (input) {

        input.addEventListener('change', function () {
            updateProgress();

            formError.hidden = true;
        });

    });

    form.addEventListener('submit', async function (e) {

        e.preventDefault();

        const answered = getAnsweredCount();

        if (answered !== 4) {

            formError.textContent =
                'لطفاً به همه سؤال‌ها پاسخ دهید.';

            formError.hidden = false;

            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'در حال ثبت...';

        formError.hidden = true;

        const formData = new FormData(form);

        try {

            const response = await fetch(
                window.YAZDAN_SURVEY_CONFIG.endpoint,
                {
                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),

                        'Accept': 'application/json'
                    },

                    body: formData
                }
            );

            const data = await response.json();

            if (!response.ok) {

                if (data.errors) {

                    const firstError =
                        Object.values(data.errors)[0][0];

                    formError.textContent = firstError;

                } else {

                    formError.textContent =
                        data.message || 'خطایی رخ داده است.';
                }

                formError.hidden = false;

                submitButton.disabled = false;
                submitButton.textContent = 'ثبت پاسخ‌ها';

                return;
            }

            if (data.success) {

                form.hidden = true;

                document.querySelector(
                    '.progress-block'
                ).hidden = true;

                successPanel.hidden = false;

                window.scrollTo({
                    top: successPanel.offsetTop - 100,
                    behavior: 'smooth'
                });
            }

        } catch (error) {

            console.error(error);

            formError.textContent =
                'خطا در ارتباط با سرور. لطفاً دوباره تلاش کنید.';

            formError.hidden = false;

            submitButton.disabled = false;
            submitButton.textContent = 'ثبت پاسخ‌ها';
        }

    });

    updateProgress();

});