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
          <a href="reset_pass.php">Reset Password</a>  
        </fieldset>             
      </form>
    </div>
  </main>
    <!-- Footer -->
  <?php include ROOT_PATH . "inc/footer.inc"; ?>

  // Dynamic form for editing information:
  <script>
    const editbutton = document.querySelectorAll('button');

    const newForm = document.createElement("form");
    newForm.setAttribute('action', '');
    newForm.setAttribute('method', 'POST');
    newForm.setAttribute('class', 'form');
    newForm.style.marginTop = '5%';

    const newSubmitButton = document.createElement('button');
    newSubmitButton.setAttribute('type', 'submit');
    newSubmitButton.setAttribute('class', 'btn');
    newSubmitButton.setAttribute('style', 'background-color: rgb(255, 204, 64)');
    newSubmitButton.textContent = 'Submit';

    const newInput = document.createElement('input');
    newInput.setAttribute('type', 'text');

    document.addEventListener('DOMContentLoaded', function () {
      editbutton.forEach(node => node.onclick = function (event) {
        const nodeId = event.target.id;

        if (nodeId === 'button-fname') {
          newInput.setAttribute('name', 'fname');
          newInput.setAttribute('placeholder', 'Enter your new first name');
          newForm.appendChild(newInput);
          newForm.appendChild(newSubmitButton);
          this.after(newForm);
        } else if (nodeId === 'button-lname') {          
          newInput.setAttribute('name', 'lname');
          newInput.setAttribute('placeholder', 'Enter your new last name');
          newForm.appendChild(newInput);
          newForm.appendChild(newSubmitButton);
          this.after(newForm);
        } else if (nodeId === 'button-phoneNum') {
          newInput.setAttribute('name', 'phoneNum');
          newInput.setAttribute('placeholder', 'Enter your new phone number');
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
