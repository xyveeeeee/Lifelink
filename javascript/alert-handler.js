

document.addEventListener("DOMContentLoaded", () => {
  // Check for message in URL (?msg=...)
  const params = new URLSearchParams(window.location.search);
  const msg = params.get("msg");
  const error = params.get("error");

  if (msg) showAlert(msg, "success");
  if (error) showAlert(error, "error");
});

function showAlert(message, type = "info") {
  // Create popup container
  const alertBox = document.createElement("div");
  alertBox.className = `custom-alert ${type}`;
  alertBox.textContent = message;

  document.body.appendChild(alertBox);

  // Animate in
  setTimeout(() => alertBox.classList.add("visible"), 100);

  // Auto remove after 3s
  setTimeout(() => {
    alertBox.classList.remove("visible");
    setTimeout(() => alertBox.remove(), 500);
  }, 3000);
}
