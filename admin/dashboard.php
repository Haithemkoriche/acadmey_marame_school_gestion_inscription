<?php require_once "../config/bdd.php"; ?>
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
          $studentId = mysqli_insert_id($conn); // Get the last inserted student ID

          // Insert the link between student, instructor, and course in the etudiant_speciality table
          $sql = "INSERT INTO etudiant_speciality (id_etudiant, id_instructor, id_course, date_debut, date_fin) 
                  VALUES ('$studentId', '{$row['id_instructor']}', '{$row['course_id']}', '{$row['date_debut']}', '{$row['date_fin']}')";

          if (mysqli_query($conn, $sql)) {
            // Registration and link created successfully
            echo "Registration validated and added to students.";
          } else {
            // Error inserting the link
            echo "Error inserting link: " . mysqli_error($conn);
          }
        } else {
          // Error inserting student
          echo "Error inserting student: " . mysqli_error($conn);
        }

        // Remove the registration from demande_ins table
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
          INNER JOIN courses c ON di.course_id = c.course_id
          INNER JOIN etudiant_speciality es ON di.id_ins = es.id_etudiant
          INNER JOIN instructors i ON c.instructor_id = i.instructor_id";
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
                <th>Date de Début</th>
                <th>Date de Fin</th>
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
      echo '<td>' . $row["date_debut"] . '</td>';
      echo '<td>' . $row["date_fin"] . '</td>';
      echo '<td>
            <form method="POST" action="">
              <input type="hidden" name="id" value="' . $row["id_ins"] . '">
              <input type="hidden" name="action_reg" value="validate">
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
  ?>
</div>





        <!-- Contacts -->
        <div id="contacts" class="content">
          <h1>Contacts</h1>

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
              <a href="?delete_ms=' . $row["id_contact"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this message?\')">Delete</a>
            </td>';
              echo '</tr>';
            }

            echo '</tbody></table>';
          } else {
            echo '<p>No contact messages found.</p>';
          }


          ?>
        </div>


        <!-- Students -->
        <div id="students" class="content">
          <h1>Students</h1>

          <!-- Add Student Button -->
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add Student</button>

          <?php

          // Vérification si un étudiant a été ajouté ou modifié
          if (isset($_POST['action_stu'])) {
            $action = $_POST['action_stu'];

            if ($action === 'add_stu') {
              // Récupération des données du formulaire d'ajout
              $studentName = $_POST['student_name'];
              $studentSpeciality = $_POST['student_speciality'];
              $studentInstructor = $_POST['student_instructor'];
              $studentEmail = $_POST['student_email'];
              $studentPhone = $_POST['student_phone'];

              // Insertion du nouvel étudiant dans la table "etudiants"
              $insertStudentSql = "INSERT INTO etudiants (nom_etu, prenom_etu, date_naissance_etu, numero_telephone_etu, email_etu)
                VALUES ('$studentName', '', '', '$studentPhone', '$studentEmail')";
              if (mysqli_query($conn, $insertStudentSql)) {
                $studentId = mysqli_insert_id($conn);

                // Insertion des informations supplémentaires dans la table "etudiant_speciality"
                $insertSpecialitySql = "INSERT INTO etudiant_speciality (id_etudiant, id_instructor, id_course, date_debut, date_fin)
                  VALUES ($studentId, $studentInstructor, 0, '', '')";
                if (mysqli_query($conn, $insertSpecialitySql)) {
                  echo '<div class="alert alert-success" role="alert">Student added successfully.</div>';
                } else {
                  echo '<div class="alert alert-danger" role="alert">Error adding student: ' . mysqli_error($conn) . '</div>';
                }
              } else {
                echo '<div class="alert alert-danger" role="alert">Error adding student: ' . mysqli_error($conn) . '</div>';
              }
            } elseif ($action === 'update') {
              // Récupération des données du formulaire de mise à jour
              $studentId = $_POST['student_id'];
              $studentName = $_POST['student_name'];
              $studentSpeciality = $_POST['student_speciality'];
              $studentInstructor = $_POST['student_instructor'];
              $studentEmail = $_POST['student_email'];
              $studentPhone = $_POST['student_phone'];

              // Mise à jour du nom de l'étudiant dans la table "etudiants"
              $updateStudentSql = "UPDATE etudiants 
                SET nom_etu = '$studentName', numero_telephone_etu = '$studentPhone', email_etu = '$studentEmail'
                WHERE id_etudiant = $studentId";
              if (mysqli_query($conn, $updateStudentSql)) {

                // Mise à jour des informations supplémentaires dans la table "etudiant_speciality"
                $updateSpecialitySql = "UPDATE etudiant_speciality 
                  SET id_instructor = $studentInstructor
                  WHERE id_etudiant = $studentId";
                if (mysqli_query($conn, $updateSpecialitySql)) {
                  echo '<div class="alert alert-success" role="alert">Student updated successfully.</div>';
                } else {
                  echo '<div class="alert alert-danger" role="alert">Error updating student: ' . mysqli_error($conn) . '</div>';
                }
              } else {
                echo '<div class="alert alert-danger" role="alert">Error updating student: ' . mysqli_error($conn) . '</div>';
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
                echo '<div class="alert alert-success" role="alert">Student deleted successfully.</div>';
              } else {
                echo '<div class="alert alert-danger" role="alert">Error deleting student: ' . mysqli_error($conn) . '</div>';
              }
            } else {
              echo '<div class="alert alert-danger" role="alert">Error deleting student: ' . mysqli_error($conn) . '</div>';
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
                  <th>Student ID</th>
                  <th>Name</th>
                  <th>Date of Birth</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Instructor</th>
                  <th>Course</th>
                  <th>Start Date</th>
                  <th>End Date</th>
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
              echo '<td>' . $row["date_fin"] . '</td>';

              // Actions
              echo '<td>
                <button class="btn btn-sm btn-primary edit-student" data-bs-toggle="modal" data-bs-target="#editStudentModal" data-student-id="' . $row["id_etudiant"] . '">Edit</button>
                <a href="students.php?delete_stu=' . $row["id_etudiant"] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this student?\')">Delete</a>
              </td>';

              echo '</tr>';
            }

            echo '</tbody>
            </table>';
          } else {
            echo '<div class="alert alert-info" role="alert">No students found.</div>';
          }
          // Récupérer les cours depuis la base de données
          $coursesSql = "SELECT course_id, course_name FROM courses";
          $coursesResult = mysqli_query($conn, $coursesSql);

          ?>
          <!-- Add Student Modal -->
          <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="" method="POST">
                    <input type="hidden" name="action_stu" value="add_stu">

                    <div class="mb-3">
                      <label for="student_name" class="form-label">Name</label>
                      <input type="text" class="form-control" id="student_name" name="student_name" required>
                    </div>

                    <div class="mb-3">
                      <label for="student_speciality" class="form-label">Speciality</label>
                      <select class="form-select" id="student_speciality" name="student_speciality" required>
                        <?php
                        // Afficher les options de sélection des cours
                        while ($courseRow = mysqli_fetch_assoc($coursesResult)) {
                          echo '<option value="' . $courseRow["course_id"] . '">' . $courseRow["course_name"] . '</option>';
                        }
                        ?>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="student_instructor" class="form-label">Instructor</label>
                      <select class="form-select" id="student_instructor" name="student_instructor" required>
                        <?php
                        // Récupérer les instructeurs depuis la base de données
                        $instructorSql = "SELECT instructor_id, instructor_name FROM instructors";
                        $instructorResult = mysqli_query($conn, $instructorSql);

                        while ($instructorRow = mysqli_fetch_assoc($instructorResult)) {
                          echo '<option value="' . $instructorRow["instructor_id"] . '">' . $instructorRow["instructor_name"] . '</option>';
                        }
                        ?>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="student_email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="student_email" name="student_email" required>
                    </div>

                    <div class="mb-3">
                      <label for="student_phone" class="form-label">Phone</label>
                      <input type="text" class="form-control" id="student_phone" name="student_phone" required>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Courses -->
        <div id="courses" class="content">
          <h1>Courses</h1>

          <!-- Add Course Button -->
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>

          <?php
          // Check if a course has been added or updated
          if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'add_crs') {
              // Get data from the add form
              $courseName = $_POST['course_name'];
              $courseDescription = $_POST['course_description'];
              $instructorId = $_POST['instructor_id'];

              // Check if an image has been uploaded
              if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
                $targetDirectory = "upload/"; // Destination directory for images
                $targetFile = $targetDirectory . basename($_FILES['course_image']['name']);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check the file type
                $allowedExtensions = array("jpg", "jpeg", "png");
                if (in_array($imageFileType, $allowedExtensions)) {
                  // Move the file to the destination directory
                  if (move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
                    // Insert the new course into the database with the image path
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
              // Get data from the update form
              $courseId = $_POST['course_id'];
              $courseName = $_POST['course_name'];
              $courseDescription = $_POST['course_description'];
              $instructorId = $_POST['instructor_id'];

              // Check if a new image has been selected
              $updateImage = false;
              if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
                $targetDirectory = "upload/"; // Destination directory for images
                $targetFile = $targetDirectory . basename($_FILES['course_image']['name']);
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check the file type
                $allowedExtensions = array("jpg", "jpeg", "png");
                if (in_array($imageFileType, $allowedExtensions)) {
                  // Move the file to the destination directory
                  if (move_uploaded_file($_FILES['course_image']['tmp_name'], $targetFile)) {
                    $updateImage = true;
                  } else {
                    echo '<div class="alert alert-danger" role="alert">Error uploading image.</div>';
                  }
                } else {
                  echo '<div class="alert alert-danger" role="alert">Invalid image format. Allowed formats: JPG, JPEG, PNG.</div>';
                }
              }

              // Update the course in the database
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

          // Check if a course has been deleted
          if (isset($_GET['delete'])) {
            $deleteId = $_GET['delete'];

            // Delete the course from the database
            $deleteSql = "DELETE FROM courses WHERE course_id = $deleteId";
            if (mysqli_query($conn, $deleteSql)) {
              echo '<div class="alert alert-success" role="alert">Course deleted successfully.</div>';
            } else {
              echo '<div class="alert alert-danger" role="alert">Error deleting course: ' . mysqli_error($conn) . '</div>';
            }
          }

          // Retrieve courses with associated instructor information
          $selectSql = "SELECT courses.course_id, courses.course_name, courses.course_description, courses.course_image, instructors.instructor_name
                FROM courses
                LEFT JOIN instructors ON courses.instructor_id = instructors.instructor_id";
          $result = mysqli_query($conn, $selectSql);

          if (mysqli_num_rows($result) > 0) {
            echo '<table class="table">
            <thead>
              <tr>
                <th>Course ID</th>
                <th>Name</th>
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
              <button class="btn btn-sm btn-primary edit-course" data-bs-toggle="modal" data-bs-target="#editCourseModal" data-course-id="' . $row["course_id"] . '">Edit</button>
              <a href="?delete=' . $row["course_id"] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this course?\')">Delete</a>
            </td>';
              echo '</tr>';
            }

            echo '</tbody></table>';
          } else {
            echo '<div class="alert alert-info" role="alert">No courses found.</div>';
          }
          ?>

        </div>

        <!-- Add Course Modal -->
        <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="action" value="add_crs">

                  <div class="mb-3">
                    <label for="course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required>
                  </div>

                  <div class="mb-3">
                    <label for="course_description" class="form-label">Course Description</label>
                    <textarea class="form-control" id="course_description" name="course_description" required></textarea>
                  </div>

                  <div class="mb-3">
                    <label for="course_image" class="form-label">Course Image</label>
                    <input type="file" class="form-control" id="course_image" name="course_image" accept="image/jpeg,image/png" required>
                  </div>

                  <div class="mb-3">
                    <label for="instructor_id" class="form-label">Instructor</label>
                    <select class="form-select" id="instructor_id" name="instructor_id" required>
                      <?php
                      // Retrieve the instructors
                      $instructorSql = "SELECT instructor_id, instructor_name FROM instructors";
                      $instructorResult = mysqli_query($conn, $instructorSql);

                      while ($instructorRow = mysqli_fetch_assoc($instructorResult)) {
                        echo '<option value="' . $instructorRow["instructor_id"] . '">' . $instructorRow["instructor_name"] . '</option>';
                      }
                      ?>
                    </select>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Course Modal -->
        <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="action" value="update">

                  <input type="hidden" id="edit_course_id" name="course_id">

                  <div class="mb-3">
                    <label for="edit_course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="edit_course_name" name="course_name" required>
                  </div>

                  <div class="mb-3">
                    <label for="edit_course_description" class="form-label">Course Description</label>
                    <textarea class="form-control" id="edit_course_description" name="course_description" required></textarea>
                  </div>

                  <div class="mb-3">
                    <label for="edit_course_image" class="form-label">Course Image</label>
                    <input type="file" class="form-control" id="edit_course_image" name="course_image" accept="image/jpeg,image/png">
                  </div>

                  <div class="mb-3">
                    <label for="edit_instructor_id" class="form-label">Instructor</label>
                    <select class="form-select" id="edit_instructor_id" name="instructor_id" required>
                      <?php
                      // Retrieve the instructors
                      $instructorSql = "SELECT instructor_id, instructor_name FROM instructors";
                      $instructorResult = mysqli_query($conn, $instructorSql);

                      while ($instructorRow = mysqli_fetch_assoc($instructorResult)) {
                        echo '<option value="' . $instructorRow["instructor_id"] . '">' . $instructorRow["instructor_name"] . '</option>';
                      }
                      ?>
                    </select>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Course</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
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
          echo '<div class="alert alert-success" role="alert">Instructor image updated successfully.</div>';
        } else {
          echo '<div class="alert alert-danger" role="alert">Error updating instructor image: ' . mysqli_error($conn) . '</div>';
        }
      }

      // Mise à jour de l'instructeur dans la base de données
      $updateSql_ins = "UPDATE instructors 
                        SET instructor_name = '$instructorName', instructor_specialty = '$instructorSpecialty', 
                            instructor_email = '$instructorEmail', instructor_phone = '$instructorPhone',
                            instructor_description = '$instructorDescription'
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
      echo '<td><img src="' . $row["instructor_image"] . '" alt="Instructor Image" class="img-fluid" /></td>';
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
                    <form action="" method="post" enctype="multipart/form-data">
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
                      <div class="form-group">
                        <label for="editInstructorDescription">Description:</label>
                        <textarea class="form-control" id="editInstructorDescription" name="instructor_description" rows="3">' . $row["instructor_description"] . '</textarea>
                      </div>
                      <div class="form-group">
                        <label for="editInstructorImage">Image:</label>
                        <input type="file" class="form-control" id="editInstructorImage" name="instructor_image">
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
        <form action="" method="post" enctype="multipart/form-data">
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
          <div class="form-group">
            <label for="instructorDescription">Description:</label>
            <textarea class="form-control" id="instructorDescription" name="instructor_description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="instructorImage">Image:</label>
            <input type="file" class="form-control" id="instructorImage" name="instructor_image" required>
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
<?php
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>