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
<!-- Courses -->
<div id="courses" class="content container mt-5 mb-5">
  <h1><a class="nav-link" href="admin.php"><i class="fa fa-arrow-left"></i> </a>Cours</h1>

  <!-- Ajouter un cours -->
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">Ajouter un cours</button>

  <?php
  // Vérifier si un cours a été ajouté ou mis à jour
  if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_crs') {
      // Récupérer les données du formulaire d'ajout
      $courseName = $_POST['course_name'];
      $courseDescription = $_POST['course_description'];
      $instructorId = $_POST['instructor_id'];

      // Vérifier si une image a été téléchargée
      if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $targetDirectory = "upload/"; // Répertoire de destination pour les images
        $targetFile = $targetDirectory . basename($_FILES['course_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Vérifier le type de fichier
        $allowedExtensions = array("jpg", "jpeg", "png");
        if (in_array($imageFileType, $allowedExtensions)) {
          // Déplacer le fichier vers le répertoire de destination
          if (move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
            // Insérer le nouveau cours dans la base de données avec le chemin de l'image
            $insertSql = "INSERT INTO courses (course_name, course_description, course_image, instructor_id)
                          VALUES ('$courseName', '$courseDescription', '$targetFile', $instructorId)";
            if (mysqli_query($conn, $insertSql)) {
              echo '<div class="alert alert-success" role="alert">Cours ajouté avec succès.</div>';
            } else {
              echo '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout du cours : ' . mysqli_error($conn) . '</div>';
            }
          } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors du téléchargement de l\'image.</div>';
          }
        } else {
          echo '<div class="alert alert-danger" role="alert">Format d\'image invalide. Formats autorisés : JPG, JPEG, PNG.</div>';
        }
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors du téléchargement de l\'image.</div>';
      }
    } elseif ($action === 'update') {
      // Récupérer les données du formulaire de modification
      $courseId = $_POST['course_id'];
      $courseName = $_POST['course_name'];
      $courseDescription = $_POST['course_description'];
      $instructorId = $_POST['instructor_id'];

      // Vérifier si une nouvelle image a été sélectionnée
      $updateImage = false;
      if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $targetDirectory = "upload/"; // Répertoire de destination pour les images
        $targetFile = $targetDirectory . basename($_FILES['course_image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Vérifier le type de fichier
        $allowedExtensions = array("jpg", "jpeg", "png");
        if (in_array($imageFileType, $allowedExtensions)) {
          // Déplacer le fichier vers le répertoire de destination
          if (move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
            $updateImage = true;
          } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors du téléchargement de l\'image.</div>';
          }
        } else {
          echo '<div class="alert alert-danger" role="alert">Format d\'image invalide. Formats autorisés : JPG, JPEG, PNG.</div>';
        }
      }

      // Mettre à jour le cours dans la base de données
      if ($updateImage) {
        $updateSql = "UPDATE courses 
                      SET course_name = '$courseName', course_description = '$courseDescription', course_image = '$targetFile', instructor_id = $instructorId
                      WHERE course_id = $courseId";
      } else {
        $updateSql = "UPDATE courses 
                      SET course_name = '$courseName', course_description = '$courseDescription', instructor_id = $instructorId
                      WHERE course_id = $courseId";
      }

      if (mysqli_query($conn, $updateSql)) {
        echo '<div class="alert alert-success" role="alert">Cours mis à jour avec succès.</div>';
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour du cours : ' . mysqli_error($conn) . '</div>';
      }
    }
  }

  // Vérifier si un cours a été supprimé
  if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];

    // Supprimer le cours de la base de données
    $deleteSql = "DELETE FROM courses WHERE course_id = $deleteId";
    if (mysqli_query($conn, $deleteSql)) {
      echo '<div class="alert alert-success" role="alert">Cours supprimé avec succès.</div>';
    } else {
      echo '<div class="alert alert-danger" role="alert">Erreur lors de la suppression du cours : ' . mysqli_error($conn) . '</div>';
    }
  }

  // Récupérer les cours avec les informations associées à l'instructeur
  $selectSql = "SELECT courses.course_id, courses.course_name, courses.course_description, courses.course_image, instructors.instructor_name
                FROM courses
                LEFT JOIN instructors ON courses.instructor_id = instructors.instructor_id";
  $result = mysqli_query($conn, $selectSql);

  if (mysqli_num_rows($result) > 0) {
    echo '<table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Description</th>
              <th>Image</th>
              <th>Instructeur</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>';
      echo '<td>' . $row["course_id"] . '</td>';
      echo '<td>' . $row["course_name"] . '</td>';
      echo '<td>' . $row["course_description"] . '</td>';
      echo '<td><img src="' . $row["course_image"] . '" alt="Image du cours" height="50"></td>';
      echo '<td>' . $row["instructor_name"] . '</td>';
      echo '<td>
      <div class="btn-group" role="group">
            <button class="btn btn-sm btn-primary  me-2" data-bs-toggle="modal" data-bs-target="#editCourseModal_' . $row["course_id"] . '"><i class="fas fa-edit"></i></button>
            <a href="?delete=' . $row["course_id"] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce cours ?\')"><i class="fas fa-trash"></i></a>
        </div>
          </td>';
      echo '</tr>';

      // Modal de modification de cours
      echo '<div class="modal fade" id="editCourseModal_' . $row["course_id"] . '" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">';
      echo '<div class="modal-dialog">';
      echo '<div class="modal-content">';
      echo '<div class="modal-header">';
      echo '<h5 class="modal-title" id="editCourseModalLabel">Modifier le cours</h5>';
      echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
      echo '</div>';
      echo '<div class="modal-body">';
      echo '<form action="" method="POST" enctype="multipart/form-data">';
      echo '<input type="hidden" name="action" value="update">';
      echo '<input type="hidden" name="course_id" value="' . $row["course_id"] . '">';
      echo '<div class="mb-3">';
      echo '<label for="edit_course_name" class="form-label">Nom du cours</label>';
      echo '<input type="text" class="form-control" id="edit_course_name" name="course_name" value="' . $row["course_name"] . '" required>';
      echo '</div>';
      echo '<div class="mb-3">';
      echo '<label for="edit_course_description" class="form-label">Description du cours</label>';
      echo '<textarea class="form-control" id="edit_course_description" name="course_description" required>' . $row["course_description"] . '</textarea>';
      echo '</div>';
      echo '<div class="mb-3">';
      echo '<label for="edit_course_image" class="form-label">Image du cours</label>';
      echo '<input type="file" class="form-control" id="edit_course_image" name="course_image" accept="image/jpeg,image/png">';
      echo '</div>';
      echo '<div class="mb-3">';
      echo '<label for="edit_instructor_id" class="form-label">Instructeur</label>';
      echo '<select class="form-select" id="edit_instructor_id" name="instructor_id" required>';

      // Récupérer les instructeurs
      $instructorSql = "SELECT instructor_id, instructor_name FROM instructors";
      $instructorResult = mysqli_query($conn, $instructorSql);

      while ($instructorRow = mysqli_fetch_assoc($instructorResult)) {
        $selected = ($instructorRow["instructor_id"] == $row["instructor_id"]) ? "selected" : "";
        echo '<option value="' . $instructorRow["instructor_id"] . '" ' . $selected . '>' . $instructorRow["instructor_name"] . '</option>';
      }

      echo '</select>';
      echo '</div>';
      echo '<div class="modal-footer">';
      echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>';
      echo '<button type="submit" class="btn btn-primary">Mettre à jour le cours</button>';
      echo '</div>';
      echo '</form>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }

    echo '</tbody></table>';
  } else {
    echo '<div class="alert alert-info" role="alert">Aucun cours trouvé.</div>';
  }
  ?>

</div>

<!-- Modal d'ajout de cours -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCourseModalLabel">Ajouter un cours</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add_crs">

          <div class="mb-3">
            <label for="course_name" class="form-label">Nom du cours</label>
            <input type="text" class="form-control" id="course_name" name="course_name" required>
          </div>

          <div class="mb-3">
            <label for="course_description" class="form-label">Description du cours</label>
            <textarea class="form-control" id="course_description" name="course_description" required></textarea>
          </div>

          <div class="mb-3">
            <label for="course_image" class="form-label">Image du cours</label>
            <input type="file" class="form-control" id="course_image" name="course_image" accept="image/jpeg,image/png" required>
          </div>

          <div class="mb-3">
            <label for="instructor_id" class="form-label">Instructeur</label>
            <select class="form-select" id="instructor_id" name="instructor_id" required>
              <?php
              // Récupérer les instructeurs
              $instructorSql = "SELECT instructor_id, instructor_name FROM instructors";
              $instructorResult = mysqli_query($conn, $instructorSql);

              while ($instructorRow = mysqli_fetch_assoc($instructorResult)) {
                echo '<option value="' . $instructorRow["instructor_id"] . '">' . $instructorRow["instructor_name"] . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-primary">Ajouter le cours</button>
          </div>
        </form>
      </div>
    </div>
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