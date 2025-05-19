<?php

include_once __DIR__ . '/../repositories/UserRepository.php';
include_once __DIR__ . '/../repositories/SessionRepository.php';
include_once __DIR__ . '/../repositories/ClassRepository.php';
include_once __DIR__ . '/../core/AuthService.php';
include_once __DIR__ . '/../core/FormValidator.php';
include_once __DIR__ . '/../core/FlashCookie.php';
include_once __DIR__ . '/../views/renderView.php';

class AuthController
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
    $this->authService = new AuthService($this->userRepository, $this->sessionRepository);
    $this->flashCookie = new FlashCookie();
  }

  public function login(): void
  {
    $user = $this->authService->getAuthenticatedUser();

    if ($user) {
      header('Location: ./');
      exit;
    }

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $scripts = ['login.js'];
    $cookieLostPwdContent = '';


    $cookieLostPwd = $this->flashCookie->getAndDelete('forgot-password');

    if ($cookieLostPwd) {
      $cookieLostPwdContent = $_COOKIE['forgot-password'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $_POST = [
        'email' => trim($_POST['email'] ?? ''),
        'password' => trim($_POST['password'] ?? '')
      ];

      $validator = new FormValidator();
      $errors = [];

      $formRules = [
        'email' => [
          'email' => true,
          'maxLength' => 255,
          'required' => true
        ],
        'password' => [
          'minLength' => 3,
          'maxLength' => 255,
          'required' => true
        ]
      ];

      $errors = $validator->validate($_POST, $formRules);

      if (empty($errors)) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->authService->login($email, $password);

        if ($user) {
          header('Location: ./');
          exit;
        } else {
          $errors['global'] = 'Identifiants invalides.';
        }
      }
    }

    renderView('login', [
      'title' => 'Connexion',
      'errors' => $errors ?? [],
      'cookieLostPwd' => $cookieLostPwdContent,
      'scripts' => $scripts,
    ]);
  }

  public function logout(): void
  {
    $this->authService->logout();
  }

  public function forgotPassword(): void
  {
    $user = $this->authService->getAuthenticatedUser();

    if ($user) {
      header('Location: ./');
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $_POST = [
        'email' => trim($_POST['email'] ?? '')
      ];

      $validator = new FormValidator();
      $errors = [];

      $formRules = [
        'email' => [
          'email' => true,
          'maxLength' => 255,
          'required' => true
        ]
      ];

      $errors = $validator->validate($_POST, $formRules);

      if (empty($errors)) {
        $email = $_POST['email'];
        $user = $this->userRepository->getUserByEmail($email);

        $temporaryPassword = bin2hex(random_bytes(6));

        if ($user) {
          $temporaryPasswordHash = password_hash($temporaryPassword, PASSWORD_DEFAULT);

          $this->userRepository->resetUserPassword($user->getIdUser(), $temporaryPasswordHash);
        }

        $this->flashCookie->set('forgot-password', $temporaryPassword);
        header('Location: ./se-connecter');
        exit;
      }
    }

    renderView('lostPassword', [
      'title' => 'JournaStage - Mot de passe oublié',
      'errors' => $errors ?? [],
      'post' => $_POST ?? [],
      'scripts' => [
        'lostPassword.js'
      ]
    ]);
  }

  public function requestAccount(): void
  {
    $user = $this->authService->getAuthenticatedUser();

    if ($user) {
      header('Location: ./');
      exit;
    }

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $cookie = $this->flashCookie->getAndDelete('request-account');
    $cookieContent = '';

    $scripts = ['requestAccount.js'];

    if ($cookie === 'success') {
      $cookieContent = 'success';
      array_push($scripts, 'temp-window.js');
    }

    $this->classRepository = new ClassRepository(Database::getInstance());

    $classList = $this->classRepository->getAllClasses();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $_POST['lastName'] = trim($_POST['lastName'] ?? '');
      $_POST['firstName'] = trim($_POST['firstName'] ?? '');
      $_POST['email'] = trim($_POST['email'] ?? '');
      $_POST['birthDate'] = trim($_POST['birthDate'] ?? '');

      $validator = new FormValidator();
      $errors = [];

      $formRules = [
        'lastName' => [
          'minLength' => 2,
          'maxLength' => 50,
          'required' => true
        ],
        'firstName' => [
          'minLength' => 2,
          'maxLength' => 50,
          'required' => true
        ],
        'email' => [
          'email' => true,
          'maxLength' => 255,
          'required' => true
        ],
        'birthDate' => [
          'date' => true,
          'required' => true
        ],
      ];

      $errors = $validator->validate($_POST, $formRules);

      if (empty($errors)) {
        $lastName = $_POST['lastName'] ?? '';
        $firstName = $_POST['firstName'] ?? '';
        $email = $_POST['email'] ?? '';
        $birthDate = $_POST['birthDate'] ?? '';
        $status = $_POST['status'] ?? '';
        $classId = $_POST['selected_class_id'] ?? null;
        $classIds = $_POST['selected_class_ids'] ?? [];
        $class = [];

        if ($status === 'student') {
          foreach ($classList as $classItem) {
            if ($classItem['class_id'] == $classId) {
              $class[] = $classItem;
              break;
            }
          }
        } elseif ($status === 'teacher') {
          foreach ($classList as $classItem) {
            if (in_array($classItem['class_id'], $classIds)) {
              $class[] = $classItem;
            }
          }
        } else {
          http_response_code(400);
          renderView('error/400', [
            'title' => 'JournaStage - Erreur'
          ]);
          exit;
        }

        $to = 'fezandellecamille@gmail.com';

        $subject = 'JournaStage - Demande de compte de ' . $firstName . ' ' . $lastName;

        $headers = 'From: ' . $email . "\r\n" .
          'Reply-To: ' . $email . "\r\n" .
          'X-Mailer: PHP/' . phpversion();

        $body = "Nom: $lastName\n" .
          "Prénom: $firstName\n" .
          "Email: $email\n" .
          "Date de naissance: $birthDate\n" .
          "Statut: $status\n\n" .
          "Classe(s): \n";

        foreach ($class as $classItem) {
          $schoolFullName = $classItem['school_name'] . ' (' . $classItem['school_city'] . ', ' . $classItem['school_department_number'] . ')';
          $classFullName = $classItem['class_name'] . ' (année ' . $classItem['class_year_number'] . ')';
          $body .= "$schoolFullName - $classFullName\n";
        }

        $success = mail($to, $subject, $body, $headers);

        if (!$success) {
          http_response_code(500);
          renderView('error/500', [
            'title' => 'JournaStage - Erreur'
          ]);
          exit;
        }

        $this->flashCookie->set('request-account', 'success');
        header('Location: ./demander-un-compte');
        exit;
      }
    }

    renderView('requestAccount', [
      'title' => 'JournaStage - Demande de compte',
      'classList' => $classList,
      'cookie' => $cookieContent,
      'errors' => $errors ?? [],
      'post' => $_POST ?? [],
      'scripts' => $scripts,
    ]);
  }

  public function contact(): void
  {
    $user = $this->authService->getAuthenticatedUser() ?? null;

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if ($user) {
      $scripts = ['contactLogged.js'];
    } else {
      $scripts = ['contactNotLogged.js'];
    }

    $cookie = $this->flashCookie->getAndDelete('contact');
    $cookieContent = '';

    if ($cookie === 'success') {
      $cookieContent = 'success';
      array_push($scripts, 'temp-window.js');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $_POST = [
        'lastName' => trim($_POST['lastName'] ?? ''),
        'firstName' => trim($_POST['firstName'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'message' => trim($_POST['message'] ?? '')
      ];

      $validator = new FormValidator();
      $errors = [];

      if ($user) {
        $formRules = [
          'message' => [
            'minLength' => 3,
            'maxLength' => 3000,
            'required' => true
          ]
        ];
      } else {
        $formRules = [
          'lastName' => [
            'minLength' => 2,
            'maxLength' => 50,
            'required' => true
          ],
          'firstName' => [
            'minLength' => 2,
            'maxLength' => 50,
            'required' => true
          ],
          'email' => [
            'email' => true,
            'maxLength' => 255,
            'required' => true
          ],
          'message' => [
            'minLength' => 5,
            'maxLength' => 3000,
            'required' => true
          ]
        ];
      }

      $errors = $validator->validate($_POST, $formRules);

      if (empty($errors)) {

        if ($user) {
          $lastName = $user->getLastName() ?? '';
          $firstName = $user->getFirstName() ?? '';
          $email = $user->getEmail() ?? '';
        } else {
          $lastName = $_POST['lastName'] ?? '';
          $firstName = $_POST['firstName'] ?? '';
          $email = $_POST['email'] ?? '';
        }
        $message = $_POST['message'] ?? '';

        $to = 'fezandellecamille@gmail.com';

        $subject = 'JournaStage - Message de ' . $firstName . ' ' . $lastName;

        $headers = 'From: ' . $email . "\r\n" .
          'Reply-To: ' . $email . "\r\n" .
          'X-Mailer: PHP/' . phpversion();

        $body = "Nom: $lastName\n" .
          "Prénom: $firstName\n" .
          "Email: $email\n\n" .
          "Message:\n$message";

        $success = mail($to, $subject, $body, $headers);

        if (!$success) {
          http_response_code(500);
          renderView('error/500', [
            'title' => 'JournaStage - Erreur'
          ]);
          exit;
        }

        echo 'Message envoyé avec succès.';
        $this->flashCookie->set('contact', 'success');
        header('Location: ./contact');
        exit;
      }
    }

    renderView('contact', [
      'title' => 'JournaStage - Contact',
      'user' => $user,
      'errors' => $errors ?? [],
      'post' => $_POST ?? [],
      'cookie' => $cookieContent,
      'scripts' => $scripts,
    ]);
  }
}
