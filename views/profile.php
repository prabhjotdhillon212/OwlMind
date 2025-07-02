<?php
  session_start();
  require_once "../inc/config.inc";

  require_once ROOT_PATH . "inc/headtags.inc";
  require_once ROOT_PATH . "inc/header.inc";
?>
<?php 
  include 'db_connection.php';

  $student_id = $_SESSION['studentID'];

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $action = $_POST["action"] ?? '';

    switch($action) {
      case 'fname':

        $fname = $_POST["fname"] ?? '';

        $updatefname = $db->prepare("UPDATE Student SET fname=:fname WHERE studentID=$student_id");
        $updatefname->bindParam(':fname', $fname, SQLITE3_TEXT);
        $updatefname->execute();
        header("Location: profile.php");
        $_SESSION['fname'] = $fname;
        break;

      case 'lname':

        $lname = $_POST["lname"] ?? '';
        
        $updatelname = $db->prepare("UPDATE Student SET lname=:lname WHERE studentID=$student_id");
        $updatelname->bindParam(':lname', $lname, SQLITE3_TEXT);
        $updatelname->execute();
        header("Location: profile.php");
        $_SESSION['lname'] = $lname;
        break;
      
      case 'phoneNum':

        $phoneNum = $_POST["phoneNum"] ?? '';

        if (strlen($phoneNum) != 10 || !(is_numeric($phoneNum))) {
          $err = "Phone numbers must be 10 digits. Enter numbers only.";
        } else {
          $updatephone = $db->prepare("UPDATE Student SET phoneNum=:phoneNum WHERE studentID=$student_id");
          $updatephone->bindParam(':phoneNum', $phoneNum, SQLITE3_TEXT);
          $updatephone->execute();
          header("Location: profile.php");
          $_SESSION['phoneNum'] = $phoneNum;
          break;
        }
      default:
        echo "Invalid action.";
    } 
  }
  
  $db->close();
?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <!-- Navbar -->
  <main>
    <div class="main-card fade-in">
      <h1 class="hero-heading" style="text-align: center;"><?php echo $_SESSION['fname']; ?>'s Profile</h1>
      <?php
        if (isset($err)) {
          echo "<div style='color: red'>" . $err . "</div>";
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
          <a href="reset_pass.php">Reset Password</a>  
        </fieldset>             
      </form>
    </div>
  </main>
    <!-- Footer -->
  <?php include ROOT_PATH . "inc/footer.inc"; ?>

  <script>
    const editbutton = document.querySelectorAll('button');

    const newForm = document.createElement("form");
    newForm.setAttribute('method', 'POST');
    newForm.setAttribute('class', 'form');
    newForm.setAttribute('id', 'signupForm');
    newForm.style.marginTop = '5%';

    const newSubmitButton = document.createElement('button');
    newSubmitButton.setAttribute('type', 'submit');
    newSubmitButton.setAttribute('class', 'btn');
    newSubmitButton.setAttribute('style', 'background-color: rgb(255, 204, 64)');
    newSubmitButton.setAttribute('name', 'action');
    newSubmitButton.textContent = 'Submit';

    const newInput = document.createElement('input');
    newInput.setAttribute('type', 'text');

    document.addEventListener('DOMContentLoaded', function () {
      editbutton.forEach(node => node.onclick = function (event) {
        const nodeId = event.target.id;

        if (nodeId === 'button-fname') {
          newInput.setAttribute('name', 'fname');
          newInput.setAttribute('placeholder', 'Enter your new first name');
          newSubmitButton.setAttribute('value', 'fname');
          newForm.appendChild(newInput);
          newForm.appendChild(newSubmitButton);
          this.after(newForm);
        } else if (nodeId === 'button-lname') {          
          newInput.setAttribute('name', 'lname');
          newInput.setAttribute('placeholder', 'Enter your new last name');
          newSubmitButton.setAttribute('value', 'lname');
          newForm.appendChild(newInput);
          newForm.appendChild(newSubmitButton);
          this.after(newForm);
        } else if (nodeId === 'button-phoneNum') {
          newInput.setAttribute('name', 'phoneNum');
          newInput.setAttribute('placeholder', 'Enter your new phone number');
          newSubmitButton.setAttribute('value', 'phoneNum');
          newForm.appendChild(newInput);
          newForm.appendChild(newSubmitButton);
          this.after(newForm);
        } else {
          // do nothing.
        }
      });
    });
  </script>
</body>
