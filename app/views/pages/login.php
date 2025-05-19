<?php

$errorEmail = $errors['email'] ?? '';
$errorPassword = $errors['password'] ?? '';

if (!empty($errorEmail) || !empty($errorPassword)) {
  $errorGlobal = 'Tous les champs doivent être correctement remplis.';
} elseif (!empty($errors['global'])) {
  $errorGlobal = $errors['global'];
} else {
  $errorGlobal = '';
}

?>

<div class="main-container">
  <main class="main-center">
    <div class="container login center">
      <h1 class="logo">journaStage</h1>
      <h2 class="text-center">Connectez-vous pour continuer</h2>
      <form action="./se-connecter" method="POST">
        <?php if ($cookieLostPwd) : ?>
          <div class="success-container success">
            <p class="success-text">Si cette adresse email est enregistrée, voici le mot de passe temporaire :</p>
            <span><?= $cookieLostPwd ?></span>
          </div>
        <?php endif ?>
        <?php if ($errorGlobal): ?>
          <div class="error-container">
            <p class="error-text"><i class="fa-solid fa-circle-xmark"></i><?= $errorGlobal ?></p>
          </div>
        <?php endif; ?>
        <div class="input-container">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" required />
          <p class="error-text">
            <?= htmlspecialchars($errorEmail) ?>
          </p>
        </div>
        <div class="input-container">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Mot de passe" required />
          <p class="error-text">
            <?= htmlspecialchars($errorPassword) ?>
          </p>
          <div class="link">
            <a href="./mot-de-passe-oublie">Mot de passe oublié ?</a>
          </div>
        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary" disabled>Se connecter</button>
        </div>
      </form>
      <div class="create-account text-center">
        <a href="./demander-un-compte" class="link">Demander un compte</a>
      </div>
    </div>
  </main>
</div>