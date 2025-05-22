<?php
$className = $class->className;
$classYear = $class->classFullYear;
$classLocation = $class->school->schoolFullLocation;
$classStudentCount = $class->classStudentCount;

if ($classStudentCount < 2) {
  $classStudentCount .= " étudiant";
} else {
  $classStudentCount .= " étudiants";
}

?>

<div class="title-container">
  <section>
    <div class="title text-overflow">
      <h1 class="text-overflow"><?= htmlspecialchars($className) . ($classYear !== '<span class="invisible">a</span>' ? ' - ' . $classYear : '') ?></h1>
      <p><?= htmlspecialchars($classLocation) ?></p>
    </div>
    <p class="description"><?= htmlspecialchars($classStudentCount) ?></p>
  </section>
</div>
<div class="main-container">
  <main class="main-grid unique">
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
                <input type="radio" id="radio1" name="radio" />
                <label for="radio1">Nom (A - Z)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="radio2" name="radio" />
                <label for="radio2">Nom (Z - A)</label>
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
      <?php if (empty($students)): ?>
        <h2 class="center">Aucun étudiant trouvé dans cette classe.</h2>
      <?php else: ?>
        <?php foreach ($students as $student): ?>
          <fieldset class="element">
            <legend>
              <?php if ($student['report_count'] < 2): ?>
                <?= htmlspecialchars($student['report_count']) ?> compte rendu
              <?php else: ?>
                <?= htmlspecialchars($student['report_count']) ?> comptes rendus
              <?php endif; ?>
            </legend>
            <div class="content">
              <p class="text-overflow dotted">
                <i class="fa-solid fa-user-graduate"></i>
                <span><span class="uppercase"><?= htmlspecialchars($student['student_last_name']) ?></span> <?= htmlspecialchars($student['student_first_name']) ?></span>
              </p>
              <a href="./etudiant?id=<?= $student['student_public_id'] ?>" class="button-primary"><i class="fa-solid fa-eye"></i>Voir l'étudiant</a>
            </div>
          </fieldset>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</div>