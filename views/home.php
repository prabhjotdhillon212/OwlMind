<?php require_once "../inc/config.inc"; ?>
<?php
// TEMPORARY: Dev preview — remove this once login system is active
if (!isset($_SESSION['email'])) {
  $_SESSION['first_name'] = "Prabhjot";
  $_SESSION['email'] = "you@southernct.edu";
}

require_once(ROOT_PATH . "inc/headtags.inc");
require_once(ROOT_PATH . "inc/header.inc");
?>
<body>

  </main>
    <!-- Footer -->
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
</body>