<?php

class FormValidator
{
  public const REQUIRED = 'required';
  public const MIN = 'minLength';
  public const MAX = 'maxLength';
  public const EMAIL = 'email';
  public const DATE = 'date';
  public const CONFIRM_PASSWORD = 'confirm_password';

  private array $errors = [];

  public function validate(array $data, array $rules): array
  {
    $this->errors = [];

    foreach ($rules as $field => $fieldRules) {
      $value = trim($data[$field] ?? '');

      foreach ($fieldRules as $rule => $constraint) {
        switch ($rule) {
          case self::REQUIRED:
            if ($constraint && $value === '') {
              $this->errors[$field] = 'Ce champ est requis.';
            }
            break;

          case self::MIN:
            if (strlen($value) < $constraint) {
              $this->errors[$field] = 'Ce champ doit contenir au moins ' . $constraint . ' caractères.';
            }
            break;

          case self::MAX:
            if (strlen($value) > $constraint) {
              $this->errors[$field] = 'Ce champ doit contenir au maximum ' . $constraint . ' caractères.';
            }
            break;

          case self::EMAIL:
            if ($constraint && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
              $this->errors[$field] = 'Cette adresse e-mail est invalide.';
            }
            break;

          case self::DATE:
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
              $this->errors[$field] = 'Cette date est invalide.';
            }
            break;

          case self::CONFIRM_PASSWORD:
            if ($constraint && $value !== $data[$constraint]) {
              $this->errors[$field] = 'Les mots de passe ne correspondent pas.';
            }
            break;
        }
      }
    }

    return $this->errors;
  }
}
