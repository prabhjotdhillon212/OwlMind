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
        $fname = ucfirst(strtolower(trim(htmlspecialchars($_POST['fname'] ?? ''))));

        $updatefname = $db->prepare("UPDATE Student SET fname=:fname WHERE studentID=$student_id");
        $updatefname->bindParam(':fname', $fname, SQLITE3_TEXT);
        $entrySaved = $updatefname->execute();
        $saveError = !$entrySaved;
        $_SESSION['fname'] = $fname;
        //header("Location: profile.php");
        break;

      case 'lname':
        $lname = ucfirst(strtolower(trim(htmlspecialchars($_POST['lname'] ?? ''))));
          
        $updatelname = $db->prepare("UPDATE Student SET lname=:lname WHERE studentID=$student_id");
        $updatelname->bindParam(':lname', $lname, SQLITE3_TEXT);
        $updatelname->execute();
        $entrySaved = $updatelname->execute();
        $saveError = !$entrySaved;
        $_SESSION['lname'] = $lname;
        //header("Location: profile.php");
        break;
        
      case 'phoneNum':
        $phoneNum = htmlspecialchars($_POST["phoneNum"] ?? '');

        if (strlen($phoneNum) != 10 || !(is_numeric($phoneNum))) {
          $err = "Phone numbers must be 10 digits. Enter numbers only.";
        } else {
          $updatephone = $db->prepare("UPDATE Student SET phoneNum=:phoneNum WHERE studentID=$student_id");
          $updatephone->bindParam(':phoneNum', $phoneNum, SQLITE3_TEXT);
          $updatephone->execute();
          $entrySaved = $updatephone->execute();
          $saveError = !$entrySaved;
          $_SESSION['phoneNum'] = $phoneNum;
          //header("Location: profile.php");
          break;
        }
      default:
        // echo "Invalid action.";
      } 
  }
    
  if ($entrySaved == true) {
    $message = "✅ Entry saved!";
    $class = 'text-success';
  } else if ($saveError == true) {
    $message = "❌ Something went wrong. Please try again.";
    $class = 'text-danger';
  } else {
    // do nothing
  }

  $db->close();
?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <!-- Navbar -->
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

      <form class="form">
        <fieldset class="field">
          <label for="fname">First Name:</label>
          <input name="fname" id="fname" type="text" value="<?php echo $_SESSION['fname']; ?>" disabled />
          <button class="edit-button btn" id="button-fname" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="lname">Last Name:</label>
          <input name="lname" id="lname" type="text" value="<?php echo $_SESSION['lname']; ?>" disabled />
          <button class="edit-button btn" id="button-lname" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="phoneNum">Phone Number:</label>
          <input class="input" name="phoneNum" id="phoneNum" type="text" value="<?php echo $_SESSION['phoneNum']; ?>" disabled />
          <button class="edit-button btn" id="button-phoneNum" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <a class="reset-password" href="reset_pass.php">Reset Password</a>  
        </fieldset>             
      </form>
    </div>
  </main>
    <!-- Footer -->
  <div class="push"></div>
  <?php include ROOT_PATH . "inc/footer.inc"; ?>

  <script>
    // dynamic form when edit buttons are clicked:
    const editbutton = document.querySelectorAll('button');

    const newForm = document.createElement("form");
    newForm.setAttribute('method', 'POST');
    newForm.setAttribute('class', 'profile-form');
    // newForm.setAttribute('id', 'signupForm');
    newForm.style.marginTop = '5%';

    const newSubmitButton = document.createElement('button');
    newSubmitButton.setAttribute('type', 'submit');
    newSubmitButton.setAttribute('class', 'edit-button');
    newSubmitButton.setAttribute('style', 'background-color: green');
    newSubmitButton.setAttribute('name', 'action');
    newSubmitButton.textContent = 'Submit';

    const newCancelButton = document.createElement('button');
    newCancelButton.setAttribute('type', 'button'); // Important for cancel button
    newCancelButton.setAttribute('class', 'edit-button');
    newCancelButton.setAttribute('style', 'background-color: red');
    newCancelButton.setAttribute('name', 'action');
    newCancelButton.textContent = 'Cancel';
    newCancelButton.addEventListener('click', function() {
        newForm.remove();
    });

    const newInput = document.createElement('input');
    newInput.setAttribute('type', 'text');
    newInput.setAttribute('class', 'form-input');

    document.addEventListener('DOMContentLoaded', function () {
      editbutton.forEach(node => node.onclick = function (event) {
        const nodeId = event.target.id;

        if (nodeId === 'button-fname') {
          newInput.setAttribute('name', 'fname');
          newInput.setAttribute('placeholder', 'Enter your new first name');
          newSubmitButton.setAttribute('value', 'fname');
          this.after(newForm);
        } else if (nodeId === 'button-lname') {          
          newInput.setAttribute('name', 'lname');
          newInput.setAttribute('placeholder', 'Enter your new last name');
          newSubmitButton.setAttribute('value', 'lname');
          this.after(newForm);
        } else if (nodeId === 'button-phoneNum') {
          newInput.setAttribute('name', 'phoneNum');
          newInput.setAttribute('placeholder', 'Enter your new phone number');
          newSubmitButton.setAttribute('value', 'phoneNum');          
          this.after(newForm);
        } else {
          // do nothing
        }
      });
    });

    newForm.appendChild(newInput);
    newForm.appendChild(newSubmitButton);
    newForm.appendChild(newCancelButton);

  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
