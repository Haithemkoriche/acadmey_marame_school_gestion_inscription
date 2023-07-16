<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../asstes/bootstrap/css/bootstrap.min.css">
  <style>
    /* Ajoutez votre propre style CSS ici */
    .sidebar {
      height: 100vh;
      background-color: #f8f9fa;
    }

    .content {
      display: none;
    }
  </style>
  <title>Dashboard</title>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">Dashboard</a>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <span class="nav-link">Logged in as Admin</span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">View Site</a>
        </li>
        <li class="nav-item">
          <button id="btn-logout" class="btn btn-link nav-link">Logout</button>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 sidebar">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="registrations">Registrations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="contacts">Contacts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="students">Students</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="courses">Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="instructors">Instructors</a>
          </li>
          <!-- Ajoutez d'autres liens pour les fonctions d'administration -->
        </ul>
      </div>

      <!-- Content -->
      <div class="col-md-9">
        <!-- Dashboard -->
        <div id="dashboard" class="content">
          <h1>Welcome to the Dashboard</h1>
          <p>Here you can manage various administrative tasks.</p>
        </div>

        <!-- Registrations -->
        <div id="registrations" class="content">
          <h1>Registrations</h1>

          <?php
          // Connexion à la base de données
          $conn = mysqli_connect("localhost", "root", "", "ams");

          // Vérification de la connexion
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }

          // Vérification des actions de validation et de suppression
          if (isset($_POST['action_reg'])) {
            $id = $_POST['id'];
            $action = $_POST['action_reg'];

            if ($action === 'validate') {
              // Effectuer des opérations de validation ici
              $sql = "SELECT * FROM demande_ins WHERE id_ins = $id";
              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // Insérer les informations dans la table des étudiants
                $sql = "INSERT INTO etudiants (nom_etu, prenom_etu, date_naissance_etu, numero_telephone_etu, email_etu) 
                VALUES ('{$row['nom_ins']}', '{$row['prenom_ins']}', '{$row['date_naissance_ins']}', '{$row['numero_telephone_ins']}', '{$row['email_ins']}')";

                if (mysqli_query($conn, $sql)) {
                  // Supprimer la demande d'inscription
                  $sql = "DELETE FROM demande_ins WHERE id_ins = $id";

                  if (mysqli_query($conn, $sql)) {
                    echo "Registration validated and added to students.";
                  } else {
                    echo "Error deleting registration: " . mysqli_error($conn);
                  }
                } else {
                  echo "Error inserting student: " . mysqli_error($conn);
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

          // Récupération des demandes d'inscription
          $sql = "SELECT * FROM demande_ins";
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
              echo '<td>
              <form method="POST" action="">
                <input type="hidden" name="id" value="' . $row["id_ins"] . '">
                <input type="hidden" name="actioni_reg" value="validate">
                <button type="submit" class="btn btn-success mr-2">Validate</button>
              </form>
              <form method="POST" action="">
                <input type="hidden" name="id" value="' . $row["id_ins"] . '">
                <input type="hidden" name="action_reg" value="delete">
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </td>';
              echo '</tr>';
            }

            echo '</tbody></table>';
          } else {
            echo '<p>No registrations found.</p>';
          }

          // Fermeture de la connexion à la base de données
          mysqli_close($conn);
          ?>
        </div>


        <!-- Contacts -->
        <div id="contacts" class="content">
          <h1>Contacts</h1>

          <?php
          // Connexion à la base de données
          $conn = mysqli_connect("localhost", "root", "", "ams");

          // Vérification de la connexion
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }

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
              <a href="?delete_ms=' . $row["id_contact"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this message?\')">Delete</a>
            </td>';
              echo '</tr>';
            }

            echo '</tbody></table>';
          } else {
            echo '<p>No contact messages found.</p>';
          }

          // Fermeture de la connexion à la base de données
          mysqli_close($conn);
          ?>
        </div>


        <!-- Students -->
        <div id="students" class="content">
          <h1>Students</h1>
          <p>Content for Students page goes here.</p>
        </div>

        <!-- Courses -->
        <div id="courses" class="content">
          <h1>Courses</h1>
          <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
          <?php
          // Connexion à la base de données
          $conn = mysqli_connect("localhost", "root", "", "ams");

          // Vérification de la connexion
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }

          // Vérification si un cours a été ajouté ou modifié
          if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'add') {
              // Récupération des données du formulaire d'ajout
              $courseName = $_POST['course_name'];
              $courseDescription = $_POST['course_description'];
              $instructorId = $_POST['instructor_id'];

              // Vérification de l'image
              if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
                $targetDirectory = "upload/"; // Répertoire de destination des images
                $targetFile = $targetDirectory . basename($_FILES['course_image']['name']);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Vérification du type de fichier
                $allowedExtensions = array("jpg", "jpeg", "png");
                if (in_array($imageFileType, $allowedExtensions)) {
                  // Déplacement du fichier vers le répertoire de destination
                  if (move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
                    // Insertion du nouveau cours dans la base de données avec le chemin de l'image
                    $insertSql = "INSERT INTO courses (course_name, course_description, course_image, instructor_id)
                          VALUES ('$courseName', '$courseDescription', '$targetFile', $instructorId)";
                    if (mysqli_query($conn, $insertSql)) {
                      echo '<div class="alert alert-success" role="alert">Course added successfully.</div>';
                    } else {
                      echo '<div class="alert alert-danger" role="alert">Error adding course: ' . mysqli_error($conn) . '</div>';
                    }
                  } else {
                    echo '<div class="alert alert-danger" role="alert">Error uploading image.</div>';
                  }
                } else {
                  echo '<div class="alert alert-danger" role="alert">Invalid image format. Allowed formats: JPG, JPEG, PNG.</div>';
                }
              } else {
                echo '<div class="alert alert-danger" role="alert">Error uploading image.</div>';
              }
            } elseif ($action === 'update') {
              // Récupération des données du formulaire de mise à jour
              $courseId = $_POST['course_id'];
              $courseName = $_POST['course_name'];
              $courseDescription = $_POST['course_description'];
              $instructorId = $_POST['instructor_id'];

              // Vérification si une nouvelle image a été sélectionnée
              $updateImage = false;
              if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
                $targetDirectory = "upload/"; // Répertoire de destination des images
                $targetFile = $targetDirectory . basename($_FILES['course_image']['name']);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Vérification du type de fichier
                $allowedExtensions = array("jpg", "jpeg", "png");
                if (in_array($imageFileType, $allowedExtensions)) {
                  // Déplacement du fichier vers le répertoire de destination
                  if (move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
                    $updateImage = true;
                  } else {
                    echo '<div class="alert alert-danger" role="alert">Error uploading image.</div>';
                  }
                } else {
                  echo '<div class="alert alert-danger" role="alert">Invalid image format. Allowed formats: JPG, JPEG, PNG.</div>';
                }
              }

              // Mise à jour du cours dans la base de données
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
                echo '<div class="alert alert-success" role="alert">Course updated successfully.</div>';
              } else {
                echo '<div class="alert alert-danger" role="alert">Error updating course: ' . mysqli_error($conn) . '</div>';
              }
            }
          }

          // Vérification si un cours a été supprimé
          if (isset($_GET['delete'])) {
            $deleteId = $_GET['delete'];

            // Suppression du cours de la base de données
            $deleteSql_c = "DELETE FROM courses WHERE course_id = $deleteId";
            if (mysqli_query($conn, $deleteSql_c)) {
              echo '<div class="alert alert-success" role="alert">Course deleted successfully.</div>';
            } else {
              echo '<div class="alert alert-danger" role="alert">Error deleting course: ' . mysqli_error($conn) . '</div>';
            }
          }

          // Récupération des cours avec les informations sur les instructeurs associés
          $selectSql = "SELECT courses.course_id, courses.course_name, courses.course_description, courses.course_image, instructors.instructor_name
                FROM courses
                LEFT JOIN instructors ON courses.instructor_id = instructors.instructor_id";
          $result = mysqli_query($conn, $selectSql);

          if (mysqli_num_rows($result) > 0) {
            echo '<table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Course Name</th>
              <th>Description</th>
              <th>Image</th>
              <th>Instructor</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr>';
              echo '<td>' . $row["course_id"] . '</td>';
              echo '<td>' . $row["course_name"] . '</td>';
              echo '<td>' . $row["course_description"] . '</td>';
              echo '<td><img src="' . $row["course_image"] . '" alt="Course Image" height="50"></td>';
              echo '<td>' . $row["instructor_name"] . '</td>';
              echo '<td>
              <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCourseModal-' . $row["course_id"] . '">Edit</a>
              <a href="?delete=' . $row["course_id"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this course?\')">Delete</a>
            </td>';
              echo '</tr>';

              // Modal pour la modification du cours
              echo '<div class="modal fade" id="editCourseModal-' . $row["course_id"] . '" tabindex="-1" aria-labelledby="editCourseModalLabel-' . $row["course_id"] . '" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editCourseModalLabel-' . $row["course_id"] . '">Edit Course</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="action" value="update">
                      <input type="hidden" name="course_id" value="' . $row["course_id"] . '">
                      <div class="form-group">
                        <label for="editCourseName">Course Name:</label>
                        <input type="text" class="form-control" id="editCourseName" name="course_name" value="' . $row["course_name"] . '" required>
                      </div>
                      <div class="form-group">
                        <label for="editCourseDescription">Course Description:</label>
                        <textarea class="form-control" id="editCourseDescription" name="course_description" rows="3" required>' . $row["course_description"] . '</textarea>
                      </div>
                      <div class="form-group">
                        <label for="editInstructorId">Instructor:</label>
                        <select class="form-control" id="editInstructorId" name="instructor_id" required>
                          <option value="">Select an Instructor</option>';

              // Récupération des instructeurs
              $instructorsSql = "SELECT * FROM instructors";
              $instructorsResult = mysqli_query($conn, $instructorsSql);

              while ($instructorRow = mysqli_fetch_assoc($instructorsResult)) {
                $selected = ($instructorRow["instructor_id"] == $row["instructor_id"]) ? 'selected' : '';
                echo '<option value="' . $instructorRow["instructor_id"] . '" ' . $selected . '>' . $instructorRow["instructor_name"] . '</option>';
              }

              echo '            </select>
                      </div>
                      <div class="form-group">
                        <label for="editCourseImage">Course Image:</label>
                        <input type="file" class="form-control-file" id="editCourseImage" name="course_image">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
            }

            echo '</tbody></table>';
          } else {
            echo '<p>No courses found.</p>';
          }

          // Fermeture de la connexion à la base de données
          mysqli_close($conn);
          ?>
        </div>

        <!-- Instructors -->
        <div id="instructors" class="content">
          <h1>Instructors</h1>

          <!-- Add Instructor Button -->
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInstructorModal">Add Instructor</button>

          <?php
          // Connexion à la base de données
          $conn = mysqli_connect("localhost", "root", "", "ams");

          // Vérification de la connexion
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
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

              // Insertion du nouvel instructeur dans la base de données
              $insertSql = "INSERT INTO instructors (instructor_name, instructor_specialty, instructor_email, instructor_phone)
      VALUES ('$instructorName', '$instructorSpecialty', '$instructorEmail', '$instructorPhone')";
              if (mysqli_query($conn, $insertSql)) {
                echo '<div class="alert alert-success" role="alert">Instructor added successfully.</div>';
              } else {
                echo '<div class="alert alert-danger" role="alert">Error adding instructor: ' . mysqli_error($conn) . '</div>';
              }
            } elseif ($action === 'update_ins') {
              // Récupération des données du formulaire de mise à jour
              $instructorId = $_POST['instructor_id'];
              $instructorName = $_POST['instructor_name'];
              $instructorSpecialty = $_POST['instructor_specialty'];
              $instructorEmail = $_POST['instructor_email'];
              $instructorPhone = $_POST['instructor_phone'];

              // Mise à jour de l'instructeur dans la base de données
              $updateSql_ins = "UPDATE instructors 
      SET instructor_name = '$instructorName', instructor_specialty = '$instructorSpecialty', 
      instructor_email = '$instructorEmail', instructor_phone = '$instructorPhone'
      WHERE instructor_id = $instructorId";
              if (mysqli_query($conn, $updateSql_ins)) {
                echo '<div class="alert alert-success" role="alert">Instructor updated successfully.</div>';
              } else {
                echo '<div class="alert alert-danger" role="alert">Error updating instructor: ' . mysqli_error($conn) . '</div>';
              }
            }
          }

          // Vérification si un instructeur a été supprimé
          if (isset($_GET['delete_ins'])) {
            $deleteId = $_GET['delete_ins'];

            // Suppression de l'instructeur de la base de données
            $deleteSql_ins = "DELETE FROM instructors WHERE instructor_id = $deleteId";
            if (mysqli_query($conn, $deleteSql_ins)) {
              echo '<div class="alert alert-success" role="alert">Instructor deleted successfully.</div>';
            } else {
              echo '<div class="alert alert-danger" role="alert">Error deleting instructor: ' . mysqli_error($conn) . '</div>';
            }
          }

          // Récupération des instructeurs
          $selectSql = "SELECT * FROM instructors";
          $result = mysqli_query($conn, $selectSql);

          if (mysqli_num_rows($result) > 0) {
            echo '<table class="table">
    <thead>
      <tr>
        <th>Instructor ID</th>
        <th>Name</th>
        <th>Specialty</th>
        <th>Email</th>
        <th>Phone</th>
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
              echo '<td>
          <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editInstructorModal-' . $row["instructor_id"] . '">Edit</a>
          <a href="?delete_ins=' . $row["instructor_id"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this instructor?\')">Delete</a>
        </td>';
              echo '</tr>';

              // Modal pour la modification de l'instructeur
              echo '<div class="modal fade" id="editInstructorModal-' . $row["instructor_id"] . '" tabindex="-1" aria-labelledby="editInstructorModalLabel-' . $row["instructor_id"] . '" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editInstructorModalLabel-' . $row["instructor_id"] . '">Edit Instructor</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <input type="hidden" name="action" value="update_ins">
              <input type="hidden" name="instructor_id" value="' . $row["instructor_id"] . '">
              <div class="form-group">
                <label for="editInstructorName">Name:</label>
                <input type="text" class="form-control" id="editInstructorName" name="instructor_name" value="' . $row["instructor_name"] . '" required>
              </div>
              <div class="form-group">
                <label for="editInstructorSpecialty">Specialty:</label>
                <input type="text" class="form-control" id="editInstructorSpecialty" name="instructor_specialty" value="' . $row["instructor_specialty"] . '" required>
              </div>
              <div class="form-group">
                <label for="editInstructorEmail">Email:</label>
                <input type="email" class="form-control" id="editInstructorEmail" name="instructor_email" value="' . $row["instructor_email"] . '" required>
              </div>
              <div class="form-group">
                <label for="editInstructorPhone">Phone:</label>
                <input type="text" class="form-control" id="editInstructorPhone" name="instructor_phone" value="' . $row["instructor_phone"] . '" required>
              </div>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>';
            }

            echo '</tbody></table>';
          } else {
            echo '<p>No instructors found.</p>';
          }

          // Fermeture de la connexion à la base de données
          mysqli_close($conn);
          ?>
        </div>

        <!-- Add Instructor Modal -->
        <div class="modal fade" id="addInstructorModal" tabindex="-1" aria-labelledby="addInstructorModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addInstructorModalLabel">Add Instructor</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                  <input type="hidden" name="action" value="add">
                  <div class="form-group">
                    <label for="instructorName">Name:</label>
                    <input type="text" class="form-control" id="instructorName" name="instructor_name" required>
                  </div>
                  <div class="form-group">
                    <label for="instructorSpecialty">Specialty:</label>
                    <input type="text" class="form-control" id="instructorSpecialty" name="instructor_specialty" required>
                  </div>
                  <div class="form-group">
                    <label for="instructorEmail">Email:</label>
                    <input type="email" class="form-control" id="instructorEmail" name="instructor_email" required>
                  </div>
                  <div class="form-group">
                    <label for="instructorPhone">Phone:</label>
                    <input type="text" class="form-control" id="instructorPhone" name="instructor_phone" required>
                  </div>
                  <button type="submit" class="btn btn-primary">Add Instructor</button>
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
        <script src="../asstes/bootstrap/js/bootstrap.min.js"></script>
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