const messageInput = document.getElementById("message");
const messageError = messageInput.parentElement.querySelector(".error-text");
const submitButton = document.querySelector(".submit-btn");

let messageValid = false;

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
  submitButton.disabled = !messageValid;
};

// Écouteurs d'événements
messageInput.addEventListener("input", checkMessage);
messageInput.addEventListener("blur", showErrorIfInvalid);
