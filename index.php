<?php 
$auth_only_nav = true;
include 'inc/config.inc'; 
include 'inc/headtags.inc';
?>
<body>
  <!-- Navbar -->
  <?php include 'inc/header.inc'; ?>

  <!-- Landing Hero -->
  <main>
    <div class="main-card landing-page fade-in">
      <img src="public/images/Owl.png" class="hero-logo" alt="OwlMind logo">
      <h1 class="hero-h">Welcome to <span class="hero-name"><?php echo SITE_NAME; ?></span></h1>
      <p class="hero-sub">Your mental health matters. Let’s take care of it together.</p>

      <div class="btn-container">
        <a href="views/signup.php" class="btn btn-outline">Sign Up</a>
        <a href="views/login.php" class="btn">Log In</a>
      </div>

      <p class="home-summary">
        Sign up to start journaling, tracking moods, and getting support.
      </p>
    </div>
  </main>

  <!-- Footer -->
  <?php include 'inc/footer.inc'; ?>
</body>