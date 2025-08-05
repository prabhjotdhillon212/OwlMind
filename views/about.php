<?php
  session_start();
  if (!isset($_SESSION['studentID'])) {
    $auth_only_nav = true;
  }
require_once "../inc/config.inc";

require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";

include 'db_connection.php';

?>
<body>
  <main>
    <div class="about-box bg-white p-4 shadow rounded-4" style="margin-bottom: 5%; width: 60%;">      
      <div class="about-us-container">
        <h1 class="fw-bold mb-2" style="color:#1d3557; padding-top: 5%;">About Us</h1>
        <p>
          Many students struggle with mental health, on and off campus. While Southern Connecticut State University offers a wide range of much-needed services, navigating to
          those services is difficult and cumbersome. While indeed <?php echo SITE_NAME ?> began as a simple capstone project, we aimed to provide students a free, easier, better way to connect to the 
          Counseling Services at SCSU and to create tools and spaces for students to improve their mental well-being and excel in and out of class. We hope you enjoy using OwlMind and we wish
          you all a good journey to an improved mental health!
        </p>
      </div>
      <div class="about-us-container">
        <h2 class="fw-bold mb-2" style="color:#1d3557;">About the Developers</h2>
        <h3 class="fw-bold mb-2">Prabhjot</h3>
        <div id="about-prabhjot">
          <p>
            Prabjhot loves coding, gaming, and turning ideas into real, working projects.
          </p>
        </div>
        <h3 class="fw-bold mb-2">Justin</h3>
        <div id="about-justin">
          <p>
            Justin loves strategy games (board and video), is passionate about mental health and fostering different avenues of good communication, 
            and has an aquarium and lives with his two cats Bear and Beans. Has a large monitor setup and yet refuses to use it.
          </p>          
        </div>
      </div>
    </div>
  </main>
    <!-- Footer -->
  <div class="push"></div>
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>