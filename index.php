<?php include 'inc/config.inc'; ?>

<?php include 'inc/headtags.inc'; ?>
<body>
  <!-- Navbar -->
  <?php include 'inc/header.inc'; ?>

  <!-- Welcome Section -->
  <main>
    <div class="main-card">
      <img src="public/images/Owl.png" class="welcome-img"> 
      <h1>Welcome to <?php echo SITE_NAME; ?></h1>
      <p>Your mental health matters. Let’s take care of it together.</p>
      <div class="btn-container">
        <a href="views/signup.html" class="btn btn-outline">Sign Up</a>
        <a href="views/login.html" class="btn">Log In</a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include 'inc/footer.inc'; ?>
</body>
