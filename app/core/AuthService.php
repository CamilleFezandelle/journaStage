<?php

class AuthService
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;

  public function __construct(UserRepository $userRepository, SessionRepository $sessionRepository)
  {
    $this->userRepository = $userRepository;
    $this->sessionRepository = $sessionRepository;
  }

  public function login(string $email, string $password): ?User
  {
    $user = $this->userRepository->getUserByEmail($email);

    if ($user && password_verify($password, $user->getPassword())) {
      $userId = $user->getIdUser();
      $sessionExpiresAt = time() + 60 * 60 * 24 * 30;

      $sessionToken = $this->sessionRepository->createSession($userId, $sessionExpiresAt);

      setcookie(
        'session_token',
        $sessionToken,
        [
          'expires' => $sessionExpiresAt,
          'path' => './',
          'domain' => '',
          'secure' => false,
          // 'secure' => true, // HTTPS
          'httponly' => true,
          'samesite' => 'Strict'
        ]
      );

      return $user;
    }

    return null;
  }

  public function getAuthenticatedUser(): ?User
  {
    if (!isset($_COOKIE['session_token'])) {
      return null;
    }

    $session = $this->sessionRepository->getSessionByToken($_COOKIE['session_token']);

    if (!$session) {
      return null;
    }

    $userId = $session->getUserId();
    $user = $this->userRepository->getUserById($userId);

    return $user ? $user : null;
  }

  public function logout(): void
  {
    if (isset($_COOKIE['session_token'])) {
      $this->sessionRepository->deleteSession($_COOKIE['session_token']);

      setcookie(
        'session_token',
        '',
        [
          'expires' => time() - 3600,
          'path' => './',
          'domain' => '',
          'secure' => false,
          // 'secure' => true, // HTTPS
          'httponly' => true,
          'samesite' => 'Strict'
        ]
      );
    }

    header('Location: ./se-connecter');
    exit;
  }
}
