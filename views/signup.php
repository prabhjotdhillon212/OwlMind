<?php
session_start();
$auth_only_nav = true;

require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/headtags.inc");
require_once(ROOT_PATH . "inc/header.inc");
require_once ROOT_PATH . 'vendor/autoload.php';
require_once ROOT_PATH . "inc/email_service.php";
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
    $id = trim($_POST['student_id']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $fname = ucfirst(trim($_POST['fname']));
    $lname = ucfirst(trim($_POST['lname']));
    $phone = trim($_POST['phone']);
    $errors = [];

    if ($password !== $confirm) {
        $errors[] = "❌ Passwords do not match. Please try again.";
    }
    if (!str_ends_with($email, "@southernct.edu")) {
        $errors[] = "❌ Only southernct.edu emails are allowed for registration.";
    }
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='text-danger'>{$error}</p>";
        }
        goto end_script;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $db->exec('BEGIN TRANSACTION');

        $check_email_sql = $db->prepare("SELECT accountID FROM account WHERE email = :email");
        $check_email_sql->bindValue(':email', $email, SQLITE3_TEXT);
        $result_email = $check_email_sql->execute();
        if ($result_email->fetchArray()) {
            $db->exec('ROLLBACK');
            echo "<p class='text-danger'>⚠️ An account with this email already exists. Please log in or use a different email.</p>";
            goto end_script;
        }

        $check_id_sql = $db->prepare("SELECT studentID FROM Student WHERE studentID = :studentID");
        $check_id_sql->bindValue(':studentID', $id, SQLITE3_TEXT);
        $result_id = $check_id_sql->execute();
        if ($result_id->fetchArray()) {
            $db->exec('ROLLBACK');
            echo "<p class='text-danger'>⚠️ This Southern Student ID is already registered.</p>";
            goto end_script;
        }

        $sql_account = $db->prepare("INSERT INTO account (email, password) VALUES (:email, :password)");
        $sql_account->bindValue(':email', $email, SQLITE3_TEXT);
        $sql_account->bindValue(':password', $hashed_password, SQLITE3_TEXT);
        $sql_account->execute();
        $account_id = $db->lastInsertRowID();

        $sql_student = $db->prepare("INSERT INTO Student (studentID, fname, lname, phoneNum, accountID)
                                     VALUES (:studentID, :fname, :lname, :phone, :accountID)");
        $sql_student->bindValue(':studentID', $id, SQLITE3_TEXT);
        $sql_student->bindValue(':fname', $fname, SQLITE3_TEXT);
        $sql_student->bindValue(':lname', $lname, SQLITE3_TEXT);
        $sql_student->bindValue(':phone', $phone, SQLITE3_TEXT);
        $sql_student->bindValue(':accountID', $account_id, SQLITE3_INTEGER);
        $sql_student->execute();

        $db->exec('COMMIT');

        $_SESSION['accountID'] = $account_id;
        $_SESSION['email'] = $email;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;

        if (sendWelcomeEmail($email, $fname . ' ' . $lname)) {
            $_SESSION['success_message'] = "✅ Account created successfully! A welcome email has been sent.";
        } else {
            $_SESSION['warning_message'] = "✅ Account created successfully, but we couldn't send the welcome email. Please check your spam folder.";
            error_log("⚠️ Failed to send welcome email to $email after successful signup.");
        }

        header("Location: home.php");
        exit();

    } catch (SQLite3Exception $e) {
        $db->exec('ROLLBACK');
        $error_message = $e->getMessage();
        if (strpos($error_message, "UNIQUE constraint failed: account.email") !== false) {
            echo "<p class='text-danger'>⚠️ An account with this email already exists. Please log in or use a different email.</p>";
        } elseif (strpos($error_message, "UNIQUE constraint failed: Student.studentID") !== false) {
            echo "<p class='text-danger'>⚠️ This Southern Student ID is already registered.</p>";
        } else {
            echo "<p class='text-danger'>Error during registration: " . htmlspecialchars($error_message) . "</p>";
            error_log("SQLite3 Exception in signup.php: " . $error_message);
        }
    } finally {
        if ($db) {
            $db->close();
        }
    }
}
end_script:
?>

<main class="d-flex justify-content-center align-items-center p-4" style="min-height: 100vh;">
    <div class="main-card fade-in">
        <h1>Create an Account</h1>

        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<p class='text-success'>" . htmlspecialchars($_SESSION['success_message']) . "</p>";
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['warning_message'])) {
            echo "<p class='text-warning'>" . htmlspecialchars($_SESSION['warning_message']) . "</p>";
            unset($_SESSION['warning_message']);
        }
        ?>

        <form action="" method="POST" class="form" id="signupForm">
            <input type="text" name="fname" placeholder="First Name" required value="<?php echo htmlspecialchars($_POST['fname'] ?? ''); ?>" />
            <input type="text" name="lname" placeholder="Last Name" required value="<?php echo htmlspecialchars($_POST['lname'] ?? ''); ?>" />
            <input type="tel" name="phone" placeholder="Phone Number (e.g., 203-555-1234)" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" />
            <input type="email" name="email" placeholder="abc123@southernct.edu" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
            <input type="text" name="student_id" placeholder="Southern Student ID" required value="<?php echo htmlspecialchars($_POST['student_id'] ?? ''); ?>" />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="confirm" placeholder="Confirm Password" required />
            <p class="text-danger fw-semibold" id="emailError" style="display: none;">
                Only southernct.edu emails are allowed.
            </p>
            <div class="form-check text-start">
                <input class="form-check-input" type="checkbox" id="tosCheckbox" required />
                <label class="form-check-label small" for="tosCheckbox">
                    I acknowledge this app is <strong>not monitored in real-time</strong> and does <strong>not replace professional care</strong>.
                    If I am in crisis, I will call <a href="tel:988" class="fw-semibold">988</a>.
                </label>
            </div>
            <button type="submit" class="btn">Sign Up</button>
            <p class="form-footer">Already have an account? <a href="login.php">Log In</a></p>
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
