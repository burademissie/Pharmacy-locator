document
  .getElementById("medicine-form")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const messageContainer = document.getElementById("message-container");

    fetch(form.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        messageContainer.innerHTML = data;
        if (data.includes("successfully")) {
          form.reset();
        }
      })
      .catch((error) => {
        messageContainer.innerHTML = `<div class="alert error"><i class="fas fa-exclamation-circle"></i> Error: ${error.message}</div>`;
      });
  });
