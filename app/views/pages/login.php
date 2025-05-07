<form action="./se-connecter" method="post">
  <h1>Connexion</h1>
  <br>
  <span class="error"><?= htmlspecialchars($error['global']) ?></span>
  <br>
  <label for="email">Email</label>
  <input type="email" name="email" id="email">
  <br>
  <span class="error"><?= htmlspecialchars($error['email']) ?></span>
  <br>
  <br>
  <label for="password">Mot de passe</label>
  <input type="password" name="password" id="password">
  <br>
  <span class="error"><?= htmlspecialchars($error['password']) ?></span>
  <br>
  <br>
  <input type="submit" value="Se connecter">
  <br>
  <br>
</form>