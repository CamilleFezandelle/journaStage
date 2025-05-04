<?php

class HomeController
{
  public function home(): void
  {
    global $userStatus;

    if (!isset($userStatus)) {
      header("Location: /login");
      exit;
    }

    switch ($userStatus) {
      case 0:
        $this->studentHome();
        break;
      case 1:
        $this->professorHome();
        break;
      default:
        http_response_code(403);
        echo "403: Forbidden";
        break;
    }
  }

  private function studentHome(): void
  {
    echo "Accueil étudiant";
  }

  private function professorHome(): void
  {
    echo "Accueil professeur";
  }
}
