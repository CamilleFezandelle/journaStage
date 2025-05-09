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
    echo "Page de la classe";
  }

  public function viewStudent(): void
  {
    echo "Page de l'étudiant";
  }
}
