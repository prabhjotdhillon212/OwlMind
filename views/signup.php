<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $id = $_POST['student_id'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $phone = $_POST['phone'];

  $sql = $db->prepare("INSERT INTO account (email, password) VALUES ('$email', '$password')");
  $sql->execute();
  $account_id = $db->lastInsertRowID();

  try {
    $sql = $db->prepare("INSERT INTO Student (studentID, fname, lname, phoneNum, accountID) VALUES ('$id', '$fname', '$lname', '$phone', '$account_id')");
    $sql->execute();

    $_SESSION['accountID'] = $account_id;
    $sql = $db->prepare("SELECT * FROM Account WHERE email='$email'");
    $result = $sql->execute();
    $email = $result->fetchArray(SQLITE3_ASSOC);
    $_SESSION['email'] = $email;
    header("Location: home.php");
    exit();
  } catch (SQLite3Exception $e) {
    echo "Error: " . $e->getMessage();
  }
  $db->close();
}
?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <main class="d-flex justify-content-center align-items-center p-4" style="min-height: 100vh;">
    <div class="card p-4 shadow-lg fade-in text-center" style="max-width: 400px; width: 100%; border-radius: 12px;">
      <h1 class="fw-bold mb-4" style="color: #01497c;">Create an Account</h1>
      <form action="" method="POST" class="d-flex flex-column gap-3" id="signupForm">
        <input type="text" name="fname" class="form-control" placeholder="First Name" required />
        <input type="text" name="lname" class="form-control" placeholder="Last Name" required />
        <input type="tel" name="phone" class="form-control" placeholder="Phone Number (e.g., 203-555-1234)" required />
        <input type="email" name="email" class="form-control" placeholder="abc123@southernct.edu" required />
        <input type="text" name="student_id" class="form-control" placeholder="Southern Student ID" required />
        <input type="password" name="password" class="form-control" placeholder="Password" required />
        <input type="password" name="confirm" class="form-control" placeholder="Confirm Password" required />
        <p class="text-danger fw-semibold" id="emailError" style="display: none;">Only southernct.edu emails are allowed.</p>
        <button type="submit" class="btn btn-custom">Sign Up</button>
        <p class="mt-3 text-muted">Already have an account? <a href="login.php" class="fw-semibold" style="color: #0077b6;">Log In</a></p>
      </form>
    </div>
  </main>

  <?php include(ROOT_PATH . "inc/footer.inc"); ?>

  <script>
    const form = document.getElementById("signupForm");
    const emailInput = form.querySelector("input[name='email']");
    const errorMsg = document.getElementById("emailError");

    form.addEventListener("submit", (e) => {
      const email = emailInput.value.trim().toLowerCase();
      if (!email.endsWith("@southernct.edu")) {
        e.preventDefault();
        errorMsg.style.display = "block";
      } else {
        errorMsg.style.display = "none";
      }
    });
  </script>
</body>
s