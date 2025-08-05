<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");
ini_set('display_errors', 0);
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim(strtolower($_POST['email']));
    $password = $_POST['password'];

    try {
        // fetch by email only
        $sql = $db->prepare("
            SELECT Account.accountID, Account.password, Student.studentID, Student.fname, Student.lname, Student.phoneNum
            FROM Account
            INNER JOIN Student ON Student.accountID = Account.accountID
            WHERE Account.email = :email
            LIMIT 1
        ");
        $sql->bindValue(':email', $email, SQLITE3_TEXT);
        $res = $sql->execute();
        $user = $res->fetchArray(SQLITE3_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['accountID'] = $user['accountID'];
            $_SESSION['email']     = $email;
            $_SESSION['studentID'] = $user['studentID'];
            $_SESSION['fname']     = $user['fname'];
            $_SESSION['lname']     = $user['lname'];
            $_SESSION['phoneNum']  = $user['phoneNum'];
            header("Location: home.php");
            exit();
        } else {
            $login_err = "Incorrect email or password.<br>Need to <a href='reset_pass.php'>reset your password?</a>";
        }
    } catch (SQLite3Exception $e) {
        $login_err = "Login error. Please try again.";
    } finally {
        if ($db) $db->close();
    }
}
?>

<?php include(ROOT_PATH . "inc/headtags.inc"); ?>
<body>
  <?php if (isset($_SESSION['verify_success'])): ?>
    <div class="alert alert-success fw-semibold">
        <?= $_SESSION['verify_success']; ?>
    </div>
    <?php unset($_SESSION['verify_success']); ?>
  <?php endif; ?>
  <main>
    <div class="main-card fade-in">
      <h1>Log In</h1>
      <?php if (isset($login_err)): ?>
        <div style="color: red"><?= $login_err ?></div>
      <?php endif; ?>
      <form action="" method="POST" class="form" id="loginForm">
        <input type="email"    name="email"    placeholder="Email (@southernct.edu)" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" class="btn">Log In</button>
        <p class="form-footer">Don't have an account? <a href="signup.php">Sign Up</a></p>
      </form>
    </div>
  </main>
  <?php include(ROOT_PATH . "inc/footer.inc"); ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>  
</body>
</html>
