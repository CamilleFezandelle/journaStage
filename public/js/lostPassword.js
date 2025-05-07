const emailInput = document.getElementById("email");
const emailError = emailInput.parentElement.querySelector(".error-text");
const submitButton = document.querySelector(".submit-btn");

let emailValid = false;

// Vérifie la validité de l'email
const checkEmail = () => {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  emailValid = emailRegex.test(emailInput.value);

  updateInputStyle(emailInput, emailValid);
  clearErrorIfFixed(emailInput, emailError, emailValid);
  updateSubmitButtonState();
};

// Met à jour le style de l'input en fonction de sa validité
const updateInputStyle = (input, isValid) => {
  if (input.value.trim() === "") {
    input.style.borderColor = "";
  } else {
    input.style.borderColor = isValid ? "var(--button-border-primary)" : "var(--warning)";
  }
};

// Affiche une erreur si l'email est invalide
const showErrorIfInvalid = () => {
  if (!emailValid && emailInput.value.trim() !== "") {
    emailError.textContent = "Veuillez entrer une adresse e-mail valide.";
  }
};

// Efface l'erreur si l'email est valide ou vide
const clearErrorIfFixed = (input, errorElement, isValid) => {
  if (isValid || input.value.trim() === "") {
    errorElement.textContent = "";
  }
};

// Active ou désactive le bouton en fonction des vérifications
const updateSubmitButtonState = () => {
  submitButton.disabled = !emailValid;
};

// Écouteurs d'événements
emailInput.addEventListener("input", checkEmail);
emailInput.addEventListener("blur", showErrorIfInvalid);
