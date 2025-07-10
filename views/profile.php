<?php
  session_start();
  require_once "../inc/config.inc";
  require_once ROOT_PATH . "inc/headtags.inc";
  require_once ROOT_PATH . "inc/header.inc";
?>
<?php 
  include 'db_connection.php';

  if (!isset($_SESSION['accountID'])) {
    header("Location: login.php");
    exit();
  }

  $entrySaved = false;
  $saveError = false;

  $student_id = $_SESSION['studentID'];

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? '';

    switch($action) {
      case 'fname':
        $fname = $_POST["fname"] ?? '';
        $updatefname = $db->prepare("UPDATE Student SET fname=:fname WHERE studentID=$student_id");
        $updatefname->bindParam(':fname', $fname, SQLITE3_TEXT);
        $entrySaved = $updatefname->execute();
        $saveError = !$entrySaved;
        $_SESSION['fname'] = $fname;
        break;

      case 'lname':
        $lname = $_POST["lname"] ?? '';
        $updatelname = $db->prepare("UPDATE Student SET lname=:lname WHERE studentID=$student_id");
        $updatelname->bindParam(':lname', $lname, SQLITE3_TEXT);
        $entrySaved = $updatelname->execute();
        $saveError = !$entrySaved;
        $_SESSION['lname'] = $lname;
        break;

      case 'phoneNum':
        $phoneNum = $_POST["phoneNum"] ?? '';
        if (strlen($phoneNum) != 10 || !is_numeric($phoneNum)) {
          $err = "Phone numbers must be 10 digits. Enter numbers only.";
        } else {
          $updatephone = $db->prepare("UPDATE Student SET phoneNum=:phoneNum WHERE studentID=$student_id");
          $updatephone->bindParam(':phoneNum', $phoneNum, SQLITE3_TEXT);
          $entrySaved = $updatephone->execute();
          $saveError = !$entrySaved;
          $_SESSION['phoneNum'] = $phoneNum;
        }
        break;
    }
  }

  if ($entrySaved) {
    $message = "✅ Entry saved!";
    $class = 'text-success';
  } else if ($saveError) {
    $message = "❌ Something went wrong. Please try again.";
    $class = 'text-danger';
  }

  $db->close();
?>

<main>
  <div class="main-card profile-card fade-in">
    <div class="profile-avatar">
      <?php echo strtoupper(substr($_SESSION['fname'], 0, 1)); ?>
    </div>
    <h1><?php echo $_SESSION['fname']; ?>'s Profile</h1>

    <?php
      if (isset($err)) {
        echo "<p class='fw-semibold text-danger'>" . $err . "</p>";
      }
      if (isset($message)) {
        echo "<p class='fw-semibold $class'>" . $message . "</p>";
      }
    ?>

    <form class="profile-form">
      <fieldset>
        <label for="fname">First Name</label>
        <input name="fname" id="fname" type="text" value="<?php echo $_SESSION['fname']; ?>" disabled />
        <button class="edit-button" id="button-fname" type="button">Edit</button>
      </fieldset>
      <fieldset>
        <label for="lname">Last Name</label>
        <input name="lname" id="lname" type="text" value="<?php echo $_SESSION['lname']; ?>" disabled />
        <button class="edit-button" id="button-lname" type="button">Edit</button>
      </fieldset>
      <fieldset>
        <label for="phoneNum">Phone Number</label>
        <input name="phoneNum" id="phoneNum" type="text" value="<?php echo $_SESSION['phoneNum']; ?>" disabled />
        <button class="edit-button" id="button-phoneNum" type="button">Edit</button>
      </fieldset>
      <a href="reset_pass.php" class="reset-password">Reset Password</a>
    </form>
  </div>
</main>

<?php include ROOT_PATH . "inc/footer.inc"; ?>

<script>
  const editButtons = document.querySelectorAll('.edit-button');
  const newForm = document.createElement("form");
  newForm.method = 'POST';
  newForm.className = 'profile-form';
  newForm.style.marginTop = '20px';

  const newInput = document.createElement('input');
  newInput.type = 'text';
  newInput.className = 'form-input';

  const submitButton = document.createElement('button');
  submitButton.type = 'submit';
  submitButton.className = 'edit-button';
  submitButton.style.backgroundColor = '#ffcc40';
  submitButton.textContent = 'Submit';

  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      const field = this.previousElementSibling;
      field.disabled = false;
      submitButton.name = 'action';
      submitButton.value = field.name;
      this.closest('fieldset').appendChild(submitButton);
    });
  });
<<<<<<< HEAD
</script>
=======
</script>
>>>>>>> recovered-wip
