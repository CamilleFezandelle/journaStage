<?php

$userRepository = new UserRepository(Database::getInstance());
$sessionRepository = new SessionRepository(Database::getInstance());

$authService = new AuthService($userRepository, $sessionRepository);
$user = $authService->getAuthenticatedUser();


if ($user) :
?>
  <header>
    <h2>HEADER</h2>
    <a href="#">Mes informations</a>
    <br>
    <br>
    <a href="./deconnexion">Déconnexion</a>
    <br>
    <br>
    <hr>
    <br>
  </header>
<?php endif; ?>