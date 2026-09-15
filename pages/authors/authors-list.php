<?php

$sql = "SELECT
          a.id,
          a.nom,
          a.prenom
        FROM 
          auteur a
        ORDER BY 
          nom ASC, prenom ASC";

$authors = $pdo->query($sql)->fetchAll();

?>

<!-- Template -->

<h1>Liste des auteurs :</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Nom complet</th>
      <th>Détails</th>
      <th>Modifier</th>
      <th>Supprimer</th>
    </tr>
  </thead>
  <tbody>

    <?php foreach ($authors as $a): ?>

      <tr>
        <td><?= htmlspecialchars($a["id"]) ?></td>
        <td><?= htmlspecialchars($a["nom"] . ' ' . $a['prenom']) ?></td>
        <td><a href="?page=author-details&id=<?= $a['id'] ?>">🔎</a></td>
        <td><a href="?page=author-edit&id=<?= $a['id'] ?>">🖊️</a></td>
        <td><a href="?page=author-delete&id=<?= $a['id'] ?>">🗑️</a></td>
      </tr>

    <?php endforeach ?>

  </tbody>
</table>
