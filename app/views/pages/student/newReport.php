<div class="title-container">
  <h1>Créer un nouveau compte rendu</h1>
</div>
<div class="main-container">
  <main class="main-center">
    <div class="container login large center">
      <h2 class="text-center">Entrez votre contenu ici</h2>
      <form action="./nouveau-compte-rendu" method="POST" class="border-top">
        <div class="input-container">
          <label for="title">Titre du compte rendu</label>
          <input type="text" id="title" name="title" placeholder="Titre du compte rendu" required />
          <p class="error-text"><?= $error['title'] ? $error['title'] : '' ?></p>
        </div>
        <div class="input-container">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" required />
          <p class="error-text"><?= $error['date'] ? $error['date'] : '' ?></p>
        </div>
        <div class="input-container">
          <label for="content">Contenu</label>
          <textarea
            id="content"
            name="content"
            rows="4"
            class="large"
            placeholder="Écrivez le contenu ici..."
            required></textarea>
          <p class="error-text"><?= $error['content'] ? $error['content'] : '' ?></p>

        </div>
        <div class="btn-container">
          <button type="submit" class="submit-btn button-primary" disabled>
            <i class="fa-solid fa-plus"></i>Créer le compte rendu
          </button>
        </div>
      </form>
      <div class="border-top text-center">
        <a href="./" class="link">Revenir à l'accueil</a>
      </div>
    </div>
  </main>
</div>