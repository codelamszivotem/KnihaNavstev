<?php
include 'db.php';

$name = $_POST['name'] ?? '';
$message = $_POST['message'] ?? '';
$category = $_POST['category'] ?? 'general';

// Větvení 1 – validace vstupu
if (trim($name) === '' || trim($message) === '') {
    header("Location: index.php?error=empty_fields");
    exit;
}

// Větvení 2 – kontrola délky jména
if (strlen($name) > 30) {
    header("Location: index.php?error=name_too_long");
    exit;
}

// Větvení 3 – kontrola kategorie
$allowedCategories = ['general', 'question', 'compliment', 'complaint'];
if (!in_array($category, $allowedCategories)) {
    $category = 'general';
}

// Funkce 1 – bezpečné vložení
function insertMessage($conn, $name, $message, $category) {
    $stmt = $conn->prepare("INSERT INTO messages (name, message, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $message, $category);
    return $stmt->execute();
}

// Funkce 2 – logování aktivity
function logActivity($conn, $username, $action) {
    $stmt = $conn->prepare("INSERT INTO activity_log (username, action) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $action);
    $stmt->execute();
}

// Funkce 3 – kontrola spamování
function isSpamming($conn, $username) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM messages 
                           WHERE name = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 5;
}

// Cyklus 1 – opakování při chybě připojení
$attempts = 0;
$maxAttempts = 3;
$inserted = false;

while ($attempts < $maxAttempts && !$inserted) {
    if (!isSpamming($conn, $name)) {
        $inserted = insertMessage($conn, $name, $message, $category);
        if ($inserted) {
            logActivity($conn, $name, 'new_message');
        }
    } else {
        header("Location: index.php?error=spam_detected");
        exit;
    }
    $attempts++;
}

if (!$inserted) {
    header("Location: index.php?error=db_error");
    exit;
}

header("Location: index.php?success=true");
exit;
?>