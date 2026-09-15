<?php

// Récupération de l'ID dans l'URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$livre = false;

if ($id !== false && $id !== null) {

  $sql = "SELECT  l.id,
                  l.titre,
                  l.annee,
                  l.prix,
                  l.isbn,
                  a.id AS auteur_id,
                  a.nom AS auteur_nom,
                  a.prenom AS auteur_prenom,
                  a.nationalite AS auteur_nationalite
          FROM  livre AS l
                JOIN auteur AS a ON l.auteur_id = a.id
          WHERE l.id = ?";

  $request = $pdo->prepare($sql);
  $request->execute([$id]);

  $livre = $request->fetch();
}

?>


<?php if (!$livre) : ?>

  <h1>Livre introuvable</h1>
  <p>Aucun livre ne correspond à l'id <?= $id ?>.</p>

<?php else : ?>

  <h1>Détails de <?= htmlspecialchars($livre["titre"]) ?></h1>

  <dl>

    <dt>Année de parution:</dt>
    <dd><?= htmlspecialchars($livre['annee'] ?? 'inconnue') ?></dd>

    <dt>Prix:</dt>
    <dd><?= number_format($livre['prix'], 2, ',', ' ') ?> &euro;</dd>

    <dt>Code EAN13:</dt>
    <dd><?= htmlspecialchars($livre['isbn'] ?? '-') ?></dd>

    <dt>Auteur:</dt>
    <dd>
      <a href="?page=author-details&amp;id=<?= $livre['auteur_id'] ?>">
        <?= htmlspecialchars($livre['auteur_nom']) ?>
        <?= htmlspecialchars($livre['auteur_prenom']) ?>
      </a> (<?= htmlspecialchars($livre['auteur_nationalite']) ?>)
    </dd>

  </dl>

  <a href="?page=books" class="btn btn-back">Retour à la liste</a>

<?php endif ?>
