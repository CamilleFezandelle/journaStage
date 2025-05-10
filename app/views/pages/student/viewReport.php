<?php
$reportTitle = htmlspecialchars($report->getTitle());
$reportDate = htmlspecialchars($report->getDate());
$reportContent = htmlspecialchars($report->getContent());

?>

<?php if ($successUpdate) : ?>
  <div class="temp-window edit">
    <p><i class="fa-solid fa-pen"></i>Le compte rendu a bien été modifié.</p>
  </div>
<?php endif ?>

<div class="confirm-window-container">
  <div class="confirm-window">
    <div class="title">
      <div class="icon">
        <i class="far fa-trash-alt"></i>
      </div>
      <h2>Supprimer le compte rendu</h2>
      <p>Êtes-vous sûr de vouloir supprimer ce compte rendu ?</p>
    </div>
    <div class="buttons">
      <button type="button" class="button button-secondary">
        <i class="fa-solid fa-circle-xmark"></i>Annuler
      </button>
      <a href="./mes-comptes-rendus?id=<?= $report->getPublicId() ?>&action=delete" class="button button-primary warning"><i class="far fa-trash-alt"></i>Supprimer</a>
    </div>
  </div>
</div>
<div class="title-container">
  <section>
    <h1>Mes comptes rendus</h1>
  </section>
</div>
<div class="main-container">
  <main class="main-center">
    <div class="container login large center">
      <div class="head-with-icon">
        <div>
          <a href="./mes-comptes-rendus" class="button-rounded"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <h2 class="text-center">Votre compte rendu</h2>
        <div>
          <div class="dropdown">
            <button type="button" class="dropdown-button button-rounded">
              <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
            <div class="dropdown-menu">
              <a href="./mes-comptes-rendus?id=<?= $report->getPublicId() ?>&action=edit" class="dropdown-item"><i class="fa-solid fa-pen-to-square"></i>Éditer</a>
              <button type="button" href="#" class="open-confirm-window dropdown-item warning">
                <i class="fa-solid fa-trash"></i>Supprimer
              </button>
            </div>
          </div>
        </div>
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
          <textarea id="content" name="content" rows="4" class="large" disabled><?= html_entity_decode($reportContent) ?></textarea>
        </div>
      </form>
    </div>
  </main>
</div>