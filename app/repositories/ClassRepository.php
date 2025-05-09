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
}
