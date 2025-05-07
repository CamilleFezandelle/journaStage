document.addEventListener("DOMContentLoaded", () => {
  const openButton = document.querySelector(".open-confirm-window");
  const confirmWindowContainer = document.querySelector(".confirm-window-container");
  const confirmWindow = document.querySelector(".confirm-window");
  const buttons = document.querySelectorAll(".confirm-window .button");

  if (!openButton || !confirmWindowContainer || !confirmWindow || !buttons) return;

  // Affiche la fenêtre de confirmation au clic sur le bouton
  openButton.addEventListener("click", (e) => {
    e.stopPropagation();
    confirmWindowContainer.classList.add("visible");
  });

  // Ferme la fenêtre si on clique en dehors
  document.addEventListener("click", (e) => {
    if (!confirmWindow.contains(e.target) && !openButton.contains(e.target)) {
      confirmWindowContainer.classList.remove("visible");
    }
  });

  // Ferme la fenêtre avec la touche Échap
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      confirmWindowContainer.classList.remove("visible");
    }
  });

  // Ferme la fenêtre après avoir cliqué sur un des boutons
  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      confirmWindowContainer.classList.remove("visible");
    });
  });
});
