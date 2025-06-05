<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Log In - Owl Mind</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <header>
    <div class="nav">
      <a href="index.html" class="logo">Owl Mind</a>
    </div>
  </header>

  <main>
    <div class="main-card">
      <h1>Log In</h1>
      <form action="/login" method="POST" class="form">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" class="btn">Log In</button>
        <p class="form-footer">Don't have an account? <a href="signup.html">Sign Up</a></p>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Owl Mind</p>
  </footer>
</body>
</html>
