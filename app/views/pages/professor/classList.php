<div class="title-container">
  <section>
    <h1>Mes classes</h1>
  </section>
</div>
<div class="main-container">
  <main class="main-flex-column">
    <?php foreach ($classes as $class): ?>
      <div class="card card-grid text-overflow">
        <div class="title">
          <i class="fa-solid fa-school"></i>
          <div>
            <h3><?= htmlspecialchars($class['school']->schoolName) ?></h3>
            <p><?= htmlspecialchars($class['school']->schoolFullLocation) ?></p>
          </div>
        </div>
        <?php foreach ($class['classes'] as $classItem): ?>
          <div class="element text-overflow">
            <p class="text-overflow"><?= htmlspecialchars($classItem->className) ?></p>
            <p class="text-overflow"><?= $classItem->classFullYear ?></p>
            <p class="text-overflow">Nombre d'étudiants : <?= htmlspecialchars($classItem->classStudentCount) ?></p>
            <div class="btn-container">
              <a href="./classe?id=<?= htmlspecialchars($classItem->classPublicId) ?>" class="button-primary"><i class="fa-solid fa-eye"></i>Consulter</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </main>
</div>