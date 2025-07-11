<?php
session_start();
$auth_only_nav = true;
require_once "../inc/config.inc";
require_once(ROOT_PATH . "inc/header.inc");
require_once(ROOT_PATH . "vendor/autoload.php");
require_once(ROOT_PATH . "inc/email_service.php");
include 'db_connection.php';

function item_exists($db, $item_value, $item_type) {
    $sql = $db->prepare("SELECT * FROM Student INNER JOIN Account ON Account.accountID = Student.accountID 
                        WHERE $item_type = :item_value");
    $sql->bindValue(':item_value', trim($item_value), SQLITE3_TEXT); // Trim value before checking
    $result = $sql->execute();
    $fetch = $result->fetchArray(SQLITE3_ASSOC);
    return $fetch ? true : false;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Trim & sanitize all inputs
    $id = trim($_POST['student_id']);
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);
    $password_repeat = trim($_POST['confirm']);
    $fname = ucfirst(strtolower(trim($_POST['fname'])));
    $lname = ucfirst(strtolower(trim($_POST['lname'])));
    $phone = preg_replace('/\s+/', '', trim($_POST['phone'])); // remove spaces in phone numbers

    try {
        if ($password != $password_repeat) {
            $err = "Passwords must match";
        } elseif (strlen($phone) != 10 || !(is_numeric($phone))) {
            $err = "Phone numbers must be 10 digits. Enter numbers only.";
        } elseif (strlen($id) != 8 || !(is_numeric($id))) {
            $err = "Student ID must be 8 digits. Enter only numbers."; 
        } elseif (item_exists($db,$email, 'email')) {
            $err = "Email already exists. Please <a href='login.php'>log in</a>.";
        } elseif (item_exists($db, $id, 'studentID')) {
            $err = "Student ID already in use.";
        } else {
            // Insert Account
            $sql = $db->prepare("INSERT INTO Account (email, password) VALUES (:email, :password)");
            $sql->bindValue(':email', $email, SQLITE3_TEXT);
            $sql->bindValue(':password', $password, SQLITE3_TEXT);
            $sql->execute();

            $account_id = $db->lastInsertRowID();

            // Insert Student
            $sql = $db->prepare("INSERT INTO Student (studentID, fname, lname, phoneNum, accountID) 
                                VALUES (:id, :fname, :lname, :phone, :account_id)");
            $sql->bindValue(':id', $id, SQLITE3_TEXT);
            $sql->bindValue(':fname', $fname, SQLITE3_TEXT);
            $sql->bindValue(':lname', $lname, SQLITE3_TEXT);
            $sql->bindValue(':phone', $phone, SQLITE3_TEXT);
            $sql->bindValue(':account_id', $account_id, SQLITE3_INTEGER);
            $sql->execute();

            // Set session
            $_SESSION['accountID'] = $account_id;
            $_SESSION['email'] = $email;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;

            // Send welcome email
            if (sendWelcomeEmail($email, "$fname $lname")) {
                $_SESSION['success_message'] = "✅ Account created successfully! A welcome email has been sent.";
            } else {
                $_SESSION['warning_message'] = "✅ Account created successfully, but we couldn't send the welcome email. Check your spam folder.";
            }

            header("Location: verify.php");
            exit();
        }
    } catch (SQLite3Exception $e) {
        $err = "An error occurred. Please try again.";
    } finally {
        if ($db) $db->close();
    }
}
?>

<?php include(ROOT_PATH . "inc/headtags.inc"); ?>
<body>
<main>
    <div class="main-card fade-in">
        <h1>Create an Account</h1>
        <?php if (isset($err)): ?>
            <p class="fw-semibold text-danger"><?= $err; ?></p>
        <?php endif; ?>
        <form action="" method="POST" class="form" id="signupForm">
            <input type="text" name="fname" placeholder="First Name" required>
            <input type="text" name="lname" placeholder="Last Name" required>
            <input type="text" name="phone" placeholder="Phone Number (e.g., 2035551234)" required>
            <input type="email" name="email" placeholder="abc123@southernct.edu" required>
            <input type="text" name="student_id" placeholder="Southern Student ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <p class="error" id="emailError" style="display:none;">Only southernct.edu emails are allowed.</p>
            <div class="form-check text-start">
                <input class="form-check-input" type="checkbox" id="tosCheckbox" required>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
