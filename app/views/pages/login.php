<div class="main-container">
  <main class="main-center">
    <div class="container login center">
      <h1 class="logo">journaStage</h1>
      <h2 class="text-center">Connectez-vous pour continuer</h2>
      <?php if ($error['global']): ?>
        <div class="error-container">
          <p class="error-text"><i class="fa-solid fa-circle-xmark"></i><?= $error['global'] ?></p>
        </div>
      <?php endif; ?>
      <form action="./se-connecter" method="POST">
        <div class="input-container">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" required />
          <p class="error-text">
            <?= $error['email'] ? $error['email'] : '' ?>
          </p>
        </div>
        <div class="input-container">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Mot de passe" required />
          <p class="error-text">
            <?= $error['password'] ? $error['password'] : '' ?>
          </p>
          <div class="link">
            <a href="./mot-de-passe-oublie">Mot de passe oublié ?</a>
          </div>
        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary" disabled>Se connecter</button>
        </div>
      </form>
      <div class="create-account text-center">
        <a href="#" class="link">Demander un compte</a>
      </div>
    </div>
  </main>
</div>