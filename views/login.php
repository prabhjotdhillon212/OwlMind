<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");
?>
<?php

  include 'db_connection.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

  try {
    $sql = $db->prepare("SELECT * FROM Account WHERE email='$email' AND password='$password'");
    $result = $sql->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user['email'] == $email && $user['password'] == $password) {
      $_SESSION['accountID'] = $user['accountID'];
      $_SESSION['email'] = $user['email'];
      header("Location: home.php");
      exit();
    } else {
      echo "<h1>Invalid email address or password.</h1>";
    }
  } catch (SQLite3Exception $e) {
    echo "Error: ". $e->getMessage();
  } finally {
    if($db) $db->close();
  }

  $db->close();
}

?>

<?php include(ROOT_PATH . "inc/headtags.inc"); ?>
<body>
  <main>
    <div class="main-card fade-in">
      <h1>Log In</h1>
      <form action="" method="POST" class="form" id="loginForm">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" class="btn">Log In</button>
        <p class="form-footer">Don't have an account? <a href="signup.php">Sign Up</a></p>
      </form>
    </div>
  </main>

  <?php include(ROOT_PATH . "inc/footer.inc"); ?>
</body>
