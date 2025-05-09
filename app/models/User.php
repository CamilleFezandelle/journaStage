<?php

class User
{
  private int $idUser;
  private string $publicId;
  private string $lastName;
  private string $firstName;
  private string $email;
  private string $password;
  private bool $temporaryPassword;
  private string $birthDate;
  private int $status;
  private bool $admin;
  private ?int $studentClassId;

  public function __construct(
    int $idUser,
    string $publicId,
    string $lastName,
    string $firstName,
    string $email,
    string $password,
    bool $temporaryPassword,
    string $birthDate,
    int $status,
    bool $admin,
    ?int $studentClassId
  ) {
    $this->idUser = $idUser;
    $this->publicId = $publicId;
    $this->lastName = $lastName;
    $this->firstName = $firstName;
    $this->email = $email;
    $this->password = $password;
    $this->temporaryPassword = $temporaryPassword;
    $this->birthDate = $birthDate;
    $this->status = $status;
    $this->admin = $admin;
    $this->studentClassId = $studentClassId;
  }

  public function getIdUser(): int
  {
    return $this->idUser;
  }

  public function getPublicId(): string
  {
    return $this->publicId;
  }

  public function getLastName(): string
  {
    return $this->lastName;
  }

  public function getFirstName(): string
  {
    return $this->firstName;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getBirthDate(): string
  {
    return $this->birthDate;
  }

  public function getPasswordStatus(): bool
  {
    return $this->temporaryPassword;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function getStatus(): int
  {
    return $this->status;
  }

  public function isAdmin(): bool
  {
    return $this->admin;
  }
}
