(() => {
  "use strict";

  const questions = ["priceRange", "monthlyPayment", "term", "downPayment"];
  const form = document.querySelector("#surveyForm");
  const submitButton = document.querySelector("#submitButton");
  const progressText = document.querySelector("#progressText");
  const progressBar = document.querySelector("#progressBar");
  const progressTrack = document.querySelector(".progress-track");
  const formError = document.querySelector("#formError");
  const successPanel = document.querySelector("#successPanel");

  const toPersianDigits = (value) =>
    String(value).replace(/\d/g, (digit) => "۰۱۲۳۴۵۶۷۸۹"[Number(digit)]);

  const selectedCount = () =>
    questions.filter((name) => form.elements[name]?.value).length;

  const updateProgress = () => {
    const count = selectedCount();
    const percentage = (count / questions.length) * 100;
    progressText.textContent = `${toPersianDigits(count)} از ${toPersianDigits(questions.length)} پاسخ داده شده`;
    progressBar.style.width = `${percentage}%`;
    progressTrack.setAttribute("aria-valuenow", String(count));
    submitButton.disabled = count !== questions.length;
  };

  const getRespondentToken = () => {
    const key = "yazdan_mobile_survey_token_v1";
    let token = localStorage.getItem(key);
    if (!token) {
      token = crypto.randomUUID
        ? crypto.randomUUID()
        : `${Date.now()}-${Math.random().toString(36).slice(2)}`;
      localStorage.setItem(key, token);
    }
    return token;
  };

  const showError = (message) => {
    formError.textContent = message;
    formError.hidden = false;
    formError.scrollIntoView({ behavior: "smooth", block: "center" });
  };

  form.addEventListener("change", updateProgress);

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    formError.hidden = true;

    if (selectedCount() !== questions.length) {
      showError("لطفاً به هر چهار سؤال پاسخ دهید.");
      return;
    }

    if (form.elements.website.value) return;

    submitButton.disabled = true;
    submitButton.textContent = "در حال ثبت پاسخ‌ها…";

    const payload = {
      priceRange: form.elements.priceRange.value,
      monthlyPayment: form.elements.monthlyPayment.value,
      term: form.elements.term.value,
      downPayment: form.elements.downPayment.value,
      respondentToken: getRespondentToken(),
      source: new URLSearchParams(location.search).get("utm_source") || "direct",
    };

    try {
      const endpoint = window.YAZDAN_SURVEY_CONFIG?.endpoint || "./api/submit.php";
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json", Accept: "application/json" },
        credentials: "same-origin",
        body: JSON.stringify(payload),
      });

      const result = await response.json().catch(() => ({}));
      if (!response.ok || result.ok !== true) {
        throw new Error(result.message || "ثبت پاسخ با مشکل مواجه شد.");
      }

      localStorage.setItem("yazdan_mobile_survey_submitted_v1", "true");
      form.hidden = true;
      document.querySelector(".progress-block").hidden = true;
      successPanel.hidden = false;
      successPanel.scrollIntoView({ behavior: "smooth", block: "center" });
    } catch (error) {
      showError(error.message || "ارتباط با سامانه برقرار نشد. لطفاً دوباره تلاش کنید.");
      submitButton.disabled = false;
      submitButton.textContent = "ثبت پاسخ‌ها";
    }
  });

  updateProgress();
})();
