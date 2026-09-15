<?php

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$author = false;
$livres = false;

if ($id !== false && $id !== null) {

  // Récupération des données de l'auteur
  $sql = "SELECT  
            a.id,
            a.nom,
            a.prenom,
            a.nationalite
          FROM 
            auteur a
          WHERE
            a.id = ?";

  $statement = $pdo->prepare($sql);
  $statement->execute([$id]);

  $author = $statement->fetch();

  $title = 'Auteur: ' . $author["prenom"] . ' ' . $author["nom"];

  // Récupération des livres de l'auteur

  $sql = "SELECT
            l.id,
            l.titre
          FROM
            livre l
          WHERE
            l.auteur_id = ?";

  $statement = $pdo->prepare($sql);
  $statement->execute([$id]);

  $livres = $statement->fetchAll();
}

?>


<!-- Template -->

<?php if (!$author) : ?>

  <h1>Auteur introuvable.</h1>

<?php else : ?>

  <h1><?= htmlspecialchars($author['nom'] . ' ' . $author['prenom']) ?></h1>

  <dl>
    <dt>Nationalité:</dt>
    <dd><?= htmlspecialchars($author['nationalite']) ?></dd>
    <dt>Liste de ses livres:</dt>
    <dd>
      <?php if (count($livres) === 0) : ?>
        <p>Aucun livre pour le moment.</p>
      <?php else : ?>
        <ul>
          <?php foreach ($livres as $l) : ?>
            <li>
              <a href="?page=book-details&id=<?= $l['id'] ?>">
                <?= htmlspecialchars($l['titre']) ?>
              </a>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>
    </dd>
  </dl>

<?php endif ?>