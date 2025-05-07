const emailInput = document.getElementById("email");
const emailError = emailInput.parentElement.querySelector(".error-text");
const passwordInput = document.getElementById("password");
const passwordError = passwordInput.parentElement.querySelector(".error-text");
const submitButton = document.querySelector(".submit-btn");

let emailValid = false;
let passwordValid = false;

const checkEmail = () => {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  emailValid = emailRegex.test(emailInput.value);

  updateInputStyle(emailInput, emailValid);
  clearErrorIfFixed(emailInput, emailError, emailValid);
  updateSubmitButtonState();
};

const checkPassword = () => {
  passwordValid = passwordInput.value.length >= 3 && passwordInput.value.length <= 128;

  updateInputStyle(passwordInput, passwordValid);
  clearErrorIfFixed(passwordInput, passwordError, passwordValid);
  updateSubmitButtonState();
};

const updateInputStyle = (input, isValid) => {
  if (input.value.trim() === "") {
    input.style.borderColor = "";
  } else {
    input.style.borderColor = isValid ? "var(--button-border-primary)" : "var(--warning)";
  }
};

const showErrorIfInvalid = () => {
  if (!emailValid && emailInput.value.trim() !== "") {
    emailError.textContent = "Veuillez entrer une adresse e-mail valide.";
  }

  if (!passwordValid && passwordInput.value.trim() !== "") {
    passwordError.textContent = "Veuillez entrer un mot de passe valide.";
  }
};

const clearErrorIfFixed = (input, errorElement, isValid) => {
  if (isValid || input.value.trim() === "") {
    errorElement.textContent = "";
  }
};

const updateSubmitButtonState = () => {
  submitButton.disabled = !(emailValid && passwordValid);
};

emailInput.addEventListener("input", checkEmail);
emailInput.addEventListener("blur", showErrorIfInvalid);
passwordInput.addEventListener("input", checkPassword);
passwordInput.addEventListener("blur", showErrorIfInvalid);
