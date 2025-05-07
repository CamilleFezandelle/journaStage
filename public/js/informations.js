const targetDiv = document.querySelector(".fixed");
const main = document.querySelector("main");

const updatePosition = () => {
  const mainTop = main.getBoundingClientRect().top;
  const isLargeScreen = window.innerWidth > 1200 && window.innerHeight > 620;

  if (isLargeScreen && mainTop <= 32) {
    Object.assign(targetDiv.style, {
      position: "fixed",
      top: "32px",
    });
  } else {
    Object.assign(targetDiv.style, {
      position: "static",
      top: "",
    });
  }
};

// Initial check
updatePosition();

// Update on scroll and resize
["scroll", "resize"].forEach((evt) => window.addEventListener(evt, updatePosition));
