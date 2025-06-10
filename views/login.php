<?php
session_start();
$auth_only_nav = true;
<<<<<<< HEAD
require_once "../inc/config.inc";
require_once(ROOT_PATH . "/inc/header.inc");
?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
=======

require_once("../inc/config.inc"); 
require_once(ROOT_PATH . "inc/headtags.inc");
require_once(ROOT_PATH . "inc/header.inc");
?>
>>>>>>> 2a59793 (Added all the new pages, dashboard)
<body>

  <main>
    <div class="main-card fade-in">
      <h1>Log In</h1>
      <form action="/login" method="POST" class="form">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" class="btn">Log In</button>
        <p class="form-footer">Don't have an account? <a href="signup.php">Sign Up</a></p>
      </form>
    </div>
  </main>

  <?php include(ROOT_PATH . "inc/footer.inc"); ?>
</body>
