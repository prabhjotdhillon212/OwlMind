<?php
  session_start();
  require_once "../inc/config.inc";

  require_once ROOT_PATH . "inc/headtags.inc";
  require_once ROOT_PATH . "inc/header.inc";
?>
<?php 
  include 'db_connection.php';
?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <!-- Navbar -->
  <main>
    <div class="main-card fade-in">
      <h1 class="hero-heading" style="text-align: center;"><?php echo $_SESSION['fname']; ?>'s Profile</h1>
      <form class="form">
        <fieldset class="field">
          <label for="fname">First Name:</label>
          <input type="text" name="fname" value="<?php echo $_SESSION['fname']; ?>" />
          <button class="edit-button btn" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="lname">Last Name:</label>
          <input type="text" id="lname" value="<?php echo $_SESSION['lname']; ?>" />
          <button class="edit-button btn" id="lname" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="phoneNum">Phone Number:</label>
          <input class="input" type="text" id="phoneNum" value="<?php echo $_SESSION['phoneNum']; ?>" />
          <button class="edit-button btn" id="phoneNum" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="emailbox">Email Address:</label>
          <input class="input" type="text" id="emailbox" value="<?php echo $_SESSION['email']; ?>" disabled="disabled" />
        </fieldset>
        <fieldset class="field">
          <label for="studentID">Student ID:</label>
          <input class="input" type="text" id="studentID" value="<?php echo $_SESSION['studentID']; ?>" disabled="disabled" />
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
    const editbutton = document.getElementsByClassName("edit-button btn");
    const parentelement = document.getElementsByClassName("field");
    // const inputform = document.getElementsByClassName("input");

    const newForm = document.createElement("form");
    newForm.setAttribute('action', '');
    newForm.setAttribute('method', 'POST');
    newForm.setAttribute('class', 'form');

    const newInput = document.createElement('input');
    newInput.setAttribute('type', 'text');

    const newSubmitButton = document.createElement('button');
    newSubmitButton.setAttribute('type', 'submit');
    newSubmitButton.setAttribute('class', 'btn');
    newSubmitButton.setAttribute('style', 'background-color: rgb(255, 204, 64)');
    newSubmitButton.textContent = 'Submit';

    newForm.appendChild(newInput);
    newForm.appendChild(newSubmitButton);

    for (let i = 0; i < editbutton.length; i++) {
      editbutton[i].addEventListener('click', function() {
        for (let j = 0; j < parentelement.length; j++) {
          parentelement[j].appendChild(newForm);
        };
      });
    };
  </script>
</body>
