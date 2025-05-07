<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../views/renderView.php';

class HomeController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  public function __construct()
  {
    $this->userRepository = new UserRepository(Database::getInstance());
    $this->sessionRepository = new SessionRepository(Database::getInstance());
  }

  public function home(): void
  {
    $authService = new AuthService($this->userRepository, $this->sessionRepository);
    $user = $authService->getAuthenticatedUser();

    if (!$user) {
      $authService->logout();
      exit;
    }

    if ($user->getPasswordStatus()) {
      $this->changeTemporaryPassword($user);
      exit;
    }

    $userStatus = $user->getStatus();

    switch ($userStatus) {
      case 0:
        $this->studentHome($user);
        break;
      case 1:
        $this->professorHome($user);
        break;
      default:
        http_response_code(403);
        echo "403 Forbidden";
        break;
    }
  }

  private function changeTemporaryPassword(User $user): void
  {
    renderView('changeTempPwd', [
      'title' => 'Changement de mot de passe',
      'user' => $user
    ]);
  }

  private function studentHome(User $user): void
  {
    $success = false;

    if (isset($_GET['success']) && $_GET['success'] === '1') {
      $success = true;
    }

    renderView('student/home', [
      'title' => 'JournaStage - Accueil',
      'user' => $user,
      'success' => $success
    ]);
  }

  private function professorHome(User $user): void
  {
    renderView('professor/home', [
      'title' => 'JournaStage - Accueil',
      'user' => $user
    ]);
  }
}
