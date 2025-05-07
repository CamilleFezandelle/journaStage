<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../views/renderView.php';

class AuthController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  const ERROR_INPUT_REQUIRED = 'Ce champ est requis.';
  const ERROR_INPUT_INVALID = 'Le format de ce champ est invalide.';
  const ERROR_INCORRECT = 'Email ou mot de passe incorrect.';


  public function __construct()
  {
    $this->userRepository = new UserRepository(Database::getInstance());
    $this->sessionRepository = new SessionRepository(Database::getInstance());
  }

  public function login(): void
  {
    $authService = new AuthService($this->userRepository, $this->sessionRepository);
    $user = $authService->getAuthenticatedUser();

    if ($user) {
      header('Location: ./');
      exit;
    }

    $error = [
      'global' => '',
      'email' => '',
      'password' => ''
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
      $password = trim($_POST['password'] ?? '');

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = self::ERROR_INPUT_INVALID;
      }

      if (strlen($password) < 3 || strlen($password) > 255) {
        $error['password'] = self::ERROR_INPUT_INVALID;
      }

      if (empty($email)) {
        $error['email'] = self::ERROR_INPUT_REQUIRED;
      }

      if (empty($password)) {
        $error['password'] = self::ERROR_INPUT_REQUIRED;
      }

      if (empty(array_filter($error))) {
        $user = $authService->login($email, $password);

        if ($user) {
          header('Location: ./');
          exit;
        } else {
          $error['global'] = self::ERROR_INCORRECT;
        }
      }
    }

    renderView('login', [
      'title' => 'Connexion',
      'error' => $error,
      'scripts' => [
        'login.js'
      ]
    ]);
  }

  public function logout(): void
  {
    $authService = new AuthService($this->userRepository, $this->sessionRepository);
    $authService->logout();
  }

  public function forgotPassword(): void
  {
    echo "Page mot de passe oublié";
  }

  public function requestAccount(): void
  {
    echo "Page de demande de compte";
  }

  public function contact(): void
  {
    echo "Page de contact";
  }
}
