<?php
session_start();

$auth_only_nav = true;

require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
include 'db_connection.php';
?>

<?php

if (isset($_POST['verify'])) {

    $verify_code = $_SESSION['code'];
    $user_input = $_POST['verify'];

    $account_id = $_SESSION['accountID'];

    if ($verify_code === $user_input) {
        $updateverify = $db->prepare("UPDATE Account SET verified = 1 WHERE accountID=$account_id");
        $updateverify->execute();
        header("Location: home.php");
        exit();
    } else {
        $err = "The code you entered is not correct.";
    }
}

?>

<body>
  <!-- Navbar -->
  <?php include ROOT_PATH . "inc/header.inc"; ?>

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success fw-semibold">
        <?= $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['warning_message'])): ?>
    <div class="alert alert-warning fw-semibold">
        <?= $_SESSION['warning_message']; ?>
    </div>
    <?php unset($_SESSION['warning_message']); ?>
    <?php endif; ?>
  <main>
    <div class="main-card profile-card fade-in">
        <h1>Hello, <?php echo $_SESSION['fname'] ?>!</h1>
        <h2>Enter the 5-digit verification code:</h2>
        <?php if (isset($err)): ?>
            <p class="fw-semibold text-danger"><?= $err; ?></p>
        <?php endif; ?>
        <form action="" method="POST" class="form">
            <input class="input" name="verify" id="verify" type="text" />
            <button type="submit" class="btn">Go!</button>
        </form>
    </div>
  </main>
    <!-- Footer -->
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>