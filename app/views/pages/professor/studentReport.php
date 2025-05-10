<?php

$studentPublicId = htmlspecialchars($student['student_public_id']);
$studentLastName = htmlspecialchars($student['student_last_name']);
$studentFirstName = htmlspecialchars($student['student_first_name']);
$studentBirthDate = new DateTime(htmlspecialchars($student['student_birth_date']));
$today = new DateTime();
$studentAge = $today->diff($studentBirthDate)->y;

if ($studentAge < 0) {
  $studentAge = 0;
}

$reportTitle = htmlspecialchars($report->getTitle());
$reportContent = htmlspecialchars($report->getContent());
$reportContent = nl2br($reportContent);
$reportDate = htmlspecialchars($report->getDate());
$reportDateReformat = new DateTime($reportDate);
$reportDateReformat = $reportDateReformat->format('d-m-Y');

?>

<div class="title-container">
  <section>
    <div class="title">
      <h1><span class="uppercase"><?= $studentLastName ?></span> <?= $studentFirstName ?></h1>
      <p><?= $studentAge ?> ans</p>
    </div>
    <p class="description">Compte rendu du <?= $reportDateReformat ?></p>
  </section>
</div>
<div class="main-container">
  <main class="main-center">
    <div class="container login large center">
      <div class="head-with-icon">
        <div>
          <a href="./etudiant?id=<?= $studentPublicId ?>" class="button-rounded"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <h2 class="text-center">Compte rendu</h2>
        <div></div>
      </div>
      <form action="#" method="POST" class="border-top">
        <div class="input-container">
          <label for="title">Titre du compte rendu</label>
          <input type="text" id="title" name="title" value="<?= $reportTitle ?>" disabled />
        </div>
        <div class="input-container">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" value="<?= $reportDate ?>" disabled />
        </div>
        <div class="input-container">
          <label for="content">Contenu</label>
          <textarea id="content" name="content" rows="4" class="large" disabled><?= $reportContent ?></textarea>
        </div>
      </form>
    </div>
  </main>
</div>