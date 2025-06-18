<?php
session_start();
require_once "../inc/config.inc";
include ROOT_PATH . "inc/headtags.inc";
?>

<body>
<?php include ROOT_PATH . "inc/header.inc"; ?>

<main>
  <div class="main-card fade-in">
    <h1>My Journal</h1>

    <?php
    // Initialize journal in session if not set
    if (!isset($_SESSION['journal_entries'])) {
        $_SESSION['journal_entries'] = [];
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $entry = trim($_POST['entry']);
        if (!empty($entry)) {
            $timestamp = date("F j, Y, g:i a");
            array_unshift($_SESSION['journal_entries'], [
                'entry' => $entry,
                'timestamp' => $timestamp
            ]);
            echo "<p style='color: green;'>Journal entry saved!</p>";
        }
    }
    ?>

    <form method="POST" class="form">
        <textarea name="entry" rows="6" placeholder="Write your thoughts..." required></textarea>
        <button type="submit" class="btn">Save Entry</button>
    </form>

    <h3 style="margin-top: 30px;">Past Entries</h3>
    <ul style="text-align:left; padding-left: 20px;">
        <?php foreach ($_SESSION['journal_entries'] as $entry): ?>
            <li><strong><?php echo $entry['timestamp']; ?>:</strong><br><?php echo htmlspecialchars($entry['entry']); ?></li><br>
        <?php endforeach; ?>
    </ul>
  </div>
</main>

<?php include ROOT_PATH . "inc/footer.inc"; ?>
</body>
