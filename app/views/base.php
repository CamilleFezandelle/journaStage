<?php
require_once __DIR__ . '/../config.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styles.css">
  <script src="https://kit.fontawesome.com/b0d8e23d7e.js" crossorigin="anonymous"></script>
  <script src="<?= BASE_URL ?>/public/js/burgerMenu.js" defer></script>
  <?php if (!empty($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
      <script src="<?= BASE_URL ?>/public/js/<?= htmlspecialchars($script) ?>" defer></script>
    <?php endforeach; ?>
  <?php endif; ?>
  <title><?= htmlspecialchars($title) ?></title>
</head>

<body>

  <?php include_once __DIR__ . '/layout/header.php'; ?>

  <?= $content ?>

  <?php include_once __DIR__ . '/layout/footer.php'; ?>

</body>

</html>