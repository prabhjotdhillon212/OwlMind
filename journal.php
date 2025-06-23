<?php
session_start();
require_once "../inc/config.inc";
require_once ROOT_PATH . "inc/headtags.inc";
require_once ROOT_PATH . "inc/header.inc";
include 'db_connection.php';

// Flags
$entrySaved = false;
$saveError = false;

// Auth check
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
  echo "<p class='journal-error'>No student profile found.</p>";
  exit();
}

// Save entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['entry'])) {
  $title = htmlspecialchars(trim($_POST['title']));
  $entry = htmlspecialchars(trim($_POST['entry']));
  $stmt = $db->prepare("INSERT INTO Journal (studentID, title, entry) VALUES (?, ?, ?)");
  $stmt->bindValue(1, $studentID);
  $stmt->bindValue(2, $title);
  $stmt->bindValue(3, $entry);
  $entrySaved = $stmt->execute();
  $saveError = !$entrySaved;
}

// Fetch entries
$stmt = $db->prepare("SELECT * FROM Journal WHERE studentID = ? ORDER BY rowid DESC");
$stmt->bindValue(1, $studentID);
$entries = $stmt->execute();
?>

<style>
  .journal-wrapper {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 60px 20px;
    min-height: 100vh;
  }

  .journal-container {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    max-width: 1100px;
    width: 100%;
  }

  .journal-form,
  .journal-entries {
    flex: 1 1 480px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .journal-title {
    font-size: 2.4rem;
    font-weight: 800;
    color: #1d3557;
    margin-bottom: 12px;
  }

  .journal-subtext {
    font-size: 1rem;
    color: #666;
    margin-bottom: 24px;
  }

  .journal-entry-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

  .journal-entry-form input,
  .journal-entry-form textarea {
    width: 100%;
    background: #fff;
    color: #333;
    border: 1px solid #ccc;
    padding: 14px;
    border-radius: 10px;
    font-size: 1rem;
    resize: vertical;
    outline: none;
  }

  .journal-entry-form input:focus,
  .journal-entry-form textarea:focus {
    border: 1px solid #56cfe1;
    box-shadow: 0 0 0 3px rgba(86, 207, 225, 0.3);
  }

  .btn {
    padding: 12px 24px;
    background: #0077b6;
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 14px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .btn:hover {
    background: #005f8f;
  }

  .journal-success,
  .journal-error {
    text-align: center;
    margin-bottom: 12px;
    font-weight: 600;
  }

  .journal-success {
    color: #28a745;
  }

  .journal-error {
    color: #dc3545;
  }

  .journal-entries h2 {
    color: #1d3557;
    font-size: 1.5rem;
    margin-bottom: 20px;
  }

  .journal-card {
    background: #fff;
    border-left: 4px solid #56cfe1;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    margin-bottom: 15px;
  }

  .journal-card h3 {
    margin: 0 0 8px;
    color: #0077b6;
    font-size: 1rem;
  }

  .journal-card p {
    color: #333;
    font-size: 0.95rem;
  }

  .empty-msg {
    color: #999;
    font-style: italic;
    text-align: center;
  }
</style>

<main class="journal-wrapper">
  <section class="journal-container">

    <!-- Journal Form -->
    <div class="journal-form">
      <h1 class="journal-title">Daily Reflections</h1>
      <p class="journal-subtext">Take a moment and write how you’re feeling today.</p>

      <?php if ($entrySaved): ?>
        <p class="journal-success">✅ Entry saved!</p>
      <?php elseif ($saveError): ?>
        <p class="journal-error">❌ Something went wrong. Please try again.</p>
      <?php endif; ?>

      <form method="POST" class="journal-entry-form">
        <input type="text" name="title" placeholder="Title (e.g. 'Feeling anxious')" required>
        <textarea name="entry" placeholder="Start writing your thoughts..." rows="6" required></textarea>
        <button type="submit" class="btn">💾 Save Reflection</button>
      </form>
    </div>

    <!-- Reflections List -->
    <div class="journal-entries">
      <h2>Your Reflections</h2>
      <?php
      $hasEntries = false;
      while ($row = $entries->fetchArray(SQLITE3_ASSOC)):
        $hasEntries = true;
      ?>
        <div class="journal-card">
          <h3><?= htmlspecialchars($row['title']) ?></h3>
          <p><?= nl2br(htmlspecialchars($row['entry'])) ?></p>
        </div>
      <?php endwhile; ?>

      <?php if (!$hasEntries): ?>
        <p class="empty-msg">No reflections saved yet.</p>
      <?php endif; ?>
    </div>

  </section>
</main>
