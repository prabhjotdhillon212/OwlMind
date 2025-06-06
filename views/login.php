<?php require_once "../inc/config.inc"; ?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <!-- Navbar -->
  <?php include ROOT_PATH . "inc/header.inc"; ?>

  <main>
    <div class="main-card">
      <h1>Log In</h1>
      <form action="/login" method="POST" class="form">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" class="btn">Log In</button>
        <p class="form-footer">Don't have an account? <a href="signup.html">Sign Up</a></p>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
</body>
