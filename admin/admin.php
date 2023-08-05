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
  <style>
    /* Ajoutez votre propre style CSS ici */
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    @media (min-width: 576px) {
      .card-columns {
        column-count: 1;
      }
    }

    @media (min-width: 768px) {
      .card-columns {
        column-count: 2;
      }
    }

    @media (min-width: 992px) {
      .card-columns {
        column-count: 3;
      }
    }
  </style>
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
  <div class="container">
    <h1 class="text-center">Tableau de bord</h1>
    <div class="row row-gap-5 mt-5 mb-5">
      <div class="col-md-4">
        <div class="card">
          <img src="../img/demand_ins.jpg" class="card-img-top" alt="Image Inscriptions">
          <div class="card-body">
            <h5 class="card-title">Demandes d'Inscriptions</h5>
            <a class="btn btn-primary" href="di.php" data-target="registrations">Voir</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <img src="../img/contact.png" class="card-img-top" alt="Image Contacts">
          <div class="card-body">
            <h5 class="card-title">Contacts</h5>
            <a class="btn btn-primary" href="contact.php" data-target="contacts">Voir</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <img src="../img/etudiants.avif" class="card-img-top" alt="Image Étudiants">
          <div class="card-body">
            <h5 class="card-title">Étudiants</h5>
            <a class="btn btn-primary" href="etudiant.php" data-target="students">Voir</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <img src="../img/become_a_student_inline.jpg" class="card-img-top" alt="Image Cours">
          <div class="card-body">
            <h5 class="card-title">Cours</h5>
            <a class="btn btn-primary" href="cours.php" data-target="courses">Voir</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <img src="../img/video_bg.jpg" class="card-img-top" alt="Image Formateurs">
          <div class="card-body">
            <h5 class="card-title">Formateurs</h5>
            <a class="btn btn-primary" href="formateur.php" data-target="instructors">Voir</a>
          </div>
        </div>
      </div>
      <!-- Ajoutez d'autres cartes pour les fonctions d'administration -->
    </div>
  </div>
  <p style="text-align: center;">Tous droits réservés © 2023 AMS</p>
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