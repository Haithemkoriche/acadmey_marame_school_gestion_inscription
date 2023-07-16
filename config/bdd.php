<?php
// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "ams");

// Vérification de la connexion
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
