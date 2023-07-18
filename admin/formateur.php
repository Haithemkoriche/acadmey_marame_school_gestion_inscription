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

<!-- Instructeurs -->
<div id="instructors" class="content container mt-5 mb-5">
  <h1> <a class="nav-link" href="admin.php"><i class="fa fa-arrow-left"></i> </a>formateurs</h1>

  <!-- Bouton Ajouter un instructeur -->
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInstructorModal">Ajouter un instructeur</button>

  <?php
  // Connexion à la base de données
  $conn = mysqli_connect("localhost", "root", "", "ams");

  // Vérification de la connexion
  if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
  }

  // Vérification si un instructeur a été ajouté ou modifié
  if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
      // Récupération des données du formulaire d'ajout
      $instructorName = $_POST['instructor_name'];
      $instructorSpecialty = $_POST['instructor_specialty'];
      $instructorEmail = $_POST['instructor_email'];
      $instructorPhone = $_POST['instructor_phone'];
      $instructorDescription = $_POST['instructor_description'];

      // Récupération du fichier image téléchargé
      $instructorImage = $_FILES['instructor_image']['name'];
      $instructorImageTmp = $_FILES['instructor_image']['tmp_name'];
      $instructorImagePath = 'upload/' . $instructorImage;

      // Déplacement du fichier image vers le dossier de destination
      move_uploaded_file($instructorImageTmp, $instructorImagePath);

      // Insertion du nouvel instructeur dans la base de données
      $insertSql = "INSERT INTO instructors (instructor_name, instructor_specialty, instructor_email, instructor_phone, instructor_description, instructor_image)
                    VALUES ('$instructorName', '$instructorSpecialty', '$instructorEmail', '$instructorPhone', '$instructorDescription', '$instructorImagePath')";
      if (mysqli_query($conn, $insertSql)) {
        echo '<div class="alert alert-success" role="alert">Instructeur ajouté avec succès.</div>';
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout de l\'instructeur : ' . mysqli_error($conn) . '</div>';
      }
    } elseif ($action === 'update_ins') {
      // Récupération des données du formulaire de mise à jour
      $instructorId = $_POST['instructor_id'];
      $instructorName = $_POST['instructor_name'];
      $instructorSpecialty = $_POST['instructor_specialty'];
      $instructorEmail = $_POST['instructor_email'];
      $instructorPhone = $_POST['instructor_phone'];
      $instructorDescription = $_POST['instructor_description'];

      // Vérification si une nouvelle image a été téléchargée
      if (!empty($_FILES['instructor_image']['name'])) {
        // Récupération du fichier image téléchargé
        $instructorImage = $_FILES['instructor_image']['name'];
        $instructorImageTmp = $_FILES['instructor_image']['tmp_name'];
        $instructorImagePath = 'upload/' . $instructorImage;

        // Déplacement du fichier image vers le dossier de destination
        move_uploaded_file($instructorImageTmp, $instructorImagePath);

        // Mise à jour du chemin de l'image dans la base de données
        $updateSql_img = "UPDATE instructors SET instructor_image = '$instructorImagePath' WHERE instructor_id = $instructorId";
        if (mysqli_query($conn, $updateSql_img)) {
          echo '<div class="alert alert-success" role="alert">Image de l\'instructeur mise à jour avec succès.</div>';
        } else {
          echo '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour de l\'image de l\'instructeur : ' . mysqli_error($conn) . '</div>';
        }
      }

      // Mise à jour de l'instructeur dans la base de données
      $updateSql_ins = "UPDATE instructors 
                        SET instructor_name = '$instructorName', instructor_specialty = '$instructorSpecialty', 
                            instructor_email = '$instructorEmail', instructor_phone = '$instructorPhone',
                            instructor_description = '$instructorDescription'
                        WHERE instructor_id = $instructorId";
      if (mysqli_query($conn, $updateSql_ins)) {
        echo '<div class="alert alert-success" role="alert">Instructeur mis à jour avec succès.</div>';
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour de l\'instructeur : ' . mysqli_error($conn) . '</div>';
      }
    }
  }

  // Vérification si un instructeur a été supprimé
  if (isset($_GET['delete_ins'])) {
    $deleteId = $_GET['delete_ins'];

    // Suppression de l'instructeur de la base de données
    $deleteSql_ins = "DELETE FROM instructors WHERE instructor_id = $deleteId";
    if (mysqli_query($conn, $deleteSql_ins)) {
      echo '<div class="alert alert-success" role="alert">Instructeur supprimé avec succès.</div>';
    } else {
      echo '<div class="alert alert-danger" role="alert">Erreur lors de la suppression de l\'instructeur : ' . mysqli_error($conn) . '</div>';
    }
  }

  // Récupération des instructeurs
  $selectSql = "SELECT * FROM instructors";
  $result = mysqli_query($conn, $selectSql);

  if (mysqli_num_rows($result) > 0) {
    echo '<table class="table">
            <thead>
              <tr>
                <th>ID de l\'instructeur</th>
                <th>Nom</th>
                <th>Spécialité</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>';
      echo '<td>' . $row["instructor_id"] . '</td>';
      echo '<td>' . $row["instructor_name"] . '</td>';
      echo '<td>' . $row["instructor_specialty"] . '</td>';
      echo '<td>' . $row["instructor_email"] . '</td>';
      echo '<td>' . $row["instructor_phone"] . '</td>';
      echo '<td>' . $row["instructor_description"] . '</td>';
      echo '<td><img src="' . $row["instructor_image"] . '" alt="Image de l\'instructeur" class="img-fluid" /></td>';
      echo '<td>
      <div class="btn-group" role="group">
              <a href="#" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editInstructorModal-' . $row["instructor_id"] . '"><i class="fas fa-edit"></i></a>
              <a href="?delete_ins=' . $row["instructor_id"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet instructeur ?\')"><i class="fas fa-trash"></i></a>
              </div>
            </td>';
      echo '</tr>';

      // Modal de modification de l'instructeur
      echo '<div class="modal fade" id="editInstructorModal-' . $row["instructor_id"] . '" tabindex="-1" aria-labelledby="editInstructorModalLabel-' . $row["instructor_id"] . '" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editInstructorModalLabel-' . $row["instructor_id"] . '">Modifier l\'instructeur</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="action" value="update_ins">
                      <input type="hidden" name="instructor_id" value="' . $row["instructor_id"] . '">
                      <div class="form-group mt-2">
                        <label for="editInstructorName">Nom :</label>
                        <input type="text" class="form-control" id="editInstructorName" name="instructor_name" value="' . $row["instructor_name"] . '" required>
                      </div>
                      <div class="form-group mt-2">
                        <label for="editInstructorSpecialty">Spécialité :</label>
                        <input type="text" class="form-control" id="editInstructorSpecialty" name="instructor_specialty" value="' . $row["instructor_specialty"] . '" required>
                      </div>
                      <div class="form-group mt-2">
                        <label for="editInstructorEmail">Email :</label>
                        <input type="email" class="form-control" id="editInstructorEmail" name="instructor_email" value="' . $row["instructor_email"] . '" required>
                      </div>
                      <div class="form-group mt-2">
                        <label for="editInstructorPhone">Téléphone :</label>
                        <input type="text" class="form-control" id="editInstructorPhone" name="instructor_phone" value="' . $row["instructor_phone"] . '" required>
                      </div>
                      <div class="form-group mt-2">
                        <label for="editInstructorDescription">Description :</label>
                        <textarea class="form-control" id="editInstructorDescription" name="instructor_description" rows="3">' . $row["instructor_description"] . '</textarea>
                      </div>
                      <div class="form-group mt-2">
                        <label for="editInstructorImage">Image :</label>
                        <input type="file" class="form-control" id="editInstructorImage" name="instructor_image">
                      </div>
                      <button type="submit" class="btn btn-primary mt-3">Enregistrer les modifications</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
    }

    echo '</tbody></table>';
  } else {
    echo '<p>Aucun instructeur trouvé.</p>';
  }

  ?>
</div>


<!-- Modal Ajouter un instructeur -->
<div class="modal fade" id="addInstructorModal" tabindex="-1" aria-labelledby="addInstructorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addInstructorModalLabel">Ajouter un instructeur</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add">
          <div class="form-group mt-2">
            <label for="instructorName">Nom :</label>
            <input type="text" class="form-control" id="instructorName" name="instructor_name" required>
          </div>
          <div class="form-group mt-2">
            <label for="instructorSpecialty">Spécialité :</label>
            <input type="text" class="form-control" id="instructorSpecialty" name="instructor_specialty" required>
          </div>
          <div class="form-group mt-2">
            <label for="instructorEmail">Email :</label>
            <input type="email" class="form-control" id="instructorEmail" name="instructor_email" required>
          </div>
          <div class="form-group mt-2">
            <label for="instructorPhone">Téléphone :</label>
            <input type="text" class="form-control" id="instructorPhone" name="instructor_phone" required>
          </div>
          <div class="form-group mt-2">
            <label for="instructorDescription">Description :</label>
            <textarea class="form-control" id="instructorDescription" name="instructor_description" rows="3"></textarea>
          </div>
          <div class="form-group mt-2">
            <label for="instructorImage">Image :</label>
            <input type="file" class="form-control" id="instructorImage" name="instructor_image" required>
          </div>
          <button type="submit" class="btn btn-primary  mt-2">Ajouter un instructeur</button>
        </form>
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
