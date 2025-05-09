const oldPasswordInput = document.getElementById("old-password");
const oldPasswordError = oldPasswordInput.parentElement.querySelector(".error-text");
const passwordInput = document.getElementById("password");
const passwordError = passwordInput.parentElement.querySelector(".error-text");
const confirmPasswordInput = document.getElementById("confirm-password");
const confirmPasswordError = confirmPasswordInput.parentElement.querySelector(".error-text");
const requirementsContainer = document.querySelector(".password-requirements");
const strengthBar = document.querySelector(".strength-bar");
const requirements = document.querySelectorAll(".requirement");
const submitButton = document.querySelector(".btn-change-password");

let oldPasswordValid = false;
let newPasswordValid = false;
let newPasswordConfirmationValid = false;

// Vérifie la force du mot de passe
const checkPasswordStrength = (password) => {
  let strength = 0;

  // 1. Au moins 8 caractères
  password.length >= 8 && password.length <= 128
    ? requirements[0].classList.add("valid")
    : requirements[0].classList.remove("valid");
  // 2. Au moins une majuscule
  /[A-Z]/.test(password) ? requirements[1].classList.add("valid") : requirements[1].classList.remove("valid");
  // 3. Au moins une minuscule
  /[a-z]/.test(password) ? requirements[2].classList.add("valid") : requirements[2].classList.remove("valid");
  // 4. Au moins un chiffre
  /\d/.test(password) ? requirements[3].classList.add("valid") : requirements[3].classList.remove("valid");
  // 5. Au moins un caractère spécial
  /[^A-Za-z0-9]/.test(password) ? requirements[4].classList.add("valid") : requirements[4].classList.remove("valid");

  // Compte combien de critères sont validés
  requirements.forEach((req) => {
    if (req.classList.contains("valid")) strength++;
  });

  updateStrengthBar(strength);
};

// Barre de progression de la force du mot de passe
const updateStrengthBar = (strength) => {
  let width = "0%";
  let color = "var(--password-bar-weak)";

  if (strength === 1) {
    width = "20%";
  } else if (strength === 2) {
    width = "40%";
  } else if (strength === 3) {
    width = "60%";
    color = "var(--password-bar-medium)";
  } else if (strength === 4) {
    width = "80%";
    color = "var(--password-bar-medium)";
  } else if (strength === 5) {
    width = "100%";
    color = "var(--password-bar-strong)";
  }

  strengthBar.style.width = width;
  strengthBar.style.backgroundColor = color;

  newPasswordValid = strength === 5;

  updateInputStyle(passwordInput, newPasswordValid);
  clearErrorIfFixed(passwordInput, passwordError, newPasswordValid);
  updateSubmitButtonState();
};

// Vérifie que la confirmation du mot de passe est correcte
const checkPasswordMatch = () => {
  newPasswordConfirmationValid = passwordInput.value === confirmPasswordInput.value && passwordInput.value.length > 0;

  updateInputStyle(confirmPasswordInput, newPasswordConfirmationValid);
  clearErrorIfFixed(confirmPasswordInput, confirmPasswordError, newPasswordConfirmationValid);
  updateSubmitButtonState();
};

// Vérifie la validité du mot de passe actuel
const checkOldPassword = () => {
  oldPasswordValid = oldPasswordInput.value.length >= 3 && oldPasswordInput.value.length <= 128;

  updateInputStyle(oldPasswordInput, oldPasswordValid);
  clearErrorIfFixed(oldPasswordInput, oldPasswordError, oldPasswordValid);
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

// Affiche une erreur si le mot de passe de confirmation est invalide
const showErrorIfInvalid = () => {
  if (!newPasswordConfirmationValid && confirmPasswordInput.value.trim() !== "") {
    confirmPasswordError.textContent = "Les mots de passe ne correspondent pas.";
  }

  if (!oldPasswordValid && oldPasswordInput.value.trim() !== "") {
    oldPasswordError.textContent = "Veuillez entrer un mot de passe valide.";
  }
};

// Efface l'erreur si le mot de passe est valide
const clearErrorIfFixed = (input, errorElement, isValid) => {
  if (isValid || input.value.trim() === "") {
    errorElement.textContent = "";
  }
};

// Active ou désactive le bouton en fonction des vérifications
const updateSubmitButtonState = () => {
  submitButton.disabled = !(newPasswordValid && newPasswordConfirmationValid && oldPasswordValid);
};

// Écouteurs d'événements
passwordInput.addEventListener("input", () => {
  checkPasswordStrength(passwordInput.value);
  checkPasswordMatch();
});

confirmPasswordInput.addEventListener("input", checkPasswordMatch);
confirmPasswordInput.addEventListener("blur", showErrorIfInvalid);

oldPasswordInput.addEventListener("input", checkOldPassword);
oldPasswordInput.addEventListener("blur", showErrorIfInvalid);

passwordInput.addEventListener("focus", () => {
  requirementsContainer.classList.remove("hidden");
});

passwordInput.addEventListener("blur", () => {
  if (passwordInput.value.trim() === "") {
    requirementsContainer.classList.add("hidden");
  }
});
