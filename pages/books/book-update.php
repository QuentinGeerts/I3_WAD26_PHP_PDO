<?php

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Pré-remplir le select des auteurs
$query = "SELECT id, nom, prenom FROM auteur ORDER BY nom";
$authors = $pdo->query($query)->fetchAll();

$statement = $pdo->prepare("SELECT id, titre, annee, prix, isbn, auteur_id FROM livre WHERE id = ?");
$statement->execute([$id]);
$livre = $statement->fetch();

if (!$livre) {
  header("Location: index.php?page=books");
}

$values = [
  'book-title' => $livre['titre'],
  'book-published-year' => $livre['annee'],
  'book-author' => $livre['auteur_id'],
  'book-price' => $livre['prix'],
  'book-isbn' => $livre['isbn'],
];

$errors = [];

// Gérer le formulaire

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Mapping de données
  foreach (array_keys($_POST) as $field) {
    $values[$field] = trim($_POST[$field]) ?? '';
  }

  // Validation des champs

  // 1. Titre
  if ($values['book-title'] === '') {
    $errors["book-title"] = "Le titre est obligatoire";
  } else if (strlen($values['book-title']) > 200) {
    $errors["book-title"] = "Le titre ne peut pas dépasser 200 caractères.";
  }

  // 2. Année
  if ($values['book-published-year'] !== '') {
    $year = filter_var($values['book-published-year'], FILTER_VALIDATE_INT);
    if (!$year || $year < 1 || $year > date('Y') + 1) {
      $errors["book-published-year"] = "L'année est invalide. (comprise entre 1 et " . date('Y') + 1 . ").";
    }
  }

  // 3. Prix

  if ($values["book-price"] !== '') {
    $price = filter_var($values["book-price"], FILTER_VALIDATE_FLOAT);
    if (!$price || $price < 0) {
      $errors["book-price"] = "Le prix doit être positif.";
    }
  }

  // 4. Code EAN13
  if ($values['book-isbn'] !== '' && !preg_match('/\d{13}/', $values['book-isbn'])) {
    $errors["book-isbn"] = "L'isbn doit comportement exactement 13 chiffres.";
  }

  // 5. Id auteur
  $authorsId = array_map('intval', array_column($authors, 'id'));
  if ($values['book-author'] === '') {
    $errors["book-author"] = "L'auteur est obligatoire.";
  } else if (!in_array($values['book-author'], $authorsId)) {
    $errors['book-author'] = "L'auteur n'existe pas.";
  }

  // Modification d'un livre dans la DB si pas d'erreur

  if (!$errors) {

    try {
      $sql = "
      UPDATE livre
      SET
        titre = ?,
        annee = ?,
        prix = ?,
        isbn = ?,
        auteur_id = ?
      WHERE id = ?
      ";

      $statement = $pdo->prepare($sql);
      $statement->execute([
        $values['book-title'],
        $values['book-published-year'] !== '' ? (int) $values['book-published-year'] : null,
        $values['book-price'] !== '' ? (float) $values['book-price'] : null,
        $values['book-isbn'] !== '' ? $values['book-isbn'] : null,
        (int) $values['book-author'],
        $id
      ]);

      header('Location: index.php?page=book-details&id=' . $id);
    } catch (PDOException $e) {
      $errors["book-isbn"] = "L'ISBN est déjà utilisé.";
    }
  }
}
?>

<style>
  form * {
    display: block;
    margin: 10px 0;
  }
</style>

<h1>Modification d'un livre</h1>

<form method="post">

  <div>
    <label for="book-title">Titre: *</label>
    <input type="text" name="book-title" id="book-title" required value="<?= $values['book-title'] ?>">
    <?php if (isset($errors['book-title'])) : ?>
      <span class="error"><?= $errors['book-title'] ?></span>
    <?php endif ?>
  </div>

  <div>
    <label for="book-published-year">Année de parution:</label>
    <input type="number" name="book-published-year" id="book-published-year"  value="<?= $values['book-published-year'] ?>">
    <?php if (isset($errors['book-published-year'])) : ?>
      <span class="error"><?= $errors['book-published-year'] ?></span>
    <?php endif ?>
  </div>

  <div>
    <label for="book-author">Auteur: *</label>
    <select name="book-author" id="book-author" required value="<?= $values['book-author'] ?>">
      <option value=""> --- Sélectionner un auteur --- </option>
      <?php foreach ($authors as $a) : ?>
        <option 
          value="<?= htmlspecialchars($a["id"]) ?>"
          <?= $values["book-author"] === $a["id"] ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($a["nom"] . ' ' . $a["prenom"]) ?>
        </option>
      <?php endforeach ?>
    </select>
    <?php if (isset($errors['book-author'])) : ?>
      <span class="error"><?= $errors['book-author'] ?></span>
    <?php endif ?>
  </div>

  <div>
    <label for="book-price">Prix:</label>
    <input type="number" min="0" step="0.001" name="book-price" id="book-price" value="<?= $values['book-price'] ?>">
    <?php if (isset($errors['book-price'])) : ?>
      <span class="error"><?= $errors['book-price'] ?></span>
    <?php endif ?>
  </div>

  <div>
    <label for="book-isbn">EAN13:</label>
    <input type="text" maxlength="13" name="book-isbn" id="book-isbn" value="<?= $values['book-isbn'] ?>">
    <?php if (isset($errors['book-isbn'])) : ?>
      <span class="error"><?= $errors['book-isbn'] ?></span>
    <?php endif ?>
  </div>

  <button>Modifier le livre</button>

  <p>* champ obligatoire</p>
</form>