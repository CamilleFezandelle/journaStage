<div class="main-container">
  <main class="grid-home">
    <div class="image">
      <img src="./public/img/p-home.svg" alt="Accueil" />
    </div>
    <div class="content">
      <div class="text">
        <h1 class="text-overflow">Bienvenue,<br /><span><?= htmlspecialchars($user->getFirstName()) ?></span></h1>
        <p>Suivre, guider, accompagner vos étudiants au quotidien.</p>
      </div>
      <a href="./mes-classes" class="button-primary"><i class="fa-solid fa-school"></i>Accéder à mes classes</a>
    </div>
  </main>
</div>