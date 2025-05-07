<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ReportRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../views/renderView.php';

class StudentController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;
  private ReportRepository $reportRepository;

  const ERROR_INPUT_REQUIRED = 'Ce champ est requis.';
  const ERROR_INPUT_INVALID = 'Le format de ce champ est invalide.';

  public function __construct()
  {
    $this->userRepository = new UserRepository(Database::getInstance());
    $this->sessionRepository = new SessionRepository(Database::getInstance());
    $this->reportRepository = new ReportRepository(Database::getInstance());
  }

  public function newReport(): void
  {
    $authService = new AuthService($this->userRepository, $this->sessionRepository);
    $user = $authService->getAuthenticatedUser();

    if (!$user) {
      $authService->logout();
      exit;
    }

    if ($user->getPasswordStatus()) {
      header('Location: ./');
      exit;
    }

    $error = [
      'title' => '',
      'date' => '',
      'content' => ''
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $_POST = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      ]);

      $title = trim($_POST['title'] ?? '');
      $date = trim($_POST['date'] ?? '');
      $content = trim($_POST['content'] ?? '');

      if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $error['date'] = self::ERROR_INPUT_INVALID;
        return;
      }

      if (strlen($title) < 5 || strlen($title) > 120) {
        $error['title'] = self::ERROR_INPUT_INVALID;
        return;
      }

      if (strlen($content) < 5 || strlen($content) > 3000) {
        $error['content'] = self::ERROR_INPUT_INVALID;
        return;
      }

      if (empty($title)) {
        $error['title'] = self::ERROR_INPUT_REQUIRED;
      }

      if (empty($date)) {
        $error['date'] = self::ERROR_INPUT_REQUIRED;
      }

      if (empty($content)) {
        $error['content'] = self::ERROR_INPUT_REQUIRED;
      }

      if ($this->reportRepository->createReport($title, $date, $content, $user->getIdUser())) {
        header('Location: ./?success=1');
        exit;
      } else {
        http_response_code(500);
        renderView('error/500', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }
    }

    renderView('student/newReport', [
      'title' => 'JournaStage - Créer un compte-rendu',
      'user' => $user,
      'error' => $error,
      'scripts' => ['newReport.js'],
    ]);
  }

  public function myReports(): void
  {
    echo "Page de mes comptes-rendus";
  }
}
