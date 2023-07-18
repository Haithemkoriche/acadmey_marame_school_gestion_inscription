<?php
session_start();

// Vérifier si l'utilisateur n'est pas authentifié
if (!isset($_SESSION["username"])) {
  // Rediriger vers la page de connexion
  header("Location: index.php");
  exit;
}
?>
<?php require_once "../config/bdd.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../asstes/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../asstes/fonts/css/all.min.css">

  <title>Dashboard</title>
</head>

<body>
  <!-- Barre de navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">Tableau de bord</a>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item me-3">
          <a class="nav-link" href="#">Voir le site</a>
        </li>
        <li class="nav-item me-3">
          <button id="btn-logout" class="btn btn-link nav-link">Déconnexion</button>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Contacts -->
  <div id="contacts" class="content container mt-5 mb-5">
    <h1> <a class="nav-link" href="admin.php"><i class="fa fa-arrow-left"></i> </a>Contacts</h1>

    <?php

    // Vérification si un message a été supprimé
    if (isset($_GET['delete_ms'])) {
      $deleteId = $_GET['delete_ms'];

      // Suppression du message de la base de données
      $deleteSql_ms = "DELETE FROM contact_messages WHERE id_contact = $deleteId";
      if (mysqli_query($conn, $deleteSql_ms)) {
        echo '<div class="alert alert-success" role="alert">Message deleted successfully.</div>';
      } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting message: ' . mysqli_error($conn) . '</div>';
      }
    }

    // Récupération des messages de contact
    $selectSql = "SELECT * FROM contact_messages";
    $result = mysqli_query($conn, $selectSql);

    if (mysqli_num_rows($result) > 0) {
      echo '<table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';

      while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row["id_contact"] . '</td>';
        echo '<td>' . $row["name_contact"] . '</td>';
        echo '<td>' . $row["email_contact"] . '</td>';
        echo '<td>' . $row["message_contact"] . '</td>';
        echo '<td>
              <a href="?delete_ms=' . $row["id_contact"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this message?\')"><i class="fas fa-trash"></i></a>
            </td>';
        echo '</tr>';
      }

      echo '</tbody></table>';
    } else {
      echo '<p>No contact messages found.</p>';
    }


    ?>
  </div>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to logout?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="logout.php" class="btn btn-primary">Logout</a>
        </div>
      </div>
    </div>
  </div>
 <!-- Logout Modal -->
 <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logoutModalLabel">Confirmer la déconnexion</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Êtes-vous sûr(e) de vouloir vous déconnecter ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <a href="logout.php" class="btn btn-primary">Déconnexion</a>
          </div>
        </div>
      </div>
    </div>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../asstes/bootstrap/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
     
      // Gérer la déconnexion
      $('#btn-logout').click(function() {
        $('#logoutModal').modal('show');
      });
    });
  </script>
</body>

</html>
<?php
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>