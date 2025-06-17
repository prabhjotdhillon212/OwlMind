<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");

include 'db_connection.php';

function item_exists($db, $item_value, $item_type) {
  $sql = $db->prepare("SELECT * FROM Student INNER JOIN Account ON Account.accountID = Student.accountID 
                      WHERE $item_type = '$item_value'");
  $result = $sql->execute();
  $fetch = $result->fetchArray(SQLITE3_ASSOC);
  if($fetch[$item_type]) {
    return true;
  } else {
    return false;
  }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id = $_POST['student_id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_repeat = $_POST['confirm'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];

  try {
    if ($password != $password_repeat) {
      $err = "Passwords must match";
    } elseif (strlen($phone) != 10 || !(is_numeric($phone))) {
      $err = "Phone numbers must be 10 digits. Enter numbers only.";
    } elseif (strlen($id) != 8 || !(is_numeric($id))) {
      $err = "Student ID must be 8 digits. Enter only numbers."; 
    } elseif (item_exists($db,$email, 'email')) {
      $err = "Email address already exists. Please <a href='login.php'>log in</a>.";
    } elseif (item_exists($db, $id, 'studentID')) {
      $err = "Student ID already in use.";
    } else {
      $sql = $db->prepare("INSERT INTO account (email, password) VALUES (:email, :password)");
      $sql->bindValue(':email', $email, SQLITE3_TEXT);
      $sql->bindValue(':password', $password, SQLITE3_TEXT);
      $sql->execute();

      // Get $account_id to set session variable:
      $account_id = $db->lastInsertRowID();

      $sql = $db->prepare("INSERT INTO Student (studentID, fname, lname, phoneNum, accountID) VALUES (:id, :fname, :lname, :phone, :account_id)");
      $sql->bindValue(':id', $id, SQLITE3_TEXT);
      $sql->bindValue(':fname', $fname, SQLITE3_TEXT);
      $sql->bindValue(':lname', $lname, SQLITE3_TEXT);
      $sql->bindValue(':phone', $phone, SQLITE3_TEXT);
      $sql->bindValue(':account_id', $account_id, SQLITE3_TEXT);
      $sql->execute();

      // Set $_SESSION['accountID']:
      $_SESSION['accountID'] = $account_id;

      // Set $_SESSION['email']: 
      $sql = $db->prepare("SELECT * FROM Account WHERE email='$email'");
      $result = $sql->execute();
      $email = $result->fetchArray(SQLITE3_ASSOC);
      $_SESSION['email'] = $email;

      header("Location: home.php");
      exit();
    }
  } catch (SQLite3Exception $e) {
    // echo "Error: " . $e->getMessage();
    // echo "Error Code: " . $e->getCode();
  } finally {
    if($db) $db->close();
  }
}

?>
 
<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>

  <!-- Signup Form -->
  <main>
    <div class="main-card fade-in">
      <h1>Create an Account</h1>
      <?php
        // if incorrect entries, throw error message:
        if (isset($err)) {
          echo "<div style='color: red'>" . $err . "</div>";
        }
      ?>
      <form action="" method="POST" class="form" id="signupForm">
        <input type="text" name="fname" placeholder="First Name" required />
        <input type="text" name="lname" placeholder="Last Name" required />
        <input type="text" name="phone" placeholder="Phone Number (e.g., 2035551234)" required />
        <input type="email" name="email" placeholder="abc123@southernct.edu" required />
        <input type="text" name="student_id" placeholder="Southern Student ID" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="password" name="confirm" placeholder="Confirm Password" required />
        <p class="error" id="emailError" style="display: none;">Only southernct.edu emails are allowed.</p>
        <button type="submit" class="btn">Sign Up</button>
        <p class="form-footer">Already have an account? <a href="login.php">Log In</a></p>
      </form>
    </div>
  </main>

  <?php include(ROOT_PATH . "inc/footer.inc"); ?>

  <!-- JavaScript validation -->
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