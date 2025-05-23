<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ClassRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../core/FlashCookie.php';
include_once __DIR__ . '/../views/renderView.php';

class HomeController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;
  private ClassRepository $classRepository;
  private AuthService $authService;
  private FlashCookie $flashCookie;

  public function __construct()
  {
    $this->userRepository = new UserRepository(Database::getInstance());
    $this->sessionRepository = new SessionRepository(Database::getInstance());
    $this->authService = new AuthService($this->userRepository, $this->sessionRepository);
    $this->flashCookie = new FlashCookie();
  }

  public function home(): void
  {
    $user = $this->authService->getAuthenticatedUser();

    if (!$user) {
      $this->authService->logout();
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
        renderView('error/403', [
          'title' => 'JournaStage - Erreur'
        ]);
        break;
    }
  }

  private function changeTemporaryPassword(User $user): void
  {
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $password = $_POST['password'] ?? '';
      $confirmPassword = $_POST['confirm-password'] ?? '';

      if ($password !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
        return;
      }

      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $changePwd = $this->userRepository->changeUserPassword($user->getIdUser(), $hashedPassword);
      $changePwdStatus = $this->userRepository->changeTemporaryPassword($user->getIdUser());

      if ($changePwd && $changePwdStatus) {
        header('Location: ./');
        exit;
      } else {
        http_response_code(500);
        renderView('error/500', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }
    }
    renderView('changeTempPwd', [
      'title' => 'Changement de mot de passe',
      'user' => $user,
      'error' => $error,
      'scripts' => ['changeTempPwd.js']
    ]);
  }

  private function studentHome(User $user): void
  {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $classRepository = new ClassRepository(Database::getInstance());
    $class = $classRepository->getClassByStudentId($user->getIdUser()) ? true : false;

    $scripts = [];
    $successCreate = false;

    $cookie = $this->flashCookie->getAndDelete('report_create');

    if ($cookie === 'success') {
      $successCreate = true;
      array_push($scripts, 'temp-window.js');
    }

    renderView('student/home', [
      'title' => 'JournaStage - Accueil',
      'user' => $user,
      'class' => $class,
      'successCreate' => $successCreate,
      'scripts' => $scripts
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
