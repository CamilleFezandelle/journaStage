document.addEventListener("DOMContentLoaded", () => {
  const tempWindow = document.querySelector(".temp-window");

  setTimeout(() => {
    tempWindow.style.opacity = "0.8";
  }, 100);

  setTimeout(() => {
    tempWindow.style.opacity = "0";
  }, 5100);

  setTimeout(() => {
    tempWindow.remove();
  }, 7100);
});
