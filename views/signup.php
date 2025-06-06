<?php include '../inc/config.inc'; ?>

<?php include '../inc/headtags.inc'; ?>
<body>
  <!-- Navbar -->
  <?php include '../inc/header.inc'; ?>

  <!-- Signup Form -->
  <main>
    <div class="main-card">
      <h1>Create an Account</h1>
      <form action="/signup" method="POST" class="form" id="signupForm">
        <input type="text" name="name" placeholder="Full Name" required />
        <input type="email" name="email" placeholder="Email (must end with @southernct.edu)" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="password" name="confirm" placeholder="Confirm Password" required />
        <p class="error" id="emailError" style="display: none;">Only southernct.edu emails are allowed.</p>
        <button type="submit" class="btn">Sign Up</button>
        <p class="form-footer">Already have an account? <a href="login.html">Log In</a></p>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <?php include '../inc/footer.inc'; ?>

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
