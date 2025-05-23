<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ReportRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../core/FormValidator.php';
include_once __DIR__ . '/../core/FlashCookie.php';
include_once __DIR__ . '/../models/Session.php';
include_once __DIR__ . '/../views/renderView.php';

class StudentController
{
  private UserRepository $userRepository;
  private SessionRepository $sessionRepository;
  private ReportRepository $reportRepository;
  private AuthService $authService;
  private FormValidator $formValidator;
  private FlashCookie $flashCookie;

  public function __construct()
  {
    $this->userRepository = new UserRepository(Database::getInstance());
    $this->sessionRepository = new SessionRepository(Database::getInstance());
    $this->reportRepository = new ReportRepository(Database::getInstance());
    $this->authService = new AuthService($this->userRepository, $this->sessionRepository);
    $this->flashCookie = new FlashCookie();
  }

  public function newReport(): void
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

      $_POST = [
        'title' => trim($_POST['title'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'content' => trim($_POST['content'] ?? '')
      ];

      $validator = new FormValidator();

      $formRules = [
        'title' => [
          FormValidator::MIN => 5,
          FormValidator::MAX => 120,
          FormValidator::REQUIRED => true
        ],
        'date' => [
          FormValidator::DATE => true,
          FormValidator::REQUIRED => true
        ],
        'content' => [
          FormValidator::MIN => 5,
          FormValidator::MAX => 3000,
          FormValidator::REQUIRED => true
        ]
      ];

      $errors = $validator->validate($_POST, $formRules);

      if (empty($errors)) {
        $title = $_POST['title'];
        $date = $_POST['date'];
        $content = $_POST['content'];

        if ($this->reportRepository->createReport($title, $date, $content, $user->getIdUser())) {
          $this->flashCookie->set('report_create', 'success');
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
    }

    renderView('student/newReport', [
      'title' => 'JournaStage - Créer un nouveau compte rendu',
      'user' => $user,
      'errors' => $errors ?? [],
      'post' => $_POST ?? [],
      'scripts' => ['newReport.js'],
    ]);
  }

  public function myReports(): void
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

    $scripts = ['accordion.js'];
    $successDelete = false;

    $cookie = $this->flashCookie->getAndDelete('report_delete');

    if ($cookie === 'success') {
      $successDelete = true;
      array_push($scripts, 'temp-window.js');
    }

    // Consult a report
    if (isset($_GET['id'])) {
      $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

      if ($id === '') {
        http_response_code(400);
        renderView('error/400', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }

      $report = $this->reportRepository->getReportByPublicId($id);

      if ($report === null) {
        http_response_code(404);
        renderView('error/404', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }

      if ($report->getStudentId() !== $user->getIdUser()) {
        http_response_code(403);
        renderView('error/403', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }

      $scripts = ['dropdown.js', 'confirmWindow.js'];
      $successUpdate = false;

      $cookie = $this->flashCookie->getAndDelete('report_update');

      if ($cookie === 'success') {
        $successUpdate = true;
        array_push($scripts, 'temp-window.js');
      }

      // Delete a report
      if (isset($_GET['action']) && $_GET['action'] === 'delete') {

        if ($this->reportRepository->deleteReport($report->getIdReport())) {
          $this->flashCookie->set('report_delete', 'success');
          header('Location: ./mes-comptes-rendus');
          exit;
        } else {
          http_response_code(500);
          renderView('error/500', [
            'title' => 'JournaStage - Erreur'
          ]);
          exit;
        }
      }

      // Edit a report
      if (isset($_GET['action']) && $_GET['action'] === 'edit') {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

          $_POST = [
            'title' => trim($_POST['title'] ?? ''),
            'date' => trim($_POST['date'] ?? ''),
            'content' => trim($_POST['content'] ?? '')
          ];

          $validator = new FormValidator();

          $formRules = [
            'title' => [
              FormValidator::MIN => 5,
              FormValidator::MAX => 120,
              FormValidator::REQUIRED => true
            ],
            'date' => [
              FormValidator::DATE => true,
              FormValidator::REQUIRED => true
            ],
            'content' => [
              FormValidator::MIN => 5,
              FormValidator::MAX => 3000,
              FormValidator::REQUIRED => true
            ]
          ];

          $errors = $validator->validate($_POST, $formRules);

          if (empty($errors)) {
            $title = $_POST['title'];
            $date = $_POST['date'];
            $content = $_POST['content'];

            if ($this->reportRepository->updateReport($report->getIdReport(), $title, $date, $content)) {
              $this->flashCookie->set('report_update', 'success');
              header('Location: ./mes-comptes-rendus?id=' . $report->getPublicId());
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

        renderView('student/editReport', [
          'title' => 'JournaStage - Éditer mon compte rendu',
          'user' => $user,
          'report' => $report,
          'errors' => $errors ?? [],
          'post' => $_POST ?? [],
          'scripts' => ['editReport.js']
        ]);
        exit;
      }

      renderView('student/viewReport', [
        'title' => 'JournaStage - Mes comptes rendus',
        'user' => $user,
        'report' => $report,
        'successUpdate' => $successUpdate,
        'scripts' => $scripts
      ]);
      exit;
    }

    $studentId = $user->getIdUser();
    $reports = $this->reportRepository->getAllReportsByStudentId($studentId);

    $reportsByYear = [];
    foreach ($reports as $report) {
      $year = date('Y', strtotime($report->getDate()));
      if (!isset($reportsByYear[$year])) {
        $reportsByYear[$year] = [];
      }
      $reportsByYear[$year][] = $report;
    }
    krsort($reportsByYear);
    $reports = $reportsByYear;

    renderView('student/myReports', [
      'title' => 'JournaStage - Mes comptes rendus',
      'user' => $user,
      'reports' => $reports,
      'successDelete' => $successDelete,
      'scripts' => $scripts
    ]);
  }
}
