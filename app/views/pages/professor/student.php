<?php

$studentPublicId = htmlspecialchars($student['student_public_id']);
$studentLastName = htmlspecialchars($student['student_last_name']);
$studentFirstName = htmlspecialchars($student['student_first_name']);
$studentBirthDate = new DateTime(htmlspecialchars($student['student_birth_date']));
$today = new DateTime();
$studentAge = $today->diff($studentBirthDate)->y;
$reportCount = htmlspecialchars($student['report_count']);

if ($studentAge < 0) {
  $studentAge = 0;
}

if ($reportCount < 2) {
  $reportCount .= " compte rendu";
} else {
  $reportCount .= " comptes rendus";
}

?>

<div class="title-container">
  <section>
    <div class="title">
      <h1><span class="uppercase"><?= $studentLastName ?></span> <?= $studentFirstName ?></h1>
      <p><?= $studentAge ?> ans</p>
    </div>
    <P class="description"><?= $reportCount ?></P>
  </section>
</div>
<div class="main-container">
  <main class="main-grid">
    <div class="block-container filter">
      <div class="card secondary-card accordion">
        <button type="button" class="title accordion-header">
          <h3><i class="fa-solid fa-bars-staggered"></i>Filtrer</h3>
          <i class="chevron fa-solid fa-chevron-down"></i>
        </button>
        <div class="content accordion-content">
          <form action="#" method="POST" class="hidden">
            <fieldset class="sort">
              <legend>Trier par :</legend>
              <div class="checkbox-container">
                <input type="radio" id="radio3" name="radio" checked />
                <label for="radio3">Date (Plus récent)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="radio4" name="radio" />
                <label for="radio4">Date (Plus ancien)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="radio1" name="radio" />
                <label for="radio1">Titre (A - Z)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="radio2" name="radio" />
                <label for="radio2">Titre (Z - A)</label>
              </div>
            </fieldset>
            <fieldset class="years">
              <legend>Années :</legend>
              <div class="checkbox-container">
                <input type="radio" id="check1" name="check" checked />
                <label for="check1">Toutes</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="check2" name="check" />
                <label for="check2">2025</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="check3" name="check" />
                <label for="check3">2024</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="check4" name="check" />
                <label for="check4">2023</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="check5" name="check" />
                <label for="check5">2022</label>
              </div>
            </fieldset>
            <button type="submit" class="submit-btn button-secondary">
              <i class="fa-solid fa-circle-check"></i>Appliquer
            </button>
          </form>
          <h3>En construction</h3>
          <p>Cette fonctionnalité n'est pas encore disponible.</p>
        </div>
      </div>
    </div>
    <div class="block-container text-overflow">
      <?php if (empty($reports)): ?>
        <h2 class="center">Aucun compte rendu trouvé.</h2>
      <?php endif; ?>
      <?php foreach ($reports as $year => $reportsList): ?>
        <div class="year-container">
          <div class="year-separator">
            <span><?= $year ?></span>
          </div>
          <?php foreach ($reportsList as $report): ?>
            <fieldset class="element">
              <legend>Compte rendu du <?= (new DateTime($report->getDate()))->format('d-m-Y') ?> </legend>
              <div class="content">
                <div class="text-overflow text-with-dots">
                  <p class="text-overflow"><?= htmlspecialchars($report->getTitle()) ?></p>
                  <span></span>
                </div>
                <a href="./etudiant?id=<?= $student['student_public_id'] ?>&compte_rendu_id=<?= $report->getPublicId() ?>" class="button-primary"><i class="fa-solid fa-eye"></i>Consulter</a>
              </div>
            </fieldset>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>