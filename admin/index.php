<?php
session_start();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les valeurs du formulaire
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Vérifier si les informations d'identification sont valides
  if (verifyAdminTable()) {
    if (authenticateUser($username, $password)) {
      // Authentification réussie, rediriger vers la page d'administration
      $_SESSION["username"] = $username;
      header("Location: admin.php");
      exit;
    } else {
      // Authentification échouée, afficher un message d'erreur
      $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
  } else {
    // Créer un mot de passe automatique pour l'administrateur initial
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    createAdmin($username, $hashedPassword);

    // Authentification réussie, rediriger vers la page d'administration
    $_SESSION["username"] = $username;
    header("Location: admin.php");
    exit;
  }
}

// Vérifier si la table "admin" est vide
function verifyAdminTable() {
  // Connexion à la base de données
  $conn = mysqli_connect("localhost", "root", "", "ams");

  // Vérification de la connexion
  if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
  }

  // Vérification du nombre de lignes dans la table "admin"
  $query = "SELECT COUNT(*) as count FROM admin";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $count = $row["count"];

  // Fermer la connexion à la base de données
  mysqli_close($conn);

  return $count > 0;
}

// Authentifier l'utilisateur
function authenticateUser($username, $password) {
  // Connexion à la base de données
  $conn = mysqli_connect("localhost", "root", "", "ams");

  // Vérification de la connexion
  if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
  }

  // Récupérer le mot de passe hashé correspondant à l'utilisateur
  $query = "SELECT password_admin FROM admin WHERE username_admin = '$username'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $hashedPassword = $row["password_admin"];

    // Vérifier si le mot de passe est valide
    if (password_verify($password, $hashedPassword)) {
      // Fermer la connexion à la base de données
      mysqli_close($conn);
      return true;
    }
  }

  // Fermer la connexion à la base de données
  mysqli_close($conn);
  return false;
}

// Créer un nouvel administrateur
function createAdmin($username, $hashedPassword) {
  // Connexion à la base de données
  $conn = mysqli_connect("localhost", "root", "", "ams");

  // Vérification de la connexion
  if (!$conn) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
  }

  // Insérer les données dans la table "admin"
  $query = "INSERT INTO admin (username_admin, password_admin) VALUES ('$username', '$hashedPassword')";
  mysqli_query($conn, $query);

  // Fermer la connexion à la base de données
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../asstes/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../asstes/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <title>Connexion</title>
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="text-center mb-4">Connexion Administrateur</h2>
        <?php if (isset($error)) { ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } else if (!verifyAdminTable()) { ?>
          <div class="alert alert-warning">
            La table des administrateurs est vide. Veuillez vous souvenir de vos informations de connexion.
          </div>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block mt-2">Connexion</button>
        </form>
      </div>
    </div>
  </div>
<script src="../asstes/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
