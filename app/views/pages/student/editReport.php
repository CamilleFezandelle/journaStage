<?php
$reportTitle = htmlspecialchars($report->getTitle());
$reportDate = htmlspecialchars($report->getDate());
$reportContent = htmlspecialchars($report->getContent());

?>

<div class="title-container">
  <h1>Éditer mon compte rendu</h1>
</div>
<div class="main-container">
  <main class="main-center">
    <div class="container login large center">
      <h2 class="text-center">Entrez vos modifications ici</h2>
      <form action="./mes-comptes-rendus?id=<?= $report->getPublicId() ?>&action=edit" method="POST" class="border-top">
        <div class="input-container">
          <label for="title">Titre du compte rendu</label>
          <input
            type="text"
            id="title"
            name="title"
            placeholder="Titre du compte rendu"
            value="<?= $reportTitle ?>"
            required />
          <p class="error-text"></p>
        </div>
        <div class="input-container">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" value="<?= $reportDate ?>" required />
          <p class="error-text"></p>
        </div>
        <div class="input-container">
          <label for="content">Contenu</label>
          <textarea
            id="content"
            name="content"
            rows="4"
            class="large"
            placeholder="Écrivez le contenu ici..."
            required><?= $reportContent ?></textarea>
          <p class="error-text"></p>
        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary">
            <i class="fa-solid fa-floppy-disk"></i>Enregistrer les modifications
          </button>
        </div>
      </form>
      <div class="border-top text-center">
        <a href="#" class="link">Annuler</a>
      </div>
    </div>
  </main>
</div>