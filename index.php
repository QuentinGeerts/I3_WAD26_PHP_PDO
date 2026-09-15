<?php


// Gestion des routes
$routes = [

  '' => [
    'file' => 'pages/home.php',
    'title' => 'Accueil'
  ],

  /**
   * Gestion des livres
   */
  'books' => [
    'file' => 'pages/books/read.php',
    'title' => 'Liste des livres'
  ],

  'book-details' => [
    'file' => 'pages/books/details.php',
    'title' => 'Détails du livre'
  ],

  'book-create' => [
    'file' => 'pages/books/create.php',
    'title' => 'Création d\'un livre',
  ],

  'book-delete' => [
    'file' => 'pages/books/book-delete.php',
    'title' => 'Suppression d\'un livre'
  ],

  'book-edit' => [
    'file' => 'pages/books/book-update.php',
    'title' => 'Modification d\'un livre'
  ],



  /**
   * Gestion des auteurs
   */

  'authors' => [
    'file' => 'pages/authors/authors-list.php',
    'title' => 'Liste des auteurs',
  ],

  'author-details' => [
    'file' => 'pages/authors/author-details.php',
    'title' => 'Détails de l\'auteur',
  ],

  /**
   * Gestion de l'authentification
   */

  'register' => [
    'file' => 'pages/auth/register.php',
    'title' => 'S\'enregistrer',
  ],

  'login' => [
    'file' => 'pages/auth/login.php',
    'title' => 'Se connecter',
  ],

  'logout' => [
    'file' => 'pages/auth/logout.php',
    'title' => 'Se déconnecter',
  ],


];

$page = $_GET['page'] ?? '';
$route = $routes[$page] ?? null;

if ($route === null) {
  $route = [
    'file' => 'pages/errors/not-found.php',
    'title' => "404 not found"
  ];
}

$file = $route["file"];
$title = $route["title"];

require_once 'config/database.php';

// Assembler les pages

ob_start();
require_once $file;
$content = ob_get_clean();


require_once 'partials/header.php';
echo $content;
require_once 'partials/footer.php';

?>