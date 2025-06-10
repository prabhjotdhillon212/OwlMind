<?php 
$auth_only_nav = true;
include 'inc/config.inc'; 
include 'inc/headtags.inc'; 
?>
<body>
  <!-- Navbar -->
  <?php include 'inc/header.inc'; ?>

  <!-- Welcome Hero Section -->
   
<main>
  <div class="main-card landing">
    <img src="public/images/Owl.png" class="welcome-img" alt="OwlMind logo">
    <h1 class="hero-heading">Welcome to <span class="hero-name"><?php echo SITE_NAME; ?></span></h1>
    <p class="hero-subtext">Your mental health matters. Let’s take care of it together.</p>
    
    <div class="btn-container">
      <a href="views/signup.php" class="btn btn-outline">Sign Up</a>
      <a href="views/login.php" class="btn">Log In</a>
    </div>

    <p class="home-summary">
      OwlMind is your personal mental wellness companion. <br>
      Sign up to start journaling, tracking moods, and getting support.
    </p>
  </div>
</main>


  <!-- Footer -->
  <?php include 'inc/footer.inc'; ?>
</body>
