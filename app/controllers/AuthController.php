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
    renderView('lostPassword', [
      'title' => 'Mot de passe oublié',
      'scripts' => [
        'forgotPassword.js'
      ]
    ]);
  }

  public function requestAccount(): void
  {
    echo "Page de demande de compte";
  }

  public function contact(): void
  {
    $authService = new AuthService($this->userRepository, $this->sessionRepository);
    $user = $authService->getAuthenticatedUser() ?? null;
    $scripts = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($user) {
        $lastName = $user->getLastName();
        $firstName = $user->getFirstName();
        $email = $user->getEmail();
      } else {
        $lastName = trim($_POST['lastName'] ?? '');
        $firstName = trim($_POST['firstName'] ?? '');
        $email = trim($_POST['email'] ?? '');
      }
      $message = trim($_POST['message'] ?? '');

      $to = 'fezandellecamille@gmail.com';
      $subject = 'JournaStage - Un message de ' . $firstName . ' ' . $lastName;
      $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
      $body = "Nom: $lastName\n" .
        "Prénom: $firstName\n" .
        "Email: $email\n" .
        "Message:\n$message";

      $success = mail($to, $subject, $body, $headers);

      if ($success) {
        $message = 'Votre message a été envoyé avec succès.';
      } else {
        $message = 'Une erreur est survenue lors de l\'envoi de votre message.';
      }
    }

    if ($user) {
      $scripts = ['contactLogged.js'];
    } else {
      $scripts = ['contactNotLogged.js'];
    }

    renderView('contact', [
      'title' => 'JournaStage - Contact',
      'user' => $user,
      'scripts' => $scripts,
    ]);
  }
}
