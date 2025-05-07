const passwordInput = document.getElementById("password");
const passwordError = passwordInput.parentElement.querySelector(".error-text");
const confirmPasswordInput = document.getElementById("confirm-password");
const confirmPasswordError = confirmPasswordInput.parentElement.querySelector(".error-text");
const requirementsContainer = document.querySelector(".password-requirements");
const strengthBar = document.querySelector(".strength-bar");
const requirements = document.querySelectorAll(".requirement");
const submitButton = document.querySelector(".btn-change-password");

let isPasswordStrong = false;
let isPasswordConfirmed = false;

// Vérifie la force du mot de passe
const checkPasswordStrength = (password) => {
  let strength = 0;

  // 1. Au moins 8 caractères et au plus 128
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

  isPasswordStrong = strength === 5;

  updateInputStyle(passwordInput, isPasswordStrong);
  clearErrorIfFixed(passwordInput, passwordError, isPasswordStrong);
  updateSubmitButtonState();
};

// Vérifie que la confirmation du mot de passe est correcte
const checkPasswordMatch = () => {
  isPasswordConfirmed = passwordInput.value === confirmPasswordInput.value && passwordInput.value.length > 0;

  updateInputStyle(confirmPasswordInput, isPasswordConfirmed);
  clearErrorIfFixed(confirmPasswordInput, confirmPasswordError, isPasswordConfirmed);
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

// Affiche une erreur si le mot de passe n'est pas valide
const showErrorIfInvalid = () => {
  if (!isPasswordConfirmed && confirmPasswordInput.value.trim() !== "") {
    confirmPasswordError.textContent = "Les mots de passe ne correspondent pas.";
  }
};

// Efface l'erreur si le mot de passe est valide
const clearErrorIfFixed = (input, errorElement, isValid) => {
  if (isValid || input.value.trim() === "") {
    errorElement.textContent = "";
  }
};

// Active ou désactive le bouton en fonction des deux vérifications
const updateSubmitButtonState = () => {
  submitButton.disabled = !(isPasswordStrong && isPasswordConfirmed);
};

// Écouteurs d'événements
passwordInput.addEventListener("input", () => {
  checkPasswordStrength(passwordInput.value);
  checkPasswordMatch();
});

confirmPasswordInput.addEventListener("input", checkPasswordMatch);
confirmPasswordInput.addEventListener("blur", showErrorIfInvalid);

passwordInput.addEventListener("focus", () => {
  requirementsContainer.classList.remove("hidden");
});

passwordInput.addEventListener("blur", () => {
  if (passwordInput.value.trim() === "") {
    requirementsContainer.classList.add("hidden");
  }
});
