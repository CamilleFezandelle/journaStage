<?php

include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/User.php';

class UserRepository
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getUserByEmail(string $email)
  {
    $query = "SELECT * FROM JOURNASTAGE_USER WHERE email = :email";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $data = $stmt->fetch();

    if ($data) {
      return new User(
        $data['id_user'],
        $data['public_id'],
        $data['last_name'],
        $data['first_name'],
        $data['email'],
        $data['password'],
        $data['temporary_password'],
        $data['birth_date'],
        $data['status'],
        $data['admin'],
        $data['student_class_id']
      );
    }

    return null;
  }

  public function getUserById(int $idUser)
  {
    $query = "SELECT * FROM JOURNASTAGE_USER WHERE id_user = :id_user";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id_user', $idUser);
    $stmt->execute();
    $data = $stmt->fetch();

    if ($data) {
      return new User(
        $data['id_user'],
        $data['public_id'],
        $data['last_name'],
        $data['first_name'],
        $data['email'],
        $data['password'],
        $data['temporary_password'],
        $data['birth_date'],
        $data['status'],
        $data['admin'],
        $data['student_class_id']
      );
    }

    return null;
  }
}
