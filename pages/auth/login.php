<?php

$errors = [];

$values = [ 'email' => '' ];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $values['email'] = trim($_POST['email']) ?? '';
  $password = trim($_POST["password"]);

  // Validation
  if ($values['email'] === '') {
    $errors['email'] =  "L'email est obligatoire.";
  }
  else if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Le format de l'email est incorrect.";
  }

  if ($password === '') {
    $errors['password'] = "Le mot de passe est obligatoire.";
  }

  // Vérifier si les identifiants sont corrects
  if (!$errors) {

    $sql = "SELECT id, email, mot_de_passe, role FROM utilisateur WHERE email = ?";

    $statement = $pdo->prepare($sql);
    $statement->execute([$values['email']]);

    $user = $statement->fetch();

    if (!$user || !password_verify($password, $user['mot_de_passe'])) {
      $errors['global'] = "L'email et/ou mot de passe incorrect.";
    }
    else {
      $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $values['email'],
        'role' => $user['role']
      ];

      header("Location: index.php?page=books");
    }

  }

}

?>

<!-- Template -->

<h1>Se connecter</h1>

<form method="post">

    <?php if(isset($errors['global'])) : ?>
      <span class="error"><?= $errors['global'] ?></span>
    <?php endif ?>

  <div>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= $values['email'] ?>">
    <?php if(isset($errors['email'])) : ?>
      <span class="error"><?= $errors['email'] ?></span>
    <?php endif ?>
  </div>


  <div>
    <label for="password">Mot de passe:</label>
    <input type="password" name="password" id="password">
    <?php if(isset($errors['password'])) : ?>
      <span class="error"><?= $errors['password'] ?></span>
    <?php endif ?>
  </div>

  <button>Se connecter</button>

</form>

<p>Pas encore inscrit ? <a href="index.php?page=register">Inscris-toi !</a></p>
