<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/ClassRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ReportRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../models/Session.php';
include_once __DIR__ . '/../views/renderView.php';

class ProfessorController
{
  private UserRepository $userRepository;
  private ClassRepository $classRepository;
  private SessionRepository $sessionRepository;
  private ReportRepository $reportRepository;
  private AuthService $authService;

  public function __construct()
  {
    $this->userRepository = new UserRepository(Database::getInstance());
    $this->classRepository = new ClassRepository(Database::getInstance());
    $this->sessionRepository = new SessionRepository(Database::getInstance());
    $this->reportRepository = new ReportRepository(Database::getInstance());
    $this->authService = new AuthService($this->userRepository, $this->sessionRepository);
  }
  public function viewClasses(): void
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

    $classes = $this->classRepository->getClassByTeacherId($user->getIdUser(), true);

    renderView('professor/classList', [
      'title' => 'JournaStage - Mes classes',
      'user' => $user,
      'classes' => $classes,
    ]);
  }

  public function viewClass(): void
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

    if (!isset($_GET['id']) || empty($_GET['id'])) {
      http_response_code(404);
      renderView('error/404', [
        'title' => 'JournaStage - Erreur'
      ]);
      exit;
    }

    $class = $this->classRepository->getClassByPublicId($_GET['id']);

    if (!$class) {
      http_response_code(404);
      renderView('error/404', [
        'title' => 'JournaStage - Erreur'
      ]);
      exit;
    }

    $verifyTeacher = $this->classRepository->verifyTeacherInClass($user->getIdUser(), $class->classId);

    if (!$verifyTeacher) {
      http_response_code(403);
      renderView('error/403', [
        'title' => 'JournaStage - Erreur'
      ]);
      exit;
    }

    $students = $this->classRepository->getAllStudentsByClassId($class->classId);

    renderView('professor/class', [
      'title' => 'JournaStage - Classe',
      'user' => $user,
      'class' => $class,
      'students' => $students,
      'scripts' => ['accordion.js'],
    ]);
  }

  public function viewStudent(): void
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

    if (!isset($_GET['id']) || empty($_GET['id'])) {
      http_response_code(404);
      renderView('error/404', [
        'title' => 'JournaStage - Erreur'
      ]);
      exit;
    }

    $student = $this->classRepository->getStudentByPublicId($_GET['id']);

    if (!$student) {
      http_response_code(404);
      renderView('error/404', [
        'title' => 'JournaStage - Erreur'
      ]);
      exit;
    }

    $verifyTeacher = $this->classRepository->verifyTeacherTeachingStudent($user->getIdUser(), $student['student_id']);

    if (!$verifyTeacher) {
      http_response_code(403);
      renderView('error/403', [
        'title' => 'JournaStage - Erreur'
      ]);
      exit;
    }

    $studentId = $student['student_id'];
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

    // consult student report
    if (isset($_GET['compte_rendu_id']) && !empty($_GET['compte_rendu_id'])) {
      $report = $this->reportRepository->getReportByPublicId($_GET['compte_rendu_id']);

      if (!$report) {
        http_response_code(404);
        renderView('error/404', [
          'title' => 'JournaStage - Erreur'
        ]);
        exit;
      }

      renderView('professor/studentReport', [
        'title' => 'JournaStage - Compte rendu',
        'user' => $user,
        'student' => $student,
        'report' => $report
      ]);
      exit;
    }

    renderView('professor/student', [
      'title' => 'JournaStage - Étudiant',
      'user' => $user,
      'student' => $student,
      'reports' => $reports,
      'scripts' => ['accordion.js'],
    ]);
  }
}
