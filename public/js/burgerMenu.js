document.addEventListener("DOMContentLoaded", () => {
  const burgerMenu = document.querySelector(".burger-menu");
  const overlay = document.querySelector(".menu");
  const logo = document.querySelector(".logo");
  const body = document.querySelector("body");

  burgerMenu.addEventListener("click", () => {
    burgerMenu.classList.toggle("close");
    overlay.classList.toggle("open");
    logo.classList.toggle("open");
    body.classList.toggle("no-scroll");
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      burgerMenu.classList.remove("close");
      overlay.classList.remove("open");
      logo.classList.remove("open");
      body.classList.remove("no-scroll");
    }
  });

  const menuLinks = document.querySelectorAll(".menu a");
  menuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      burgerMenu.classList.remove("close");
      overlay.classList.remove("open");
      logo.classList.remove("open");
      body.classList.remove("no-scroll");
    });
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 768) {
      burgerMenu.classList.remove("close");
      overlay.classList.remove("open");
      logo.classList.remove("open");
      body.classList.remove("no-scroll");
    }
  });
});
