<?php

$titleValue = empty($errors['title']) ? ($post['title'] ?? '') : '';
$dateValue = empty($errors['date']) ? ($post['date'] ?? '') : '';
$contentValue = empty($errors['content']) ? ($post['content'] ?? '') : '';

$errorTitle = $errors['title'] ?? '';
$errorDate = $errors['date'] ?? '';
$errorContent = $errors['content'] ?? '';

if (!empty($errorTitle) || !empty($errorDate) || !empty($errorContent)) {
  $errorGlobal = 'Tous les champs doivent être correctement remplis.';
} else {
  $errorGlobal = '';
}

?>

<div class="title-container">
  <section>
    <h1>Créer un nouveau compte rendu</h1>
  </section>
</div>
<div class="main-container">
  <main class="main-center">
    <div class="container login large center">
      <div class="head-with-icon">
        <div>
          <a href="./" class="button-rounded"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <h2 class="text-center">Entrez votre contenu ici</h2>
        <div></div>
      </div>
      <form action="./nouveau-compte-rendu" method="POST" class="border-top">
        <?php if ($errorGlobal): ?>
          <div class="error-container">
            <p class="error-text"><i class="fa-solid fa-circle-xmark"></i><?= htmlspecialchars($errorGlobal) ?></p>
          </div>
        <?php endif; ?>
        <div class="input-container">
          <label for="title">Titre du compte rendu</label>
          <input type="text" id="title" name="title" placeholder="Titre du compte rendu" value="<?= htmlspecialchars($titleValue) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorTitle) ?></p>
        </div>
        <div class="input-container">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" value="<?= htmlspecialchars($dateValue) ?>" required />
          <p class="error-text"><?= htmlspecialchars($errorDate) ?></p>
        </div>
        <div class="input-container">
          <label for="content">Contenu</label>
          <textarea
            id="content"
            name="content"
            rows="4"
            class="large"
            placeholder="Écrivez le contenu ici..." required><?= htmlspecialchars($contentValue) ?></textarea>
          <p class="error-text"><?= htmlspecialchars($errorContent) ?></p>

        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary" disabled>
            <i class="fa-solid fa-plus"></i>Créer le compte rendu
          </button>
        </div>
      </form>
    </div>
  </main>
</div>