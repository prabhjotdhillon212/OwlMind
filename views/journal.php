<?php
session_start();
require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
include 'db_connection.php';

$entrySaved = false;
$saveError = false;

if (!isset($_SESSION['accountID'])) {
  header("Location: login.php");
  exit();
}

$accountID = $_SESSION['accountID'];
$stmt = $db->prepare("SELECT studentID FROM Student WHERE accountID = ?");
$stmt->bindValue(1, $accountID);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
$studentID = $row ? $row['studentID'] : null;

if (!$studentID) {
  echo "<p class='text-danger text-center fw-bold'>No student profile found.</p>";
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['entry'])) {
  $title = htmlspecialchars(trim($_POST['title']));
  $entry = htmlspecialchars(trim($_POST['entry']));
  $timestamp = date('Y-m-d H:i:s'); // current date + time

  $stmt = $db->prepare("INSERT INTO Journal (studentID, title, entry, timestamp) VALUES (?, ?, ?, ?)");
  $stmt->bindValue(1, $studentID);
  $stmt->bindValue(2, $title);
  $stmt->bindValue(3, $entry);
  $stmt->bindValue(4, $timestamp);
  $entrySaved = $stmt->execute();
  $saveError = !$entrySaved;
}

$stmt = $db->prepare("SELECT * FROM Journal WHERE studentID = ? ORDER BY rowid DESC");
$stmt->bindValue(1, $studentID);
$entries = $stmt->execute();
?>

<main class="d-flex justify-content-center align-items-start p-5" style="min-height: 100vh;">
  <section class="d-flex flex-wrap gap-4" style="max-width: 1100px; width: 100%;">
    
    <!-- Journal Form -->
    <div class="journal-form flex-fill bg-white p-4 shadow rounded-4" style="min-width: 300px;">
      <h1 class="fw-bold mb-2" style="color:#1d3557;">Daily Reflections</h1>
      <p class="text-muted mb-3">Take a moment and write how you’re feeling today.</p>

      <?php if ($entrySaved): ?>
        <p class="text-success fw-semibold">✅ Entry saved!</p>
      <?php elseif ($saveError): ?>
        <p class="text-danger fw-semibold">❌ Something went wrong. Please try again.</p>
      <?php endif; ?>

      <form method="POST" class="d-flex flex-column gap-3">
        <input type="text" name="title" class="form-control" placeholder="Title (e.g. 'Feeling anxious')" required>
        <textarea name="entry" class="form-control" placeholder="Start writing your thoughts..." rows="6" required></textarea>
        <button type="submit" class="btn btn-custom">💾 Save Reflection</button>
      </form>
    </div>

    <!-- Reflections List -->
    <div class="journal-entries flex-fill bg-white p-4 shadow rounded-4" style="min-width: 300px;">
      <h2 class="fw-bold mb-3" style="color: #1d3557;">Your Reflections</h2>
      <?php
      $hasEntries = false;
      while ($row = $entries->fetchArray(SQLITE3_ASSOC)):
        $hasEntries = true;
        $date = date('F j, Y \a\t g:i A', strtotime($row['timestamp']));
      ?>
        <div class="mb-3 p-3 border-start border-4 rounded-3 shadow-sm" style="border-color: #56cfe1; background: #fff;">
          <h3 class="fw-semibold mb-1" style="color: #0077b6;"><?= htmlspecialchars($row['title']) ?></h3>
          <p class="text-muted mb-2" style="font-size: 0.9rem;">🕒 <?= $date ?></p>
          <p class="mb-0"><?= nl2br(htmlspecialchars($row['entry'])) ?></p>
        </div>
      <?php endwhile; ?>

      <?php if (!$hasEntries): ?>
        <p class="text-muted fst-italic text-center">No reflections saved yet.</p>
      <?php endif; ?>
    </div>

  </section>
</main>
