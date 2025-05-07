<?php

$userRepository = new UserRepository(Database::getInstance());
$sessionRepository = new SessionRepository(Database::getInstance());

$authService = new AuthService($userRepository, $sessionRepository);
$user = $authService->getAuthenticatedUser();


if ($user) :
?>
  <div class="menu">
    <span class="status">Espace ÉTUDIANT</span>
    <nav>
      <a href="#" class="link">Mon compte</a>
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
      <span class="status">Espace ÉTUDIANT</span>
      <a href="#" class="logo">journaStage</a>
      <nav>
        <a href="#" class="link">Mon compte</a>
        <a href="./deconnexion" class="link logout">Déconnexion</a>
      </nav>
    </header>
  </div>
<?php endif; ?>