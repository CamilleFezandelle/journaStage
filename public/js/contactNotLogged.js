const lastNameInput = document.getElementById("lastName");
const lastNameError = lastNameInput.parentElement.querySelector(".error-text");
const firstNameInput = document.getElementById("firstName");
const firstNameError = firstNameInput.parentElement.querySelector(".error-text");
const emailInput = document.getElementById("email");
const emailError = emailInput.parentElement.querySelector(".error-text");
const messageInput = document.getElementById("message");
const messageError = messageInput.parentElement.querySelector(".error-text");
const submitButton = document.querySelector(".submit-btn");

let lastNameValid = false;
let firstNameValid = false;
let emailValid = false;
let messageValid = false;

// Vérifie la validité du nom
const checkLastName = () => {
  lastNameValid = lastNameInput.value.length >= 2 && lastNameInput.value.length <= 50;
  lastNameValid = /^[a-zA-ZÀ-ÿ '-]+$/.test(lastNameInput.value) && lastNameValid;

  updateInputStyle(lastNameInput, lastNameValid);
  clearErrorIfFixed(lastNameInput, lastNameError, lastNameValid);
  updateSubmitButtonState();
};

// Vérifie la validité du prénom
const checkFirstName = () => {
  firstNameValid = firstNameInput.value.length >= 2 && firstNameInput.value.length <= 50;
  firstNameValid = /^[a-zA-ZÀ-ÿ '-]+$/.test(firstNameInput.value) && firstNameValid;

  updateInputStyle(firstNameInput, firstNameValid);
  clearErrorIfFixed(firstNameInput, firstNameError, firstNameValid);
  updateSubmitButtonState();
};

// Vérifie la validité de l'email
const checkEmail = () => {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  emailValid = emailRegex.test(emailInput.value);

  updateInputStyle(emailInput, emailValid);
  clearErrorIfFixed(emailInput, emailError, emailValid);
  updateSubmitButtonState();
};

// Vérifie la validité du message
const checkMessage = () => {
  messageValid = messageInput.value.length >= 5 && messageInput.value.length <= 3000;

  updateInputStyle(messageInput, messageValid);
  clearErrorIfFixed(messageInput, messageError, messageValid);
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

// Affiche l'erreur si l'input est invalide
const showErrorIfInvalid = () => {
  if (!lastNameValid && lastNameInput.value.trim() !== "") {
    lastNameError.textContent = "Veuillez entrer un nom valide (2 à 50 caractères).";
  }

  if (!firstNameValid && firstNameInput.value.trim() !== "") {
    firstNameError.textContent = "Veuillez entrer un prénom valide (2 à 50 caractères).";
  }

  if (!emailValid && emailInput.value.trim() !== "") {
    emailError.textContent = "Veuillez entrer une adresse email valide.";
  }

  if (!messageValid && messageInput.value.trim() !== "") {
    messageError.textContent = "Veuillez entrer un message valide (5 à 3000 caractères).";
  }
};

// Efface l'erreur si l'input est valide ou vide
const clearErrorIfFixed = (input, errorElement, isValid) => {
  if (isValid || input.value.trim() === "") {
    errorElement.textContent = "";
  }
};

// Active ou désactive le bouton en fonction des vérifications
const updateSubmitButtonState = () => {
  submitButton.disabled = !(lastNameValid && firstNameValid && emailValid && messageValid);
};

// Écouteurs d'événements
if (!lastNameInput.disabled) {
  lastNameInput.addEventListener("input", checkLastName);
  lastNameInput.addEventListener("blur", showErrorIfInvalid);
}

if (!firstNameInput.disabled) {
  firstNameInput.addEventListener("input", checkFirstName);
  firstNameInput.addEventListener("blur", showErrorIfInvalid);
}

if (!emailInput.disabled) {
  emailInput.addEventListener("input", checkEmail);
  emailInput.addEventListener("blur", showErrorIfInvalid);
}

messageInput.addEventListener("input", checkMessage);
messageInput.addEventListener("blur", showErrorIfInvalid);
