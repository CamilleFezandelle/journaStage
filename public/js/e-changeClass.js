const radioInputs = document.querySelectorAll('input[name="radio"]');
const submitButton = document.querySelector(".submit-btn");

let radioValid = false;

// Vérifie la validité du radio
const checkRadio = () => {
  radioValid = Array.from(radioInputs).some((input) => input.checked);

  if (radioValid) {
    radioInputs.forEach((input) => {
      input.parentElement.style.borderColor = "var(--button-border-primary)";
    });
  } else {
    radioInputs.forEach((input) => {
      input.parentElement.style.borderColor = "var(--warning)";
    });
  }

  updateSubmitButtonState();
};

// Active ou désactive le bouton en fonction des vérifications
const updateSubmitButtonState = () => {
  submitButton.disabled = !radioValid;
};

// Écouteurs d'événements
radioInputs.forEach((input) => {
  input.addEventListener("change", checkRadio);
});
