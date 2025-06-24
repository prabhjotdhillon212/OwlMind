<?php
  session_start();
  $auth_only_nav = true;
  require_once "../inc/config.inc";

  require_once ROOT_PATH . "inc/headtags.inc";
  require_once ROOT_PATH . "inc/header.inc";
?>
<?php 
  include 'db_connection.php';
?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <!-- Navbar -->
  <main>
    <div class="main-card fade-in">
      <a href="reset_pass.php">Reset Password</a>
    </div>
  </main>
    <!-- Footer -->
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
</body>
