<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Owl Mind</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <!-- Navbar -->
  <header>
    <div class="nav">
      <a href="index.html" class="logo">Owl Mind</a>
    </div>
  </header>

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
  <footer>
    <p>&copy; 2025 Owl Mind</p>
  </footer>

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
</html>
