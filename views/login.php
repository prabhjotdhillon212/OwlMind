<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");
ini_set('display_errors', 0);

include 'db_connection.php';

<<<<<<< HEAD
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
=======
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    try {
        // Fetch user info by email and password directly
        $sql = $db->prepare("SELECT a.accountID, a.password, s.fname, s.lname 
                             FROM Account a
                             JOIN Student s ON a.accountID = s.accountID
                             WHERE LOWER(a.email) = :email AND a.password = :password");
        $sql->bindValue(':email', $email, SQLITE3_TEXT);
        $sql->bindValue(':password', $password, SQLITE3_TEXT);
        $result = $sql->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if ($user) {
            // Set session variables
            $_SESSION['accountID'] = $user['accountID'];
            $_SESSION['email'] = $email;
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];

            header("Location: home.php");
            exit();
        } else {
            $login_err = "Incorrect email or password.<br>
                          Need to <a href='reset_pass.php'>reset your password?</a>";
        }
    } catch (SQLite3Exception $e) {
        $login_err = "An error occurred. Please try again later.";
    } finally {
        if ($db) $db->close();
    }
>>>>>>> recovered-wip
}
?>

<?php include(ROOT_PATH . "inc/headtags.inc"); ?>
<body>
  <main>
    <div class="main-card fade-in">
      <h1>Log In</h1>
      <?php
<<<<<<< HEAD
        // if incorrect login credentials, throw error message:
        if (isset($login_err)) {
          echo "<div style='color: red'>" . $login_err . "</div>";
=======
        if (isset($login_err)) {
            echo "<div class='text-danger fw-semibold'>" . $login_err . "</div>";
>>>>>>> recovered-wip
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
</body>
