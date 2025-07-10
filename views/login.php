<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");
ini_set('display_errors', 0);
?>
<?php

  include 'db_connection.php';

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

  try {
    $sql = $db->prepare("SELECT * FROM Account INNER JOIN Student ON Student.accountID = Account.accountID WHERE email='$email' AND password='$password'");
    $result = $sql->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    // Set $_SESSION variables:
    if ($user['email'] == $email && $user['password'] == $password) {
      $_SESSION['accountID'] = $user['accountID'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['studentID'] = $user['studentID'];
      $_SESSION['fname'] = $user['fname'];
      $_SESSION['lname'] = $user['lname'];
      $_SESSION['phoneNum'] = $user['phoneNum'];
      header("Location: home.php");
      exit();
    } else {
        // Set error message:
        $login_err = "Incorrect email or password.<br>Need to <a href='reset_pass.php'>reset your password?</a>";
    }
  } catch (SQLite3Exception $e) {
    // echo "Error: ". $e->getMessage();
  } finally {
    if($db) $db->close();
  }
}

?>

<?php include(ROOT_PATH . "inc/headtags.inc"); ?>
<body>
  <main>
    <div class="main-card fade-in">
      <h1>Log In</h1>
      <?php
        // if incorrect login credentials, throw error message:
        if (isset($login_err)) {
          echo "<div style='color: red'>" . $login_err . "</div>";
        }
      ?>
      <form action="" method="POST" class="form" id="loginForm">
        <input type="email" name="email" placeholder="Email (@southernct.edu)" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" class="btn">Log In</button>
        <p class="form-footer">Don't have an account? <a href="signup.php">Sign Up</a></p>
      </form>
    </div>
  </main>

  <?php include(ROOT_PATH . "inc/footer.inc"); ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>