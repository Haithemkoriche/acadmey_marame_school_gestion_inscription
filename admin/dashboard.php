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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.nav-link').click(function() {
        var target = $(this).data('target');

        // Cacher tous les contenus
        $('.content').hide();

        // Afficher le contenu cible
        $('#' + target).show();
      });
      $('#btn-logout').click(function() {
        // Logique de déconnexion ici
        alert("Vous avez été déconnecté !");
        // Redirection vers la page de connexion ou autre action nécessaire
      });
    });
  </script>
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
  if (isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

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
                <input type="hidden" name="action" value="validate">
                <button type="submit" class="btn btn-success mr-2">Validate</button>
              </form>
              <form method="POST" action="">
                <input type="hidden" name="id" value="' . $row["id_ins"] . '">
                <input type="hidden" name="action" value="delete">
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
  if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];

    // Suppression du message de la base de données
    $deleteSql = "DELETE FROM contact_messages WHERE id_contact = $deleteId";
    if (mysqli_query($conn, $deleteSql)) {
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
              <a href="?delete=' . $row["id_contact"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this message?\')">Delete</a>
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
          <p>Content for Courses page goes here.</p>
        </div>

        <!-- Instructors -->
        <div id="instructors" class="content">
          <h1>Instructors</h1>
          <p>Content for Instructors page goes here.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="../asstes/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
