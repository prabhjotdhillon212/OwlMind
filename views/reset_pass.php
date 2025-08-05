<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";

require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
?>
<?php

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
    $email = $_POST['email'];
    $student_id = $_POST['student_id'];
    $new_password = $_POST['new_password'];
    $password_repeat = $_POST['confirm'];

    $studentidget = $db->prepare("SELECT studentID FROM Student WHERE studentID='$student_id'");
    $studentidget->execute();
    $emailget = $db->prepare("SELECT email FROM Account WHERE email='$email'");
    $emailget->execute();
    $joinarray = $db->prepare("SELECT studentID, email FROM Student INNER JOIN Account ON Student.accountID = Account.accountID 
        WHERE Account.email = :email AND Student.studentID = :student_id");
    $joinarray->bindValue(':email', $email, SQLITE3_TEXT);
    $joinarray->bindValue(':student_id', $student_id, SQLITE3_TEXT);
    $result = $joinarray->execute();

    try {
        if ($new_password != $password_repeat) {
            $err = "Passwords must match";
        } elseif (!(item_exists($db,$email, 'email'))) {
            $err = "Email doesn't exist. Please <a href='signup.php'>sign up</a>.";
        } elseif (!(item_exists($db ,$student_id, 'studentID'))) {
            $err = "Student ID doesn't exist. Please <a href='signup.php'>sign up</a>.";
        } elseif (!($result->fetchArray())) {
            $err = "Email / StudentID pair do not match.";
        } else {
            // **HASH THE PASSWORD**
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $updatesql = $db->prepare("UPDATE Account SET password=:password WHERE email='$email'");
            $updatesql->bindParam(':password', $hashed, SQLITE3_TEXT);
            $updatesql->execute();
            session_regenerate_id(true);
            session_destroy();
            header("Location: pass_reset_success.php");
            exit();
        }
    } catch (SQLite3Exception $e) {
        echo "Error: " . $e->getMessage();
        echo "Error Code: " . $e->getCode();
    } finally {
        if($db) $db->close();
    }
}

?>
<body>
    <main>
        <div class="main-card fade-in">
            <h1>Reset Your Password</h1>
            <?php
                // if incorrect entries, throw error message:
                if (isset($err)) {
                echo "<div style='color: red'>" . $err . "</div>";
                }
            ?>
            <form action="" method="POST" class="form" id="resetpassform">
                <input type="email" name="email" placeholder="abc123@southernct.edu" required />
                <input type="text" name="student_id" placeholder="Student ID" required />
                <input type="password" name="new_password" placeholder="New Password" required />
                <input type="password" name="confirm" placeholder="Confirm New Password" required />
                <p class="error" id="emailError" style="display: none;">Only southernct.edu emails are allowed.</p>
                <button type="submit" class="btn">Reset Password</button>
                <p class="form-footer">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>
    </main>
    <div class="push"></div>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>