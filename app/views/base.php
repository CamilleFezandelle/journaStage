<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?></title>
</head>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<body>
  <main>
    <?= $content ?>
  </main>
</body>

<?php include_once __DIR__ . '/layout/footer.php'; ?>

</html>