<?php
session_start();
include 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$newsletter_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (update_newsletter($newsletter_id, $title, $description)) {
        $_SESSION['message'] = "Nyhetsbrevet uppdaterades!";
        header("Location: newsletters.php");
        exit;
    } else {
        $error_message = "Kunde inte uppdatera nyhetsbrevet. Försök igen.";
    }
}

$newsletter = get_newsletter_by_id($newsletter_id);

if (!$newsletter) {
    echo "Nyhetsbrev hittades inte.";
    exit;
}

include("header.php");
?>

<form method="POST">
    <label for="title">Titel:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($newsletter['title']); ?>" required>
    <br>
    <label for="description">Beskrivning:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($newsletter['description']); ?></textarea>
    <br>
    <input type="submit" value="Uppdatera">
</form>

<?php
if (isset($error_message)) {
    echo "<p>$error_message</p>";
}
?>
