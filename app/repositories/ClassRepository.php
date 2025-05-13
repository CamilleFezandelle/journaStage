<?php

include_once __DIR__ . '/../models/Database.php';
include_once __DIR__ . '/../models/Class.php';

class ClassRepository
{
  private PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function getAllClasses(): array
  {
    $query = 'SELECT
              JOURNASTAGE_CLASS.id_class AS class_id,
              JOURNASTAGE_CLASS.public_id AS class_public_id,
              JOURNASTAGE_CLASS.name AS class_name,
              JOURNASTAGE_CLASS.year_number AS class_year_number,
              JOURNASTAGE_SCHOOL.id_school AS school_id,
              JOURNASTAGE_SCHOOL.public_id AS school_public_id,
              JOURNASTAGE_SCHOOL.name AS school_name,
              JOURNASTAGE_SCHOOL.city AS school_city,
              JOURNASTAGE_SCHOOL.department_number AS school_department_number
              FROM JOURNASTAGE_CLASS, JOURNASTAGE_SCHOOL
              WHERE JOURNASTAGE_CLASS.school_id = JOURNASTAGE_SCHOOL.id_school';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->execute();
      $rows = $stmt->fetchAll();

      usort($rows, function ($a, $b) {
        $schoolCompare = strcmp($a['school_name'], $b['school_name']);

        if ($schoolCompare !== 0) {
          return $schoolCompare;
        }

        return strcmp($a['class_name'], $b['class_name']);
      });

      return $rows;
    } catch (PDOException) {
      return [];
    }
  }

  public function getClassByStudentId(int $studentId): array
  {
    $query = 'SELECT
              JOURNASTAGE_CLASS.id_class AS class_id,
              JOURNASTAGE_CLASS.public_id AS class_public_id,
              JOURNASTAGE_CLASS.name AS class_name,
              JOURNASTAGE_CLASS.year_number AS class_year_number,
              JOURNASTAGE_SCHOOL.id_school AS school_id,
              JOURNASTAGE_SCHOOL.public_id AS school_public_id,
              JOURNASTAGE_SCHOOL.name AS school_name,
              JOURNASTAGE_SCHOOL.city AS school_city,
              JOURNASTAGE_SCHOOL.department_number AS school_department_number
              FROM JOURNASTAGE_USER, JOURNASTAGE_CLASS, JOURNASTAGE_SCHOOL
              WHERE JOURNASTAGE_USER.student_class_id = JOURNASTAGE_CLASS.id_class
              AND JOURNASTAGE_CLASS.school_id = JOURNASTAGE_SCHOOL.id_school
              AND JOURNASTAGE_USER.id_user = :studentId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':studentId', $studentId);

      $stmt->execute();
      $rows = $stmt->fetchAll();

      $classArray = [];

      foreach ($rows as $row) {
        $school = new SchoolModel(
          $row['school_id'],
          $row['school_public_id'],
          $row['school_name'],
          $row['school_city'],
          $row['school_department_number']
        );

        $class = new ClassModel(
          $row['class_id'],
          $row['class_public_id'],
          $row['class_name'],
          $row['class_year_number'],
          0,
          $school
        );

        $classArray[] = $class;
      }

      usort($classArray, fn($a, $b) => strcmp($a->classFullName, $b->classFullName));

      return $classArray;
    } catch (PDOException) {
      return [];
    }
  }

  public function getClassByTeacherId(int $teacherId, ?bool $groupedBySchool = false): array
  {
    $query = 'SELECT
              JOURNASTAGE_CLASS.id_class AS class_id,
              JOURNASTAGE_CLASS.public_id AS class_public_id,
              JOURNASTAGE_CLASS.name AS class_name,
              JOURNASTAGE_CLASS.year_number AS class_year_number,
              JOURNASTAGE_SCHOOL.id_school AS school_id,
              JOURNASTAGE_SCHOOL.public_id AS school_public_id,
              JOURNASTAGE_SCHOOL.name AS school_name,
              JOURNASTAGE_SCHOOL.city AS school_city,
              JOURNASTAGE_SCHOOL.department_number AS school_department_number,
              (
                SELECT COUNT(*) 
                FROM JOURNASTAGE_USER 
                WHERE JOURNASTAGE_USER.student_class_id = JOURNASTAGE_CLASS.id_class
              ) AS student_count
              FROM JOURNASTAGE_TEACH, JOURNASTAGE_CLASS, JOURNASTAGE_SCHOOL
              WHERE JOURNASTAGE_TEACH.class_id = JOURNASTAGE_CLASS.id_class
              AND JOURNASTAGE_CLASS.school_id = JOURNASTAGE_SCHOOL.id_school
              AND JOURNASTAGE_TEACH.teacher_id = :teacherId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':teacherId', $teacherId);

      $stmt->execute();
      $rows = $stmt->fetchAll();

      $classesArray = [];

      foreach ($rows as $row) {
        $school = new SchoolModel(
          $row['school_id'],
          $row['school_public_id'],
          $row['school_name'],
          $row['school_city'],
          $row['school_department_number']
        );

        $class = new ClassModel(
          $row['class_id'],
          $row['class_public_id'],
          $row['class_name'],
          $row['class_year_number'],
          $row['student_count'],
          $school
        );

        $classesArray[] = $class;
      }

      if ($groupedBySchool) {
        $groupedArray = [];

        foreach ($classesArray as $classArray) {
          $schoolId = $classArray->school->schoolId;

          if (!isset($groupedArray[$schoolId])) {
            $groupedArray[$schoolId] = [
              'school' => $classArray->school,
              'classes' => []
            ];
          }

          $groupedArray[$schoolId]['classes'][] = $classArray;
        }

        foreach ($groupedArray as &$groupArray) {
          usort($groupArray['classes'], fn($a, $b) => strcmp($a->classFullName, $b->classFullName));
        }
        unset($group);

        return array_values($groupedArray);
      } else {
        usort($classesArray, function ($a, $b) {
          $schoolCmp = strcmp($a->school->schoolName, $b->school->schoolName);
          if ($schoolCmp !== 0) {
            return $schoolCmp;
          }
          return strcmp($a->classFullName, $b->classFullName);
        });

        return $classesArray;
      }
    } catch (PDOException) {
      return [];
    }
  }

  public function getClassByPublicId(string $publicId): ?ClassModel
  {
    $query = 'SELECT
              JOURNASTAGE_CLASS.id_class AS class_id,
              JOURNASTAGE_CLASS.public_id AS class_public_id,
              JOURNASTAGE_CLASS.name AS class_name,
              JOURNASTAGE_CLASS.year_number AS class_year_number,
              JOURNASTAGE_SCHOOL.id_school AS school_id,
              JOURNASTAGE_SCHOOL.public_id AS school_public_id,
              JOURNASTAGE_SCHOOL.name AS school_name,
              JOURNASTAGE_SCHOOL.city AS school_city,
              JOURNASTAGE_SCHOOL.department_number AS school_department_number,
              (
                SELECT COUNT(*) 
                FROM JOURNASTAGE_USER 
                WHERE JOURNASTAGE_USER.student_class_id = JOURNASTAGE_CLASS.id_class
              ) AS student_count
              FROM JOURNASTAGE_CLASS, JOURNASTAGE_SCHOOL
              WHERE JOURNASTAGE_CLASS.school_id = JOURNASTAGE_SCHOOL.id_school
              AND JOURNASTAGE_CLASS.public_id = :publicId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':publicId', $publicId);

      $stmt->execute();
      $row = $stmt->fetch();

      if ($row) {
        return new ClassModel(
          $row['class_id'],
          $row['class_public_id'],
          $row['class_name'],
          $row['class_year_number'],
          $row['student_count'],
          new SchoolModel(
            $row['school_id'],
            $row['school_public_id'],
            $row['school_name'],
            $row['school_city'],
            $row['school_department_number']
          )
        );
      } else {
        return null;
      }
    } catch (PDOException) {
      return null;
    }
  }

  public function verifyTeacherInClass(int $teacherId, int $classId): bool
  {
    $query = 'SELECT *
              FROM JOURNASTAGE_TEACH
              WHERE teacher_id = :teacherId
              AND class_id = :classId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':teacherId', $teacherId);
      $stmt->bindParam(':classId', $classId);

      $stmt->execute();
      $row = $stmt->fetchAll();

      if (count($row) > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException) {
      return false;
    }
  }

  public function verifyTeacherTeachingStudent(int $teacherId, int $studentId): bool
  {
    $query = 'SELECT id_user
              FROM JOURNASTAGE_TEACH, JOURNASTAGE_USER
              WHERE JOURNASTAGE_TEACH.class_id = JOURNASTAGE_USER.student_class_id
              AND JOURNASTAGE_TEACH.teacher_id = :teacherId
              AND JOURNASTAGE_USER.id_user = :studentId';
    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':teacherId', $teacherId);
      $stmt->bindParam(':studentId', $studentId);

      $stmt->execute();
      $row = $stmt->fetchAll();

      if (count($row) > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException) {
      return false;
    }
  }

  public function getAllStudentsByClassId(int $classId): array
  {
    $query = 'SELECT
              JOURNASTAGE_USER.id_user AS student_id,
              JOURNASTAGE_USER.public_id AS student_public_id,
              JOURNASTAGE_USER.last_name AS student_last_name,
              JOURNASTAGE_USER.first_name AS student_first_name,
              JOURNASTAGE_USER.birth_date AS student_birth_date,
              (
                SELECT COUNT(*) 
                FROM JOURNASTAGE_REPORT 
                WHERE JOURNASTAGE_REPORT.student_id = JOURNASTAGE_USER.id_user
              ) AS report_count,
              JOURNASTAGE_USER.student_class_id AS student_class_id
              FROM JOURNASTAGE_USER
              WHERE JOURNASTAGE_USER.student_class_id = :classId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':classId', $classId);

      $stmt->execute();
      $rows = $stmt->fetchAll();

      return $rows;
    } catch (PDOException) {
      return [];
    }
  }

  public function getStudentByPublicId(string $publicId): ?array
  {
    $query = 'SELECT
              JOURNASTAGE_USER.id_user AS student_id,
              JOURNASTAGE_USER.public_id AS student_public_id,
              JOURNASTAGE_USER.last_name AS student_last_name,
              JOURNASTAGE_USER.first_name AS student_first_name,
              JOURNASTAGE_USER.birth_date AS student_birth_date,
              (
                SELECT COUNT(*) 
                FROM JOURNASTAGE_REPORT 
                WHERE JOURNASTAGE_REPORT.student_id = JOURNASTAGE_USER.id_user
              ) AS report_count,
              JOURNASTAGE_USER.student_class_id AS student_class_id
              FROM JOURNASTAGE_USER
              WHERE JOURNASTAGE_USER.public_id = :publicId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':publicId', $publicId);

      $stmt->execute();
      $row = $stmt->fetch();

      if ($row) {
        return $row;
      } else {
        return null;
      }
    } catch (PDOException) {
      return null;
    }
  }

  public function removeClassToStudent(int $studentId): bool
  {
    $query = 'UPDATE JOURNASTAGE_USER SET student_class_id = NULL WHERE id_user = :studentId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':studentId', $studentId);

      return $stmt->execute();
    } catch (PDOException) {
      return false;
    }
  }

  public function removeClassesToTeacher(int $teacherId): bool
  {
    $query = 'DELETE FROM JOURNASTAGE_TEACH WHERE teacher_id = :teacherId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':teacherId', $teacherId);

      return $stmt->execute();
    } catch (PDOException) {
      return false;
    }
  }

  public function addClassToStudent(int $studentId, int $classId): bool
  {
    $query = 'UPDATE JOURNASTAGE_USER SET student_class_id = :classId WHERE id_user = :studentId';

    try {
      $stmt = $this->db->prepare($query);

      $stmt->bindParam(':studentId', $studentId);
      $stmt->bindParam(':classId', $classId);

      return $stmt->execute();
    } catch (PDOException) {
      return false;
    }
  }

  public function addClassesToTeacher(int $teacherId, array $classIds): bool
  {
    $query = 'INSERT INTO JOURNASTAGE_TEACH (teacher_id, class_id) VALUES (:teacherId, :classId)';

    try {
      $stmt = $this->db->prepare($query);

      foreach ($classIds as $classId) {
        $stmt->bindParam(':teacherId', $teacherId);
        $stmt->bindParam(':classId', $classId);

        if (!$stmt->execute()) {
          return false;
        }
      }

      return true;
    } catch (PDOException) {
      return false;
    }
  }
}
