<?php

$firstNameContent = empty($errors['firstName']) ? ($post['firstName'] ?? '') : '';
$lastNameContent = empty($errors['lastName']) ? ($post['lastName'] ?? '') : '';
$emailContent = empty($errors['email']) ? ($post['email'] ?? '') : '';
$birthDateContent = empty($errors['birthDate']) ? ($post['birthDate'] ?? '') : '';

$errorFirstName = $errors['firstName'] ?? '';
$errorLastName = $errors['lastName'] ?? '';
$errorEmail = $errors['email'] ?? '';
$errorBirthDate = $errors['birthDate'] ?? '';

if (!empty($errorFirstName) || !empty($errorLastName) || !empty($errorEmail) || !empty($errorBirthDate)) {
  $errorGlobal = 'Tous les champs doivent être correctement remplis.';
} else {
  $errorGlobal = '';
}

?>


<?php if ($cookie === 'success') : ?>
  <div class="temp-window success">
    <p><i class="fa-solid fa-circle-check"></i>Votre demande a été envoyée avec succès.</p>
  </div>
<?php endif; ?>

<div class="main-container">
  <main class="main-center">
    <div class="container account-request center">
      <h1 class="logo">journaStage</h1>
      <h2 class="text-center">Demandez votre compte ici</h2>
      <form action="./demander-un-compte" method="POST">
        <?php if ($errorGlobal): ?>
          <div class="error-container">
            <p class="error-text"><i class="fa-solid fa-circle-xmark"></i><?= htmlspecialchars($errorGlobal) ?></p>
          </div>
        <?php endif; ?>
        <div class="input-container">
          <label for="lastName">Nom</label>
          <input type="text" id="lastName" name="lastName" placeholder="Nom" value="<?= htmlspecialchars($lastNameContent) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorLastName) ?></p>
        </div>
        <div class="input-container">
          <label for="firstName">Prénom</label>
          <input type="text" id="firstName" name="firstName" placeholder="Prénom" value="<?= htmlspecialchars($firstNameContent) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorFirstName) ?></p>
        </div>
        <div class="input-container">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($emailContent) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorEmail) ?></p>
        </div>
        <div class="input-container">
          <label for="birthDate">Date de naissance</label>
          <input type="date" id="birthDate" name="birthDate" value="<?= htmlspecialchars($birthDateContent) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorBirthDate) ?></p>
        </div>
        <div class="choice-container">
          <label>Vous êtes :</label>
          <div class="radio-group">
            <input type="radio" id="status-student" name="status" value="student" class="hidden" />
            <label for="status-student" class="button-secondary button-choice button-student">
              <i class="fa-solid fa-user-graduate"></i> Étudiant
            </label>

            <input type="radio" id="status-teacher" name="status" value="teacher" class="hidden" />
            <label for="status-teacher" class="button-secondary button-choice button-teacher">
              <i class="fa-solid fa-user-tie"></i> Professeur
            </label>
          </div>
        </div>
        <fieldset class="hidden student-choice">
          <legend>Choisissez votre classe</legend>
          <?php foreach ($classList as $class): ?>
            <?php
            $classId = $class['class_id'];

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
              <input type="radio" id="radio<?= $classId ?>" name="selected_class_id" value="<?= $classId ?>" />
              <label for="radio<?= $classId ?>"><span><?= htmlspecialchars($schoolFullName) ?></span><br /><?= $classFullName ?></label>
            </div>
          <?php endforeach; ?>
        </fieldset>
        <fieldset class="hidden teacher-choice">
          <legend>Choisissez vos classes</legend>
          <?php foreach ($classList as $class): ?>
            <?php
            $classId = $class['class_id'];

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
              <input type="checkbox" id="checkbox<?= $classId ?>" name="selected_class_ids[]" value="<?= $classId ?>" />
              <label for="checkbox<?= $classId ?>"><span><?= htmlspecialchars($schoolFullName) ?></span><br /><?= $classFullName ?></label>
            </div>
          <?php endforeach; ?>
        </fieldset>
        <p class="hidden notice student-notice">
          * Note : Vous pourrez modifier votre classe à tout moment dans vos informations personnelles.
        </p>
        <p class="hidden notice teacher-notice">
          * Note : Vous pourrez modifier les classes que vous enseignez à tout moment dans vos informations
          personnelles.
        </p>
        <div class="btn-container">
          <button type="submit" class=" button-submit button-primary" disabled>Envoyer ma demande</button>
        </div>
      </form>
      <div class="create-account text-center">
        <a href="./se-connecter" class="link">Revenir à la page de connexion</a>
      </div>
    </div>
  </main>
</div>