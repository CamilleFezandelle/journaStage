<?php

$userStatus = $user->getStatus();
$isAdmin = $user->isAdmin();

$lastName = htmlspecialchars($user->getLastName());
$firstName = htmlspecialchars($user->getFirstName());
$email = htmlspecialchars($user->getEmail());
$birthDate = htmlspecialchars($user->getBirthDate());
$statusName = '';

if ($userStatus === 0) {
  $statusName = 'Étudiant';
} elseif ($userStatus === 1) {
  $statusName = 'Professeur';
} else {
  $statusName = 'Inconnu';
}

if ($isAdmin) {
  $statusName .= ' (Administrateur)';
}

?>

<div class="title-container">
  <h1>Éditer mon compte</h1>
</div>
<div class="main-container">
  <main class="main-flex">
    <div class="fixed-container">
      <div class="fixed card">
        <div class="title">
          <div class="picture">
            <?php if ($userStatus === 0) : ?>
              <img src="./public/img/student-picture.svg" alt="Profile Picture" />
            <?php endif ?>
            <?php if ($userStatus === 1) : ?>
              <img src="./public/img/teacher-picture.png" alt="Profile Picture" />
            <?php endif ?>
          </div>
          <h2>Mon Espace</h2>
        </div>
        <div class="content">
          <a href="#informations" class="button-secondary"><i class="fa-solid fa-user"></i>Mes informations personnelles</a>
          <a href="#password" class="button-secondary"><i class="fa-solid fa-key"></i>Mon mot de passe</a>
          <a href="#school" class="button-secondary"><i class="fa-solid fa-school"></i>Ma classe</a>
        </div>
      </div>
    </div>
    <div class="block-container">
      <div id="informations" class="card informations-container">
        <div class="title">
          <h3><i class="fa-solid fa-user"></i>Mes informations personnelles</h3>
        </div>
        <div class="content">
          <form class="grid-form">
            <div class="input-container">
              <label for="name">Nom</label>
              <input type="text" id="name" name="name" value="<?= $lastName ?>" disabled />
            </div>
            <div class="input-container">
              <label for="first-name">Prénom</label>
              <input type="text" id="first-name" name="first-name" value="<?= $firstName ?>" disabled />
            </div>
            <div class="input-container">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" value="<?= $email ?>" disabled />
            </div>
            <div class="input-container">
              <label for="birth-date">Date de naissance</label>
              <input type="date" id="birth-date" name="birth-date" value="<?= $birthDate ?>" disabled />
            </div>
            <div class="input-container">
              <label for="status">Statut</label>
              <input type="text" id="status" name="status" value="<?= $statusName ?>" disabled />
            </div>
          </form>
        </div>
      </div>
      <div id="password" class="card password-container">
        <div class="title">
          <h3><i class="fa-solid fa-key"></i>Mon mot de passe</h3>
        </div>
        <div class="content">
          <form class="grid-form">
            <div class="input-container">
              <label for="password">Mot de passe</label>
              <input type="password" id="password" name="password" value="password" disabled />
            </div>
            <div class="btn-container-grid">
              <a href="./informations-personnelles/modifier-mot-de-passe" class="button-primary"><i class="fa-solid fa-pen-to-square"></i>Modifiez votre mot de passe</a>
            </div>
          </form>
        </div>
      </div>
      <?php if ($userStatus === 0) : ?>
        <div id="school" class="card school-container">
          <div class="title">
            <h3><i class="fa-solid fa-school"></i>Ma classe</h3>
          </div>
          <div class="content">
            <form class="grid-form-class-student">
              <?php foreach ($classes as $index => $class) : ?>
                <div class="input-container">
                  <label for="school">Établissement</label>
                  <input type="text" id="school" name="school" value="<?= htmlspecialchars($class->school->schoolName) ?>" disabled />
                </div>
                <div class="input-container">
                  <label for="class">Classe</label>
                  <input type="text" id="class" name="class" value="<?= htmlspecialchars($class->classFullName) ?>" disabled />
                </div>
              <?php endforeach ?>
            </form>
            <div class="btn-container-grid">
              <a href="./informations-personnelles/modifier-classe" class="button-primary"><i class="fa-solid fa-pen-to-square"></i>Changez votre classe</a>
            </div>
          </div>
        </div>
      <?php endif ?>
      <?php if ($userStatus === 1) : ?>
        <div id="school" class="card school-container">
          <div class="title">
            <h3><i class="fa-solid fa-school"></i>Mes classes</h3>
          </div>
          <div class="content">
            <form class="grid-form-class-teacher">
              <?php foreach ($classes as $index => $class) : ?>
                <p><?= $index + 1 ?>.</p>
                <div class="input-container">
                  <label for="school">Établissement</label>
                  <input type="text" id="school" name="school" value="<?= htmlspecialchars($class->school->schoolName) ?>" disabled />
                </div>
                <div class="input-container">
                  <label for="class">Classe</label>
                  <input type="text" id="class" name="class" value="<?= htmlspecialchars($class->classFullName) ?>" disabled />
                </div>
              <?php endforeach ?>
            </form>
            <div class="btn-container-grid">
              <a href="./informations-personnelles/modifier-classe" class="button-primary"><i class="fa-solid fa-pen-to-square"></i>Changez vos classes</a>
            </div>
          </div>
        </div>
      <?php endif ?>
    </div>
  </main>
</div>