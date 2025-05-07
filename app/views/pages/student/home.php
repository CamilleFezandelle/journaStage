<?php
$firstName = htmlspecialchars($user->getFirstName());
?>

<div class="main-container">
  <main class="grid-home">
    <div class="image">
      <img src="./public/img/e-home.svg" alt="Accueil" />
    </div>
    <div class="content">
      <div class="text">
        <h1 class="text-overflow">Bienvenue,<br /><span><?= $firstName ?></span></h1>
        <p>Ton stage, ton suivi, au même endroit.</p>
      </div>
      <a href="./e-newcr.html" class="button-primary"><i class="fa-solid fa-plus"></i>Créer un nouveau compte rendu</a>
      <a href="./index.html" class="button-secondary"><i class="fa-solid fa-eye"></i>Voir mes comptes rendus</a>
    </div>
  </main>
</div>