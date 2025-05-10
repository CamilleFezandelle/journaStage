<?php

class SchoolModel
{
  public function __construct(
    public int $schoolId,
    public string $schoolPublicId,
    public string $schoolName,
    public string $schoolCity,
    public string $schoolDepartmentNumber,
    public ?string $schoolFullLocation = null,
  ) {
    $this->schoolFullLocation = $this->schoolCity . ' (' . $this->schoolDepartmentNumber . ')';
  }
}

class ClassModel
{
  public function __construct(
    public int $classId,
    public string $classPublicId,
    public string $className,
    public int $classYearNumber,
    public int $classStudentCount,
    public SchoolModel $school,
    public ?string $classFullName = null,
    public ?string $classFullYear = null
  ) {
    if ($this->classYearNumber === 1) {
      $this->classFullName = $this->className . ' - 1re année';
      $this->classFullYear = $this->classYearNumber . '<sup>re</sup> année';
    } elseif ($this->classYearNumber === 2) {
      $this->classFullName = $this->className . ' - 2de année';
      $this->classFullYear = $this->classYearNumber . '<sup>de</sup> année';
    } elseif ($this->classYearNumber > 2) {
      $this->classFullName = $this->className . ' - ' . $this->classYearNumber . 'e année';
      $this->classFullYear = $this->classYearNumber . '<sup>e</sup> année';
    } else {
      $this->classFullName = $this->className;
      $this->classFullYear = '<span class="invisible">a</span>';
    }
  }
}
