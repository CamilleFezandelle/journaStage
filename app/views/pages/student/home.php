<?php
$firstName = $user->getFirstName();
?>

<?php if ($successCreate) : ?>
  <div class="temp-window success">
    <p><i class="fa-solid fa-circle-check"></i>Le compte rendu a été créé avec succès.</p>
  </div>
<?php endif ?>

<?php if (!$class) : ?>
  <div class="title-container warning">
    <section>
      <p><i class="fa-solid fa-triangle-exclamation"></i> Vous n'êtes actuellement inscrit dans aucune classe. <i class="fa-solid fa-triangle-exclamation"></i></p>
      <p>Pour en rejoindre une, <a href="./informations-personnelles/modifier-classe">cliquez ici</a>.</p>
    </section>
  </div>
<?php endif; ?>

<div class="main-container">
  <main class="grid-home">
    <div class="image">
      <img src="./public/img/e-home.svg" alt="Accueil" />
    </div>
    <div class="content">
      <div class="text">
        <h1 class="text-overflow">Bienvenue,<br /><span><?= htmlspecialchars($firstName) ?></span></h1>
        <p>Ton stage, ton suivi, au même endroit.</p>
      </div>
      <a href="./nouveau-compte-rendu" class="button-primary"><i class="fa-solid fa-plus"></i>Créer un nouveau compte rendu</a>
      <a href="./mes-comptes-rendus" class="button-secondary"><i class="fa-solid fa-eye"></i>Voir mes comptes rendus</a>
    </div>
  </main>
</div>