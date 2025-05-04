<?php

class AuthController
{
  public function login(): void
  {
    echo "Page de connexion";
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
