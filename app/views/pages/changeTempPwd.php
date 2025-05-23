<div class="main-container">
  <main class="main-center">
    <div class="container login center">
      <h2 class="text-center">Modifiez votre mot de passe</h2>
      <p class="description">
        Vous vous êtes connecté avec un mot de passe temporaire. Afin de sécuriser l'accès à votre compte, merci de
        le modifier.
      </p>
      <form action="./" method="POST">
        <div class="input-container">
          <label for="password">Nouveau mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Nouveau mot de passe" />
          <p class="error-text"></p>
          <div class="hidden password-requirements">
            <div class="password-strength">
              <div class="strength-bar"></div>
            </div>
            <p class="requirement-title">Votre mot de passe doit contenir :</p>
            <ul>
              <li class="requirement">Au moins 8 caractères</li>
              <li class="requirement">Au moins une lettre majuscule</li>
              <li class="requirement">Au moins une lettre minuscule</li>
              <li class="requirement">Au moins un chiffre</li>
              <li class="requirement">Au moins un caractère spécial</li>
            </ul>
          </div>
        </div>
        <div class="input-container">
          <label for="confirm-password">Confirmer le mot de passe</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            placeholder="Confirmer le mot de passe" />
          <p class="error-text"></p>
        </div>
        <div class="btn-container">
          <button type="submit" class="btn-change-password button-primary" disabled>
            Modifier le mot de passe
          </button>
        </div>
      </form>
    </div>
  </main>
</div>