<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ClassRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../core/FlashCookie.php';
include_once __DIR__ . '/../views/renderView.php';

class ProfileController
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
    $this->classRepository = new ClassRepository(Database::getInstance());
    $this->authService = new AuthService($this->userRepository, $this->sessionRepository);
    $this->flashCookie = new FlashCookie();
  }

  public function personalInfo(): void
  {
    $user = $this->authService->getAuthenticatedUser();

    if (!$user) {
      $this->authService->logout();
      exit;
    }

    if ($user->getPasswordStatus()) {
      header('Location: ./');
      exit;
    }

    $classes = [];

    if ($user->getStatus() === 0) {
      $classes = $this->classRepository->getClassByStudentId($user->getIdUser());
    }

    if ($user->getStatus() === 1) {
      $classes = $this->classRepository->getClassByTeacherId($user->getIdUser());
    }

    renderView('personalInfo', [
      'title' => 'JournaStage - Informations personnelles',
      'user' => $user,
      'classes' => $classes,
      'linkactive' => true,
      'scripts' => ['personalInfo.js'],
    ]);
  }

  public function editPassword(): void
  {
    $user = $this->authService->getAuthenticatedUser();

    if (!$user) {
      $this->authService->logout();
      exit;
    }

    if ($user->getPasswordStatus()) {
      header('Location: ./');
      exit;
    }

    renderView('changePwd', [
      'title' => 'JournaStage - Informations personnelles',
      'user' => $user,
      'linkactive' => true,
      'scripts' => ['changePwd.js'],
    ]);
  }

  public function editClass(): void
  {
    echo "Page de modification de la classe";
  }
}
