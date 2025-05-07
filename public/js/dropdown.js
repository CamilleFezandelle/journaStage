document.addEventListener("DOMContentLoaded", () => {
  const dropdownMenu = document.querySelector(".dropdown-menu");
  const dropdownButton = document.querySelector(".dropdown-button");
  const dropdownItems = document.querySelectorAll(".dropdown-item");

  if (!dropdownMenu || !dropdownButton || !dropdownItems) return;

  // Affiche ou masque le menu déroulant au clic sur le bouton
  dropdownButton.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle("visible");
  });

  // Ferme le menu si on clique en dehors
  document.addEventListener("click", (e) => {
    if (!dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.remove("visible");
    }
  });

  // Ferme le menu avec la touche Échap
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      dropdownMenu.classList.remove("visible");
    }
  });

  // Ferme le menu après avoir cliqué sur un des éléments
  dropdownItems.forEach((item) => {
    item.addEventListener("click", () => {
      dropdownMenu.classList.remove("visible");
    });
  });
});
