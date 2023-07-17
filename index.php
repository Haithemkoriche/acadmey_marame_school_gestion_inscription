<?php include "config/bdd.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="asstes/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="asstes/fonts/css/all.min.css">

  <title>Academy Maram School</title>
</head>

<body>
<header>
  <!-- Navbar Bootstrap -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#"><img src="img/logo.png" alt="" srcset="" width="100px" height="82px"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item me-4">
            <a class="nav-link" href="#about">About</a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="#courses">Courses</a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="#team">Instructors</a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link" href="#contact">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>


  <section class="jumbotron text-center">
    <div class="row">
      <div class="col-md-12">
        <div class="position-relative">
          <img src="img/352499042_3260409140926278_8767796183506052124_n.jpg" class="img-fluid" alt="Welcome Image" style="object-fit: cover; filter: brightness(70%); width: 100vw; height: 100vh;">
          <!-- <div class="container"> -->
          <div class="position-absolute top-50 start-50 translate-middle">
              <h1 class="text-white">Welcome to Academy Maram School</h1>
              <p class="lead text-white">Discover our wide range of courses and expert instructors.</p>
              <a href="#about" class="btn btn-primary">Learn More</a>
            </div>
        <!-- </div> -->
      </div>
    </div>
  </div>
</section>


<section id="about">
  <div class="container mt-5 mb-5">
    <div class="row">
      <div class="col-md-6 d-flex align-items-center">
        <div class="text-center">
          <h2>A Propos</h2>
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus
            convallis suscipit tellus id aliquet.
          </p>
        </div>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <img src="img/352499042_3260409140926278_8767796183506052124_n.jpg" alt="About Image" class="img-fluid" style="max-width: 100%; max-height: auto;">
      </div>
    </div>
  </div>
</section>


  <section id="courses" class="bg-light pt-5 pb-5 mb-4">
    <div class="container">
      <h2 class="text-center pb-3">Our Courses</h2>
      <div class="row">
        <?php
        // Récupérer les cours depuis la base de données
        $coursesSql = "SELECT course_id, course_name, course_description, course_image FROM courses";
        $coursesResult = mysqli_query($conn, $coursesSql);

        while ($courseRow = mysqli_fetch_assoc($coursesResult)) {
          $courseId = $courseRow['course_id'];
          $courseName = $courseRow['course_name'];
          $courseDescription = $courseRow['course_description'];
          $courseImage = $courseRow['course_image'];
        ?>
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="admin/<?php echo $courseImage; ?>" alt="<?php echo $courseName; ?>" class="card-img-top" style="object-fit: cover; height: 200px;">
              <div class="card-body">
                <h5 class="card-title"><?php echo $courseName; ?></h5>
                <p class="card-text"><?php echo $courseDescription; ?></p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrationModal<?php echo $courseId; ?>">Register</button>
              </div>
            </div>
          </div>

          <!-- Registration Modal -->
          <div class="modal fade" id="registrationModal<?php echo $courseId; ?>" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="registrationModalLabel">Course Registration</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="" method="POST">
                    <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">

                    <div class="mb-3">
                      <label class="form-label" for="student_name">Your Name</label>
                      <input type="text" class="form-control" id="student_name" name="student_name" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label" for="student_email">Your Email</label>
                      <input type="email" class="form-control" id="student_email" name="student_email" required>
                    </div>

                    <!-- Add more registration fields here -->

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>

  <section id="team" class="mb-5 mt-5">
    <div class="container">
      <h2 class="text-center pb-4">Our Instructors</h2>
      <div class="row">
        <?php
        // Récupérer les instructeurs depuis la base de données
        $instructorsSql = "SELECT instructor_name, instructor_description, instructor_image FROM instructors";
        $instructorsResult = mysqli_query($conn, $instructorsSql);

        while ($instructorRow = mysqli_fetch_assoc($instructorsResult)) {
          $instructorName = $instructorRow['instructor_name'];
          $instructorDescription = $instructorRow['instructor_description'];
          $instructorImage = $instructorRow['instructor_image'];
        ?>
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="admin/<?php echo $instructorImage; ?>" alt="<?php echo $instructorName; ?>" class="card-img-top" style="object-fit: cover; height: 300px;" />
              <div class="card-body">
                <h5 class="card-title"><?php echo $instructorName; ?></h5>
                <p class="card-text"><?php echo $instructorDescription; ?></p>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>

  <?php
  // Connexion à la base de données
  $conn = mysqli_connect("localhost", "root", "", "ams");

  // Vérification de la connexion
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Variables pour stocker les valeurs du formulaire
  $name = "";
  $email = "";
  $message = "";

  // Variable pour afficher le message de succès
  $successMessage = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification des données soumises
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $message = $_POST['message'];

      // Insertion du message dans la table "contact_messages"
      $insertSql = "INSERT INTO contact_messages (name_contact, email_contact, message_contact) VALUES ('$name', '$email', '$message')";
      if (mysqli_query($conn, $insertSql)) {
        $successMessage = "Message sent successfully!";
      } else {
        $errorMessage = "Error: " . $insertSql . "<br>" . mysqli_error($conn);
      }
    }
  }

  // Fermeture de la connexion à la base de données
  mysqli_close($conn);
  ?>


<section id="contact">
  <div class="container mt-5 mb-5">
    <h2 class="text-center">Contact Us</h2>

    <?php if (!empty($successMessage)) { ?>
      <div class="alert alert-success" role="alert">
        <?php echo $successMessage; ?>
      </div>
    <?php } ?>

    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="form-group">
            <label class="form-label" for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
          </div>
          <div class="form-group">
            <label class="form-label" for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
          </div>
          <div class="form-group">
            <label class="form-label" for="message">Message:</label>
            <textarea class="form-control" id="message" name="message"><?php echo $message; ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Message Sent</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Your message has been sent successfully. We will get back to you soon.</p>
        </div>
      </div>
    </div>
  </div>
</section>


  <footer class="footer bg-dark text-white pt-5 mt-4 pb-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h4>About Academy Maram School</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus convallis suscipit tellus id aliquet.</p>
        </div>
        <div class="col-md-4">
          <h4>Contact Information</h4>
          <p>123 Main Street, City, Country</p>
          <p>Phone: +1 234 567890</p>
          <p>Email: info@academymaramschool.com</p>
          <h4>Follow Us</h4>
          <ul class="list-inline social-icons">
            <li class="list-inline-item">
              <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            </li>
            <!-- Add more social icons here -->
          </ul>

        </div>
        <div class="col-md-4">
          <h4>Location</h4>
          <div class="map-responsive">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3196.4675560994906!2d2.9644917152887675!3d36.75934947995714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128fb103f8943f9d%3A0x97e9de1c24190320!2sAcademy%20Maram%20School!5e0!3m2!1sen!2sdz!4v1689560554708!5m2!1sen!2sdz" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </footer>


  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="asstes/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
