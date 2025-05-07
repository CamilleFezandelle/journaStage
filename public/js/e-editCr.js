const titleInput = document.getElementById("title");
const titleError = titleInput.parentElement.querySelector(".error-text");
const dateInput = document.getElementById("date");
const dateError = dateInput.parentElement.querySelector(".error-text");
const contentInput = document.getElementById("content");
const contentError = contentInput.parentElement.querySelector(".error-text");
const submitButton = document.querySelector(".submit-btn");

let titleValid = true;
let dateValid = true;
let contentValid = true;

// Vérifie si le titre est valide
const checkTitle = () => {
  titleValid = titleInput.value.length >= 5 && titleInput.value.length <= 120;

  updateInputStyle(titleInput, titleValid, titleError);
  clearErrorIfFixed(titleInput, titleError, titleValid);
  updateSubmitButtonState();
};

// Vérifie si la date est valide
const checkDate = () => {
  dateValid = dateInput.value.length === 10;
  dateValid = /^\d{4}-\d{2}-\d{2}$/.test(dateInput.value) && dateValid;

  updateInputStyle(dateInput, dateValid, dateError);
  clearErrorIfFixed(dateInput, dateError, dateValid);
  updateSubmitButtonState();
};

// Vérifie si le contenu est valide
const checkContent = () => {
  contentValid = contentInput.value.length >= 5 && contentInput.value.length <= 3000;

  updateInputStyle(contentInput, contentValid, contentError);
  clearErrorIfFixed(contentInput, contentError, contentValid);
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

// Affiche une erreur si l'input est invalide
const showErrorIfInvalid = () => {
  if (!titleValid && titleInput.value.trim() !== "") {
    titleError.textContent = "Veuillez entrer un titre valide (5 à 120 caractères).";
  }

  if (!dateValid && dateInput.value.trim() !== "") {
    dateError.textContent = "Veuillez entrer une date valide (AAAA-MM-JJ).";
  }

  if (!contentValid && contentInput.value.trim() !== "") {
    contentError.textContent = "Veuillez entrer un contenu valide (5 à 3000 caractères).";
  }
};

// Efface l'erreur si l'input est valide ou vide
const clearErrorIfFixed = (input, errorElement, isValid) => {
  if (isValid || input.value.trim() === "") {
    errorElement.textContent = "";
  }
};

// Affiche une erreur si l'input est invalide

// Active ou désactive le bouton en fonction des vérifications
const updateSubmitButtonState = () => {
  submitButton.disabled = !(titleValid && dateValid && contentValid);
};

// Écouteurs d'événements
titleInput.addEventListener("input", checkTitle);
titleInput.addEventListener("blur", showErrorIfInvalid);
dateInput.addEventListener("input", checkDate);
dateInput.addEventListener("blur", showErrorIfInvalid);
contentInput.addEventListener("input", checkContent);
contentInput.addEventListener("blur", showErrorIfInvalid);
