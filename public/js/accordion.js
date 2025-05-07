document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".accordion").forEach((accordion) => {
    const header = accordion.querySelector(".accordion-header");
    const content = accordion.querySelector(".accordion-content");
    const chevron = accordion.querySelector(".chevron");

    header.addEventListener("click", () => {
      const isOpen = content.classList.contains("open");

      if (window.innerWidth < 1200) {
        content.classList.toggle("open");
        chevron.classList.toggle("rotate");
        header.setAttribute("aria-expanded", String(!isOpen));
      }
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth >= 1200) {
        content.classList.remove("open");
        chevron.classList.remove("rotate");
        header.setAttribute("aria-expanded", "false");
      }
    });
  });
});
