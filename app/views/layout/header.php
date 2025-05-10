<?php

$userRepository = new UserRepository(Database::getInstance());
$sessionRepository = new SessionRepository(Database::getInstance());

$authService = new AuthService($userRepository, $sessionRepository);
$user = $authService->getAuthenticatedUser();

$status = null;

if (isset($user)) {
  $status = $user->getStatus();
}

$statusName = '';

if ($status === 0) {
  $statusName = 'ÉTUDIANT';
} elseif ($status === 1) {
  $statusName = 'PROFESSEUR';
}


if ($user) :
?>
  <div class="menu">
    <span class="status">Espace <?= $statusName ?></span>
    <nav>
      <a href="<?= BASE_URL ?>/informations-personnelles" class="link <?= $linkactive ? 'active' : '' ?>">Mon compte</a>
      <a href="./deconnexion" class="link logout">Déconnexion</a>
    </nav>
  </div>
  <div class="header-container">
    <header>
      <div class="burger-menu">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <span class="status">Espace <?= $statusName ?></span>
      <a href="<?= BASE_URL ?>/" class="logo">journaStage</a>
      <nav>
        <a href="<?= BASE_URL ?>/informations-personnelles" class="link <?= $linkactive ? 'active' : '' ?>">Mon compte</a>
        <a href="<?= BASE_URL ?>/deconnexion" class="link logout">Déconnexion</a>
      </nav>
    </header>
  </div>
<?php endif; ?>