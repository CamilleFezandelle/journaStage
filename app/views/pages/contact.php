<?php

$firstNameContent = empty($errors['firstName']) ? ($post['firstName'] ?? '') : '';
$lastNameContent = empty($errors['lastName']) ? ($post['lastName'] ?? '') : '';
$emailContent = empty($errors['email']) ? ($post['email'] ?? '') : '';
$messageContent = empty($errors['message']) ? ($post['message'] ?? '') : '';

$errorFirstName = $errors['firstName'] ?? '';
$errorLastName = $errors['lastName'] ?? '';
$errorEmail = $errors['email'] ?? '';
$errorMessage = $errors['message'] ?? '';

$loggedIn = false;

if ($user) {
  $userFirstName = $user->getFirstName();
  $userLastName = $user->getLastName();
  $userEmail = $user->getEmail();
  $loggedIn = true;

  if (!empty($errorMessage)) {
    $errorGlobal = 'Tous les champs doivent être correctement remplis.';
  } else {
    $errorGlobal = '';
  }
} else {
  if (!empty($errorFirstName) || !empty($errorLastName) || !empty($errorEmail) || !empty($errorMessage)) {
    $errorGlobal = 'Tous les champs doivent être correctement remplis.';
  } else {
    $errorGlobal = '';
  }
}

?>

<?php if ($cookie === 'success') : ?>
  <div class="temp-window success">
    <p><i class="fa-solid fa-circle-check"></i>Votre message a été envoyé avec succès.</p>
  </div>
<?php endif; ?>

<div class="main-container">
  <main class="main-center">
    <div class="container account-request large center">
      <?php if (!$loggedIn) : ?>
        <h1 class="logo">journaStage</h1>
      <?php endif; ?>
      <h2 class="text-center">Contactez nous ici</h2>
      <form action="./contact" method="POST">
        <?php if ($errorGlobal): ?>
          <div class="error-container">
            <p class="error-text"><i class="fa-solid fa-circle-xmark"></i><?= htmlspecialchars($errorGlobal) ?></p>
          </div>
        <?php endif; ?>
        <div class="input-container">
          <label for="lastName">Nom</label>
          <input type="text" id="lastName" name="lastName"
            <?= $loggedIn ?
              'value="' . htmlspecialchars($userLastName) . '" disabled' :
              'value="' . htmlspecialchars($lastNameContent) . '" placeholder="Nom" required' ?> />
          <p class="error-text"><?= htmlspecialchars($errorLastName) ?></p>
        </div>
        <div class="input-container">
          <label for="firstName">Prénom</label>
          <input type="text" id="firstName" name="firstName"
            <?= $loggedIn ?
              'value="' . htmlspecialchars($userFirstName) . '" disabled' :
              'value="' . htmlspecialchars($firstNameContent) . '" placeholder="Prénom" required' ?> />
          <p class="error-text"><?= htmlspecialchars($errorFirstName) ?></p>
        </div>
        <div class="input-container">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
            <?= $loggedIn ?
              'value="' . htmlspecialchars($userEmail) . '" disabled' :
              'value="' . htmlspecialchars($emailContent) . '" placeholder="Email" required' ?> />
          <p class="error-text"><?= htmlspecialchars($errorEmail) ?></p>
        </div>
        <div class="input-container">
          <label for="message">Comment pouvons-nous vous aider ?</label>
          <textarea id="message" name="message" placeholder="Votre message ici"><?= htmlspecialchars($messageContent) ?></textarea>
          <p class="error-text"><?= htmlspecialchars($errorMessage) ?></p>
        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-submit button-primary" disabled>Envoyer ma demande</button>
        </div>
      </form>
      <div class="create-account text-center">
        <?php if ($loggedIn) : ?>
          <a href="./" class="link">Retourner à l'accueil</a>
        <?php else : ?>
          <a href="./se-connecter" class="link">Retourner à la page de connexion</a>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>