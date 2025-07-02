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
          <input type="text" value="<?php echo $_SESSION['fname']; ?>" />
          <button class="edit-button btn" id="button-fname" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="lname">Last Name:</label>
          <input type="text" value="<?php echo $_SESSION['lname']; ?>" />
          <button class="edit-button btn" id="button-lname" type="button">Edit</button>
        </fieldset>
        <fieldset class="field">
          <label for="phoneNum">Phone Number:</label>
          <input class="input" type="text" value="<?php echo $_SESSION['phoneNum']; ?>" />
          <button class="edit-button btn" id="button-phoneNum" type="button">Edit</button>
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

    const editbutton = document.getElementsByClassName("edit-button");

    const newForm = document.createElement("form");
    newForm.setAttribute('action', '');
    newForm.setAttribute('method', 'POST');
    newForm.setAttribute('class', 'form');
    newForm.style.marginTop = '5%';

    const newInput = document.createElement('input');
    newInput.setAttribute('type', 'text');
    
    for (let z = 0; z < editbutton.length; z++) {
      if (editbutton[z].id === 'button-fname') {
        newInput.setAttribute('name', 'fname');
        newInput.setAttribute('placeholder', 'Enter your new first name');
      } else if (editbutton[z].id === 'button-lname') {
        newInput.setAttribute('name', 'lname');
        newInput.setAttribute('placeholder', 'Enter your new last name');
      } else if (editbutton[z].id === 'button-phoneNum') {
        newInput.setAttribute('name', 'phoneNum');
        newInput.setAttribute('placeholder', 'Enter your new phone number');
      } else {
        newInput.setAttribute = ('name', 'name');
      }
    };
    
    const newSubmitButton = document.createElement('button');
    newSubmitButton.setAttribute('type', 'submit');
    newSubmitButton.setAttribute('class', 'btn');
    newSubmitButton.setAttribute('style', 'background-color: rgb(255, 204, 64)');
    newSubmitButton.textContent = 'Submit';

    newForm.appendChild(newInput);
    newForm.appendChild(newSubmitButton);

    for (let i = 0; i < editbutton.length; i++) {
      editbutton[i].addEventListener('click', function() {
        this.after(newForm);
      });
    };
  </script>
</body>
