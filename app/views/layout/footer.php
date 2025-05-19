<?php

$currentYear = date('Y');

?>

<div class="footer-container">
  <footer>
    <p>© <?= htmlspecialchars($currentYear) ?> journaStage. Tous droits réservés.</p>
    <a href="<?= BASE_URL ?>/contact" class="link">Contact</a>
  </footer>
</div>