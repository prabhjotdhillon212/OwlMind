<?php require_once "../inc/config.inc"; ?>

<?php include ROOT_PATH . "inc/headtags.inc"; ?>
<body>
  <!-- Navbar -->
  <?php include ROOT_PATH . "inc/header.inc"; ?>
  <main>
    <div class="main-card fade-in">
        <h1>Reset Successful!</h1>
        <h2>Redirecting...</h2>
        <script>
            window.setTimeout(function() {
                window.location = "../index.php";
            }, 5000
        );
        </script>
    </div>
  </main>
    <!-- Footer -->
  <div class="push"></div>
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
</body>