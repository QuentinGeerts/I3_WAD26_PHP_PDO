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

require_once 'partials/header.php';
require_once $file;
require_once 'partials/footer.php';

?>