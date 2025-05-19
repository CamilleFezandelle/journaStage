<?php

$emailValue = empty($errors['email']) ? ($post['email'] ?? '') : '';

$errorEmail = $errors['email'] ?? '';

if (!empty($errorEmail)) {
  $errorGlobal = 'Tous les champs doivent être correctement remplis.';
} else {
  $errorGlobal = '';
}

?>

<div class="main-container">
  <main class="main-center">
    <div class="container login center">
      <h1 class="logo">journaStage</h1>
      <h2 class="text-center">Mot de passe oublié ?</h2>
      <p class="description">Renseignez votre adresse email afin de recevoir un mot de passe temporaire.</p>
      <form action="./mot-de-passe-oublie" method="POST">
        <?php if ($errorGlobal): ?>
          <div class="error-container">
            <p class="error-text"><i class="fa-solid fa-circle-xmark"></i><?= htmlspecialchars($errorGlobal) ?></p>
          </div>
        <?php endif; ?>
        <div class="input-container">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($emailValue) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorEmail) ?></p>
        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary" disabled>Valider</button>
        </div>
      </form>
      <div class="create-account text-center">
        <a href="./se-connecter" class="link">Revenir à la page de connexion</a>
      </div>
    </div>
  </main>
</div>