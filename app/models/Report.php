<?php

class Report
{
  private int $idReport;
  private string $publicId;
  private string $title;
  private string $date;
  private string $content;
  private int $studentId;

  public function __construct(
    int $idReport,
    string $publicId,
    string $title,
    string $content,
    string $date,
    int $studentId
  ) {
    $this->idReport = $idReport;
    $this->publicId = $publicId;
    $this->title = $title;
    $this->content = $content;
    $this->date = $date;
    $this->studentId = $studentId;
  }
}
