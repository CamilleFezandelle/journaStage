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

    // $userPassword = $user->getPassword();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $userPassword = $user->getPassword();

      $currentPassword = trim($_POST['current_password'] ?? '');
      $newPassword = trim($_POST['new_password'] ?? '');
      $confirmPassword = trim($_POST['confirm_password'] ?? '');

      if (!password_verify($currentPassword, $userPassword)) {
        echo 'Mot de passe actuel incorrect.';
        exit;
      }

      if (password_verify($newPassword, $userPassword)) {
        echo 'Le nouveau mot de passe ne peut pas être le même que l\'ancien.';
        exit;
      }

      if ($newPassword !== $confirmPassword) {
        echo 'Les mots de passe ne correspondent pas.';
        exit;
      }

      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      $changePwd = $this->userRepository->changeUserPassword($user->getIdUser(), $hashedPassword);

      if ($changePwd) {
        header('Location: ../informations-personnelles');
        exit;
      } else {
        http_response_code(500);
        echo 'Erreur 500 : Impossible de changer le mot de passe.';
        exit;
      }
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
    $user = $this->authService->getAuthenticatedUser();

    if (!$user) {
      $this->authService->logout();
      exit;
    }

    if ($user->getPasswordStatus()) {
      header('Location: ./');
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $selectedClassId = $_POST['selected_class_id'] ?? null;
      $selectedClassIds = $_POST['selected_class_ids'] ?? [];

      if ($user->getStatus() === 0) {
        $remove = $this->classRepository->removeClassToStudent($user->getIdUser());
        $add = $this->classRepository->addClassToStudent($user->getIdUser(), $selectedClassId);
      } elseif ($user->getStatus() === 1) {
        $remove = $this->classRepository->removeClassesToTeacher($user->getIdUser());
        $add = $this->classRepository->addClassesToTeacher($user->getIdUser(), $selectedClassIds);
      }

      if (!$remove || !$add) {
        http_response_code(500);
        echo 'Erreur lors de la mise à jour des classes.';
        exit;
      }

      header('Location: ../informations-personnelles');
      exit;
    }

    $classList = $this->classRepository->getAllClasses();

    $classesUser = [];

    if ($user->getStatus() === 0) {
      $classesUser = $this->classRepository->getClassByStudentId($user->getIdUser());
    }

    if ($user->getStatus() === 1) {
      $classesUser = $this->classRepository->getClassByTeacherId($user->getIdUser());
    }

    $classesUser = array_map(function ($class) {
      return $class->classId;
    }, $classesUser);

    renderView('changeClass', [
      'title' => 'JournaStage - Informations personnelles',
      'user' => $user,
      'classList' => $classList,
      'classesUser' => $classesUser,
      'linkactive' => true,
    ]);
  }
}
