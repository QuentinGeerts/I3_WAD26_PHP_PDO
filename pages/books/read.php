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
      </div>

    </article>

  <?php endforeach ?>

</div>