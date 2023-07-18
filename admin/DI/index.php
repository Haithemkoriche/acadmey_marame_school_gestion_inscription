<?php require_once "../../config/bdd.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../asstes/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../asstes/fonts/css/all.min.css">
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

  <!-- Registrations -->
  <div id="registrations" class="content container mt-5">
    <h1> <a class="nav-link" href="../admin.php"><i class="fa fa-arrow-left"></i> </a>Registrations</h1>

    <?php
    // Vérification des actions de validation et de suppression
    if (isset($_POST['action_reg'])) {
      $id = $_POST['id'];
      $action = $_POST['action_reg'];

      if ($action === 'validate') {
        // Effectuer des opérations de validation ici
        // Selecting the instructor ID from the courses table
        $sql = "SELECT c.instructor_id,di.course_id
FROM demande_ins di
INNER JOIN courses c ON di.course_id = c.course_id
WHERE di.id_ins = $id";
        $result = mysqli_query($conn, $sql);


        if (mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);

          // Vérifier si les clés d'array existent avant de les utiliser
          $nom_ins = isset($row['nom_ins']) ? $row['nom_ins'] : '';
          $prenom_ins = isset($row['prenom_ins']) ? $row['prenom_ins'] : '';
          $date_naissance_ins = isset($row['date_naissance_ins']) ? $row['date_naissance_ins'] : '';
          $numero_telephone_ins = isset($row['numero_telephone_ins']) ? $row['numero_telephone_ins'] : '';
          $email_ins = isset($row['email_ins']) ? $row['email_ins'] : '';
          $id_instructor = isset($row['instructor_id']) ? $row['instructor_id'] : '';
          $course_id = isset($row['course_id']) ? $row['course_id'] : '';

          // Insérer les informations dans la table des étudiants
          $sql = "INSERT INTO etudiants (nom_etu, prenom_etu, date_naissance_etu, numero_telephone_etu, email_etu) 
                VALUES ('$nom_ins', '$prenom_ins', '$date_naissance_ins', '$numero_telephone_ins', '$email_ins')";

          if (mysqli_query($conn, $sql)) {
            $studentId = mysqli_insert_id($conn); // Obtenir l'ID de l'étudiant inséré récemment

            // Insérer le lien entre l'étudiant, l'instructeur et le cours dans la table etudiant_speciality
            $sqlCheck = "SELECT * FROM instructors WHERE instructor_id = $id_instructor";
            $resultCheck = mysqli_query($conn, $sqlCheck);

            if (mysqli_num_rows($resultCheck) > 0) {
              $sql = "INSERT INTO etudiant_speciality (id_etudiant, id_instructor, id_course) 
                VALUES ('$studentId', '$id_instructor', '$course_id')";

              if (mysqli_query($conn, $sql)) {
                // Inscription et lien créés avec succès
                echo "Registration validated and added to students and etudiant_speciality.";
              } else {
                // Erreur lors de l'insertion du lien
                echo "Error inserting link in etudiant_speciality: " . mysqli_error($conn);
              }
            } else {
              // The $id_instructor does not exist in the instructors table
              echo "Invalid instructor ID. Registration removed from requests.";
            }
          } else {
            // Erreur lors de l'insertion de l'étudiant
            echo "Error inserting student: " . mysqli_error($conn);
          }

          // Supprimer l'inscription de la table demande_ins
          $sql = "DELETE FROM demande_ins WHERE id_ins = $id";

          if (mysqli_query($conn, $sql)) {
            echo "Registration removed from requests.";
          } else {
            echo "Error removing registration: " . mysqli_error($conn);
          }
        } else {
          echo "Registration not found.";
        }
      } elseif ($action === 'delete') {
        // Effectuer des opérations de suppression ici
        $sql = "DELETE FROM demande_ins WHERE id_ins = $id";

        if (mysqli_query($conn, $sql)) {
          echo "Registration deleted.";
        } else {
          echo "Error deleting registration: " . mysqli_error($conn);
        }
      }
    }

    // Récupération des demandes d'inscription avec les noms de cours et instructeurs
    $sql = "SELECT di.id_ins, di.nom_ins, di.prenom_ins, di.date_naissance_ins, di.numero_telephone_ins, di.email_ins, c.course_name, i.instructor_name, es.date_debut, es.date_fin
            FROM demande_ins di
            LEFT JOIN etudiant_speciality es ON di.id_ins = es.id_etudiant
            LEFT JOIN courses c ON di.course_id = c.course_id
            LEFT JOIN instructors i ON c.instructor_id = i.instructor_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo '<table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Date de Naissance</th>
                  <th>Numéro de Téléphone</th>
                  <th>Email</th>
                  <th>Cours</th>
                  <th>Instructeur</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>';

      while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row["id_ins"] . '</td>';
        echo '<td>' . $row["nom_ins"] . '</td>';
        echo '<td>' . $row["prenom_ins"] . '</td>';
        echo '<td>' . $row["date_naissance_ins"] . '</td>';
        echo '<td>' . $row["numero_telephone_ins"] . '</td>';
        echo '<td>' . $row["email_ins"] . '</td>';
        echo '<td>' . $row["course_name"] . '</td>';
        echo '<td>' . $row["instructor_name"] . '</td>';
        echo '<td>
        <div class="btn-group" role="group">
          <form method="POST" action="">
            <input type="hidden" name="id" value="' . $row["id_ins"] . '">
            <input type="hidden" name="action_reg" value="validate">
            <button type="submit" class="btn btn-success me-2"><i class="fas fa-check"></i></button>
          </form>
          <form method="POST" action="">
            <input type="hidden" name="id" value="' . $row["id_ins"] . '">
            <input type="hidden" name="action_reg" value="delete">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </td>';

        echo '</tr>';
      }

      echo '</tbody></table>';
    } else {
      echo '<p>No registrations found.</p>';
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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../../asstes/bootstrap/js/bootstrap.min.js"></script>
        <script>
          $(document).ready(function() {
            // Gérer la navigation en fonction des clics sur les liens du menu
            $('.nav-link').click(function() {
              var target = $(this).data('target');
              $('.content').hide();
              $('#' + target).show();
            });

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