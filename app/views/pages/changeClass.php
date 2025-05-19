<?php
$userStatus = $user->getStatus();
?>

<div class="main-container">
  <main class="main-center">
    <div class="container login center">
      <?php if ($userStatus === 0) : ?>
        <h2 class="text-center">Changez votre classe</h2>
      <?php elseif ($userStatus === 1) : ?>
        <h2 class="text-center">Changez vos classes</h2>
      <?php endif; ?>
      <form action="./modifier-classe" method="POST">
        <?php if ($userStatus === 0) : ?>
          <fieldset class="student-choice">
            <legend>Choisissez votre classe</legend>
            <?php foreach ($classList as $class): ?>
              <?php
              $classId = $class['class_id'];
              $isChecked = in_array($classId, $classesUser) ? 'checked' : '';

              $schoolFullName = $class['school_name'] . ' (' . $class['school_city'] . ', ' . $class['school_department_number'] . ')';
              $classFullName = $class['class_name'];

              if ($class['class_year_number'] === 1) {
                $classFullName .= ' - 1<sup>re</sup> année';
              } elseif ($class['class_year_number'] === 2) {
                $classFullName .= ' - 2<sup>de</sup> année';
              } elseif ($class['class_year_number'] > 2) {
                $classFullName .= ' - ' . $class['class_year_number'] . '<sup>e</sup> année';
              }
              ?>
              <div class="checkbox-container">
                <input type="radio" id="radio<?= $classId ?>" name="selected_class_id" value="<?= $classId ?>" <?= $isChecked ?> />
                <label for="radio<?= $classId ?>"><span><?= htmlspecialchars($schoolFullName) ?></span><br /><?= $classFullName ?></label>
              </div>
            <?php endforeach; ?>
          </fieldset>
        <?php elseif ($userStatus === 1) : ?>
          <fieldset class="teacher-choice">
            <legend>Choisissez vos classes</legend>
            <?php foreach ($classList as $class): ?>
              <?php
              $classId = $class['class_id'];
              $isChecked = in_array($classId, $classesUser) ? 'checked' : '';

              $schoolFullName = $class['school_name'] . ' (' . $class['school_city'] . ', ' . $class['school_department_number'] . ')';
              $classFullName = $class['class_name'];

              if ($class['class_year_number'] === 1) {
                $classFullName .= ' - 1<sup>re</sup> année';
              } elseif ($class['class_year_number'] === 2) {
                $classFullName .= ' - 2<sup>de</sup> année';
              } elseif ($class['class_year_number'] > 2) {
                $classFullName .= ' - ' . $class['class_year_number'] . '<sup>e</sup> année';
              }
              ?>
              <div class="checkbox-container">
                <input type="checkbox" id="checkbox<?= $classId ?>" name="selected_class_ids[]" value="<?= $classId ?>" <?= $isChecked ?> />
                <label for="checkbox<?= $classId ?>"><span><?= htmlspecialchars($schoolFullName) ?></span><br /><?= $classFullName ?></label>
              </div>
            <?php endforeach; ?>
          </fieldset>
        <?php endif; ?>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary">
            <i class="fa-solid fa-floppy-disk"></i>Enregistrer
          </button>
        </div>
      </form>
    </div>
  </main>
</div>