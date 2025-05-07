<?php

include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/Report.php';

class ReportRepository
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function createReport(string $title, string $date, string $content, int $studentId): bool
  {
    $publicId = $this->generateUniquePublicId();
    $reportDate = date('Y-m-d H:i:s', strtotime($date));

    $query = "INSERT INTO JOURNASTAGE_REPORT 
    (
      public_id,
      title,
      date,
      content,
      student_id
    ) VALUES (
      :public_id,
      :title,
      :date,
      :content,
      :student_id
    )";

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':public_id', $publicId);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':date', $reportDate);
      $stmt->bindParam(':content', $content);
      $stmt->bindParam(':student_id', $studentId);

      $stmt->execute();
    } catch (PDOException) {
      return false;
    }
    return true;
  }

  private function generateUniquePublicId(): string
  {
    do {
      $publicId = bin2hex(random_bytes(4));

      $query = "SELECT COUNT(*) FROM JOURNASTAGE_REPORT WHERE public_id = :public_id";
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':public_id', $publicId);

      $stmt->execute();
      $count = $stmt->fetchColumn();
    } while ($count > 0);

    return $publicId;
  }
}
