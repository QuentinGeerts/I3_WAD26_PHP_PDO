<nav>
  <ul>
    <li><a href="index.php">Accueil</a></li>

    <li>
      Gestion des livres
      <ul>
        <li><a href="index.php?page=books">Liste des livres</a></li>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
          <li><a href="index.php?page=book-create">Création d'un livre</a></li>
        <?php endif ?>
      </ul>
    </li>


    <li>
      Gestion des auteurs
      <ul>
        <li><a href="index.php?page=authors">Liste des auteurs</a></li>
      </ul>
    </li>
  </ul>

  <ul>
    <?php if (!isset($_SESSION['user'])) : ?>
      <li><a href="index.php?page=login">Se connecter</a></li>
      <li><a href="index.php?page=register">S'inscrire</a></li>
    <?php else : ?>
      <li>Connecté en tant que : <?= $_SESSION['user']['email'] ?> (<?= $_SESSION['user']['role'] ?>)</li>
      <li><a href="index.php?page=logout">Se déconnecter</a></li>
    <?php endif ?>
  </ul>
</nav>