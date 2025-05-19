<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ClassRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../core/FormValidator.php';
include_once __DIR__ . '/../core/FlashCookie.php';
include_once __DIR__ . '/../views/renderView.php';

class ProfileController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;
  private ClassRepository $classRepository;
  private AuthService $authService;
  private FormValidator $formValidator;
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

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $scripts = ['personalInfo.js'];
    $cookiePwdContent = '';
    $cookieClassContent = '';

    $cookiePwd = $this->flashCookie->getAndDelete('change-pwd');
    $cookieClass = $this->flashCookie->getAndDelete('change-class');

    if ($cookiePwd === 'success') {
      $cookiePwdContent = 'success';
      array_push($scripts, 'temp-window.js');
    }

    if ($cookieClass === 'success') {
      $cookieClassContent = 'success';
      array_push($scripts, 'temp-window.js');
    }

    $classes = [];

    if ($user->getStatus() === 0) {
      $classes = $this->classRepository->getClassByStudentId($user->getIdUser());
    }

    if ($user->getStatus() === 1) {
      $classes = $this->classRepository->getClassByTeacherId($user->getIdUser());
    }

    renderView('personalInfo', [
      'title' => 'JournaStage - Éditer mon compte',
      'user' => $user,
      'classes' => $classes,
      'linkactive' => true,
      'cookiePwdContent' => $cookiePwdContent,
      'cookieClassContent' => $cookieClassContent,
      'scripts' => $scripts,
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

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $scripts = ['changePwd.js'];
    $cookieContent = '';

    $cookie = $this->flashCookie->getAndDelete('change-pwd');

    if ($cookie === 'wrong-pwd') {
      $cookieContent = 'wrong-pwd';
      array_push($scripts, 'temp-window.js');
    }

    if ($cookie === 'same-pwd') {
      $cookieContent = 'same-pwd';
      array_push($scripts, 'temp-window.js');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $userPassword = $user->getPassword();

      $_POST = [
        'current_password' => trim($_POST['current_password'] ?? ''),
        'new_password' => trim($_POST['new_password'] ?? ''),
        'confirm_password' => trim($_POST['confirm_password'] ?? '')
      ];

      $validator = new FormValidator();
      $errors = [];

      $formRules = [
        'current_password' => [
          FormValidator::MIN => 3,
          FormValidator::MAX => 255,
          FormValidator::REQUIRED => true
        ],
        'new_password' => [
          FormValidator::MIN => 8,
          FormValidator::MAX => 255,
          FormValidator::REQUIRED => true
        ],
        'confirm_password' => [
          FormValidator::CONFIRM_PASSWORD => 'new_password',
          FormValidator::REQUIRED => true
        ]
      ];

      $errors = $validator->validate($_POST, $formRules);

      if (empty($errors)) {

        if (!password_verify($_POST['current_password'], $userPassword)) {
          $this->flashCookie->set('change-pwd', 'wrong-pwd');
          header('Location: ./modifier-mot-de-passe');
          exit;
        }

        if ($_POST['new_password'] === $_POST['current_password']) {
          $this->flashCookie->set('change-pwd', 'same-pwd');
          header('Location: ./modifier-mot-de-passe');
          exit;
        }

        $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $changePwd = $this->userRepository->changeUserPassword($user->getIdUser(), $hashedPassword);

        if ($changePwd) {
          $this->flashCookie->set('change-pwd', 'success');
          header('Location: ../informations-personnelles');
          exit;
        } else {
          http_response_code(500);
          renderView('error/500', [
            'title' => 'JournaStage - Erreur'
          ]);
          exit;
        }
      }
    }

    renderView('changePwd', [
      'title' => 'JournaStage - Informations personnelles',
      'user' => $user,
      'linkactive' => true,
      'errors' => $errors ?? [],
      'cookieContent' => $cookieContent,
      'scripts' => $scripts,
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
        renderView('error/500', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }

      $this->flashCookie->set('change-class', 'success');
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
