<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../views/renderView.php';

class AuthController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  const ERROR_REQUIRED = 'Ce champ est requis';

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

      $_POST = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
        'password' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
      ]);

      $email = $_POST['email'] ?? '';
      $password = $_POST['password'] ?? '';

      if (empty($email)) {
        $error['email'] = self::ERROR_REQUIRED;
      }

      if (empty($password)) {
        $error['password'] = self::ERROR_REQUIRED;
      }

      if (empty(array_filter($error))) {
        $user = $authService->login($email, $password);

        if ($user) {
          header('Location: ./');
          exit;
        } else {
          $error['global'] = 'Identifiants incorrects';
        }
      }
    }

    renderView('login', [
      'title' => 'Connexion',
      'error' => $error,
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
