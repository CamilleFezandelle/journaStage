<?php

$loggedIn = false;

if ($user) {
  $userFirstName = $user->getFirstName();
  $userLastName = $user->getLastName();
  $userEmail = $user->getEmail();
  $loggedIn = true;
}

?>

<div class="main-container">
  <main class="main-center">
    <div class="container account-request large center">
      <?php if (!$loggedIn) : ?>
        <h1 class="logo">journaStage</h1>
      <?php endif; ?>
      <h2 class="text-center">Contactez nous ici</h2>
      <form action="#" method="POST">
        <div class="input-container">
          <label for="lastName">Nom</label>
          <input type="text" id="lastName" name="lastName" <?= $loggedIn ? 'value="' . htmlspecialchars($userLastName) . '" disabled' : 'placeholder="Nom" required' ?> />
          <p class="error-text"></p>
        </div>
        <div class="input-container">
          <label for="firstName">Prénom</label>
          <input type="text" id="firstName" name="firstName" <?= $loggedIn ? 'value="' . htmlspecialchars($userFirstName) . '" disabled' : 'placeholder="Prénom" required' ?> />
          <p class="error-text"></p>
        </div>
        <div class="input-container">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" <?= $loggedIn ? 'value="' . htmlspecialchars($userEmail) . '" disabled' : 'placeholder="Email" required' ?> />
          <p class="error-text"></p>
        </div>
        <div class="input-container">
          <label for="message">Comment pouvons-nous vous aider ?</label>
          <textarea id="message" name="message" placeholder="Votre message ici" required></textarea>
          <p class="error-text"></p>
        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-submit button-primary" disabled>Envoyer ma demande</button>
        </div>
      </form>
      <div class="create-account text-center">
        <a href="#" class="link">Retourner à la page de connexion</a>
      </div>
    </div>
  </main>
</div>