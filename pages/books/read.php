<?php

// Récupérer la liste des livres triés par titre
$sql = "SELECT id, titre 
        FROM livre
        ORDER BY titre";

$livres = $pdo->query($sql)->fetchAll();

?>

<h1>Liste des livres</h1>

<p><?= count($livres) ?> livre(s)</p>

<div class="cards">

  <?php foreach ($livres as $livre) : ?>

    <article class="card">
      <h2><?= $livre["titre"] ?></h2>

      <div class="actions">
        <a href="index.php?page=book-details&amp;id=<?= $livre['id'] ?>" class="btn">Détails</a>

        <?php if ($_SESSION['user']['role'] === 'admin') : ?>
          <a href="index.php?page=book-edit&amp;id=<?= $livre['id'] ?>" class="btn">Modifier</a>

          <form
            method="post"
            action="index.php?page=book-delete"
            onsubmit="return confirm('Voulez-vous supprimer <?= $livre['titre'] ?> ?')">
            <input type="hidden" name="id" value="<?= $livre['id'] ?>">
            <button class="btn">🗑️</button>
          </form>

        <?php endif ?>

      </div>

    </article>

  <?php endforeach ?>

</div>