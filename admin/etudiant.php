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
<!-- Étudiants -->
<div id="students" class="content container mt-5 mb-5">
  <h1> <a class="nav-link" href="admin.php"><i class="fa fa-arrow-left"></i> </a>Étudiants</h1>

  <!-- Bouton Ajouter un étudiant -->
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Ajouter un étudiant</button>

  <?php
  // Vérification si un étudiant a été ajouté ou modifié
  if (isset($_POST['action_stu'])) {
    $action = $_POST['action_stu'];

    if ($action === 'add_stu') {
      // Récupération des données du formulaire d'ajout
      $studentName = $_POST['student_name'];
      $studentEmail = $_POST['student_email'];
      $studentPhone = $_POST['student_phone'];
      $studentCourse = $_POST['student_course'];
      $studentStartDate = $_POST['student_start_date'];

      // Insertion du nouvel étudiant dans la table "etudiants"
      $insertStudentSql = "INSERT INTO etudiants (nom_etu, prenom_etu, date_naissance_etu, numero_telephone_etu, email_etu)
        VALUES ('$studentName', '', '', '$studentPhone', '$studentEmail')";
      if (mysqli_query($conn, $insertStudentSql)) {
        $studentId = mysqli_insert_id($conn);

        // Récupération de l'ID de l'instructeur depuis la table "courses"
        $instructorSql = "SELECT instructor_id FROM courses WHERE course_id = $studentCourse";
        $instructorResult = mysqli_query($conn, $instructorSql);
        $instructorRow = mysqli_fetch_assoc($instructorResult);
        $instructorId = $instructorRow["instructor_id"];

        // Insertion des informations supplémentaires dans la table "etudiant_speciality"
        $insertSpecialitySql = "INSERT INTO etudiant_speciality (id_etudiant, id_instructor, id_course, date_debut, date_fin)
          VALUES ($studentId, $instructorId, $studentCourse, '$studentStartDate', '')";
        if (mysqli_query($conn, $insertSpecialitySql)) {
          echo '<div class="alert alert-success" role="alert">Étudiant ajouté avec succès.</div>';
        } else {
          echo '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout de l\'étudiant : ' . mysqli_error($conn) . '</div>';
        }
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout de l\'étudiant : ' . mysqli_error($conn) . '</div>';
      }
    } elseif ($action === 'update') {
      // Récupération des données du formulaire de mise à jour
      $studentId = $_POST['student_id'];
      $studentName = $_POST['student_name'];
      $studentEmail = $_POST['student_email'];
      $studentPhone = $_POST['student_phone'];
      $studentCourse = $_POST['student_course'];
      $studentStartDate = $_POST['student_start_date'];

      // Mise à jour du nom de l'étudiant dans la table "etudiants"
      $updateStudentSql = "UPDATE etudiants 
        SET nom_etu = '$studentName', numero_telephone_etu = '$studentPhone', email_etu = '$studentEmail'
        WHERE id_etudiant = $studentId";
      if (mysqli_query($conn, $updateStudentSql)) {

        // Récupération de l'ID de l'instructeur depuis la table "courses"
        $instructorSql = "SELECT instructor_id FROM courses WHERE course_id = $studentCourse";
        $instructorResult = mysqli_query($conn, $instructorSql);
        $instructorRow = mysqli_fetch_assoc($instructorResult);
        $instructorId = $instructorRow["instructor_id"];

        // Mise à jour des informations supplémentaires dans la table "etudiant_speciality"
        $updateSpecialitySql = "UPDATE etudiant_speciality 
          SET id_instructor = $instructorId, id_course = $studentCourse, date_debut = '$studentStartDate'
          WHERE id_etudiant = $studentId";
        if (mysqli_query($conn, $updateSpecialitySql)) {
          echo '<div class="alert alert-success" role="alert">Étudiant mis à jour avec succès.</div>';
        } else {
          echo '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour de l\'étudiant : ' . mysqli_error($conn) . '</div>';
        }
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de la mise à jour de l\'étudiant : ' . mysqli_error($conn) . '</div>';
      }
    }
  }

  // Vérification si un étudiant a été supprimé
  if (isset($_GET['delete_stu'])) {
    $deleteId = $_GET['delete_stu'];

    // Suppression des informations supplémentaires dans la table "etudiant_speciality"
    $deleteSpecialitySql = "DELETE FROM etudiant_speciality WHERE id_etudiant = $deleteId";
    if (mysqli_query($conn, $deleteSpecialitySql)) {

      // Suppression de l'étudiant de la table "etudiants"
      $deleteStudentSql = "DELETE FROM etudiants WHERE id_etudiant = $deleteId";
      if (mysqli_query($conn, $deleteStudentSql)) {
        echo '<div class="alert alert-success" role="alert">Étudiant supprimé avec succès.</div>';
      } else {
        echo '<div class="alert alert-danger" role="alert">Erreur lors de la suppression de l\'étudiant : ' . mysqli_error($conn) . '</div>';
      }
    } else {
      echo '<div class="alert alert-danger" role="alert">Erreur lors de la suppression de l\'étudiant : ' . mysqli_error($conn) . '</div>';
    }
  }

  // Récupération des étudiants avec leurs informations supplémentaires
  $selectSql = "SELECT etudiants.id_etudiant, etudiants.nom_etu, etudiants.prenom_etu, etudiants.date_naissance_etu, etudiants.numero_telephone_etu, etudiants.email_etu, etudiant_speciality.id_instructor, etudiant_speciality.id_course, etudiant_speciality.date_debut, etudiant_speciality.date_fin
    FROM etudiants
    INNER JOIN etudiant_speciality ON etudiants.id_etudiant = etudiant_speciality.id_etudiant";
  $result = mysqli_query($conn, $selectSql);

  if (mysqli_num_rows($result) > 0) {
    echo '<table class="table">
      <thead>
        <tr>
          <th>ID Étudiant</th>
          <th>Nom</th>
          <th>Date de naissance</th>
          <th>Téléphone</th>
          <th>Email</th>
          <th>Instructeur</th>
          <th>Cours</th>
          <th>Date de début</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>';
      echo '<td>' . $row["id_etudiant"] . '</td>';
      echo '<td>' . $row["nom_etu"] . ' ' . $row["prenom_etu"] . '</td>';
      echo '<td>' . $row["date_naissance_etu"] . '</td>';
      echo '<td>' . $row["numero_telephone_etu"] . '</td>';
      echo '<td>' . $row["email_etu"] . '</td>';

      // Récupération de l'instructeur
      $instructorId = $row["id_instructor"];
      $instructorSql = "SELECT instructor_name FROM instructors WHERE instructor_id = $instructorId";
      $instructorResult = mysqli_query($conn, $instructorSql);
      $instructorRow = mysqli_fetch_assoc($instructorResult);
      $instructorName = $instructorRow["instructor_name"];


      echo '<td>' . $instructorName . '</td>';

      // Récupération du cours
      $courseId = $row["id_course"];
      $courseSql = "SELECT course_name FROM courses WHERE course_id = $courseId";
      $courseResult = mysqli_query($conn, $courseSql);
      $courseRow = mysqli_fetch_assoc($courseResult);
      $courseName = $courseRow["course_name"];

      echo '<td>' . $courseName . '</td>';
      echo '<td>' . $row["date_debut"] . '</td>';

      // Actions
      echo '<td>
      <div class="btn-group" role="group">
        <button class="btn btn-sm btn-primary edit-student me-2" data-bs-toggle="modal" data-bs-target="#editStudentModal" data-student-id="' . $row["id_etudiant"] . '"><i class="fas fa-edit"></i></button>
        <a href="?delete_stu=' . $row["id_etudiant"] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet étudiant ?\')"><i class="fas fa-trash"></i></a>
        </div>
      </td>';

      echo '</tr>';
    }

    echo '</tbody>
    </table>';
  } else {
    echo '<div class="alert alert-info" role="alert">Aucun étudiant trouvé.</div>';
  }
  // Récupérer les cours depuis la base de données
  $coursesSql = "SELECT course_id, course_name FROM courses";
  $coursesResult = mysqli_query($conn, $coursesSql);

  ?>
  <!-- Modal Ajouter un étudiant -->
  <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addStudentModalLabel">Ajouter un étudiant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
            <input type="hidden" name="action_stu" value="add_stu">

            <div class="mb-3">
              <label for="student_name" class="form-label">Nom</label>
              <input type="text" class="form-control" id="student_name" name="student_name" required>
            </div>

            <div class="mb-3">
              <label for="student_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="student_email" name="student_email" required>
            </div>

            <div class="mb-3">
              <label for="student_phone" class="form-label">Téléphone</label>
              <input type="text" class="form-control" id="student_phone" name="student_phone" required>
            </div>

            <div class="mb-3">
              <label for="student_course" class="form-label">Cours</label>
              <select class="form-select" id="student_course" name="student_course" required>
                <?php
                // Afficher les options de sélection des cours
                while ($courseRow = mysqli_fetch_assoc($coursesResult)) {
                  echo '<option value="' . $courseRow["course_id"] . '">' . $courseRow["course_name"] . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="student_start_date" class="form-label">Date de début</label>
              <input type="date" class="form-control" id="student_start_date" name="student_start_date" required>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
              <button type="submit" class="btn btn-primary">Ajouter l'étudiant</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modifier un étudiant -->
  <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editStudentModalLabel">Modifier un étudiant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="POST">
            <input type="hidden" name="action_stu" value="update">
            <input type="hidden" id="edit_student_id" name="student_id">

            <div class="mb-3">
              <label for="edit_student_name" class="form-label">Nom</label>
              <input type="text" class="form-control" id="edit_student_name" name="student_name" required>
            </div>

            <div class="mb-3">
              <label for="edit_student_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="edit_student_email" name="student_email" required>
            </div>

            <div class="mb-3">
              <label for="edit_student_phone" class="form-label">Téléphone</label>
              <input type="text" class="form-control" id="edit_student_phone" name="student_phone" required>
            </div>

            <div class="mb-3">
              <label for="edit_student_course" class="form-label">Cours</label>
              <select class="form-select" id="edit_student_course" name="student_course" required>
                <?php
                // Afficher les options de sélection des cours
                mysqli_data_seek($coursesResult, 0);
                while ($courseRow = mysqli_fetch_assoc($coursesResult)) {
                  echo '<option value="' . $courseRow["course_id"] . '">' . $courseRow["course_name"] . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="mb-3">
              <label for="edit_student_start_date" class="form-label">Date de début</label>
              <input type="date" class="form-control" id="edit_student_start_date" name="student_start_date" required>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
              <button type="submit" class="btn btn-primary">Modifier l'étudiant</button>
            </div>
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

      // Remplir le formulaire de modification d'étudiant avec les données actuelles
      $('.edit-student').click(function() {
        var studentId = $(this).data('student-id');
        var studentName = $(this).closest('tr').find('td:eq(1)').text();
        var studentEmail = $(this).closest('tr').find('td:eq(4)').text();
        var studentPhone = $(this).closest('tr').find('td:eq(3)').text();
        var studentCourse = $(this).closest('tr').find('td:eq(6)').text();
        var studentStartDate = $(this).closest('tr').find('td:eq(7)').text();

        $('#edit_student_id').val(studentId);
        $('#edit_student_name').val(studentName);
        $('#edit_student_email').val(studentEmail);
        $('#edit_student_phone').val(studentPhone);
        $('#edit_student_course').val(studentCourse);
        $('#edit_student_start_date').val(studentStartDate);
      });
    });
  </script>
</body>

</html>
