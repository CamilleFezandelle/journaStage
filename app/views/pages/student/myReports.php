<?php if ($successDelete) : ?>
  <div class="temp-window warning">
    <p><i class="far fa-trash-alt"></i>Le compte rendu a bien été supprimé.</p>
  </div>
<?php endif ?>

<div class="title-container">
  <h1>Mes comptes rendus</h1>
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
          <form action="./mes-comptes-rendus" method="GET" class="hidden">
            <fieldset class="sort">
              <legend>Trier par :</legend>
              <div class="checkbox-container">
                <input type="radio" id="sortby1" name="sortby" checked />
                <label for="sortby1">Date (Plus récent)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="sortby2" name="sortby" />
                <label for="sortby2">Date (Plus ancien)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="sortby3" name="sortby" />
                <label for="sortby3">Titre (A - Z)</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="sortby4" name="sortby" />
                <label for="sortby4">Titre (Z - A)</label>
              </div>
            </fieldset>
            <fieldset class="years">
              <legend>Années :</legend>
              <div class="checkbox-container">
                <input type="radio" id="year1" name="year" checked />
                <label for="year1">Toutes</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="year2" name="year" />
                <label for="year2">2025</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="year3" name="year" />
                <label for="year3">2024</label>
              </div>
              <div class="checkbox-container">
                <input type="radio" id="year4" name="year" />
                <label for="year4">2023</label>
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
                <a href="./mes-comptes-rendus?id=<?= $report->getPublicId() ?>" class="button-primary"><i class="fa-solid fa-eye"></i>Consulter</a>
              </div>
            </fieldset>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>