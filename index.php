<?php 
header('Content-Type: text/html; charset=utf-8');
include 'db.php';

// Funkce 1 pro výpis zpráv
function fetchMessages($conn, $categoryFilter = null) {
    $sql = "SELECT name, message, created_at, category FROM messages";
    
    if ($categoryFilter && in_array($categoryFilter, ['general', 'question', 'compliment', 'complaint'])) {
        $sql .= " WHERE category = '$categoryFilter'";
    }
    
    $sql .= " ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $messages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    return $messages;
}

// Funkce 2 – počet zpráv podle uživatele
function getMessageCountsByUser($messages) {
    $counts = [];
    foreach ($messages as $msg) {
        $name = $msg['name'];
        if (!isset($counts[$name])) {
            $counts[$name] = 0;
        }
        $counts[$name]++;
    }
    arsort($counts);
    return $counts;
}

// Funkce 3 – statistiky kategorií
function getCategoryStats($messages) {
    $stats = ['general' => 0, 'question' => 0, 'compliment' => 0, 'complaint' => 0];
    foreach ($messages as $msg) {
        if (isset($stats[$msg['category']])) {
            $stats[$msg['category']]++;
        }
    }
    return $stats;
}

$currentCategory = $_GET['category'] ?? null;
$messages = fetchMessages($conn, $currentCategory);
$messageCounts = getMessageCountsByUser($messages); // pole č. 1
$categoryStats = getCategoryStats($messages); // pole č. 2

// Získání unikátních jmen (pro výpis v seznamu autorů)
$uniqueNames = array_keys($messageCounts); // pole č. 3

// Posledních 5 jmen pro patičku
$recentNames = array_slice(array_column($messages, 'name'), 0, 5); // pole č. 4

// Top 3 autoři
$topAuthors = array_slice($messageCounts, 0, 3); // pole č. 5
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Kniha návštěv</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Kniha návštěv</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                <?php
                // Cyklus 1 – výpis chyb
                $errors = [
                    'empty_fields' => 'Vyplňte prosím všechny pole.',
                    'name_too_long' => 'Jméno je příliš dlouhé (max 30 znaků).',
                    'spam_detected' => 'Příliš mnoho zpráv v krátkém čase.',
                    'db_error' => 'Chyba databáze, zkuste to prosím později.'
                ];
                
                foreach ($errors as $errorCode => $errorMessage) {
                    if ($_GET['error'] === $errorCode) {
                        echo htmlspecialchars($errorMessage);
                        break;
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="insert.php" method="post" class="form">
            <input type="text" name="name" placeholder="Vaše jméno" required maxlength="30">
            <textarea name="message" placeholder="Vaše zpráva" required></textarea>
            
            <select name="category">
                <option value="general">Obecná zpráva</option>
                <option value="question">Dotaz</option>
                <option value="compliment">Pochvala</option>
                <option value="complaint">Stížnost</option>
            </select>
            
            <button type="submit">Odeslat</button>
        </form>

        <div class="stats">
            <h2>Statistiky</h2>
            <ul>
                <?php
                // Cyklus 2 – výpis statistik kategorií
                foreach ($categoryStats as $category => $count) {
                    $displayName = ucfirst($category);
                    echo "<li>$displayName: $count zpráv</li>";
                }
                ?>
            </ul>
            
            <h3>Top autoři</h3>
            <ol>
                <?php
                // Cyklus 3 – výpis top autorů
                foreach ($topAuthors as $author => $count) {
                    $safeAuthor = htmlspecialchars($author);
                    echo "<li>$safeAuthor ($count zpráv)</li>";
                }
                ?>
            </ol>
        </div>

        <div class="filter">
            <h2>Filtrovat zprávy</h2>
            <a href="index.php">Vše</a>
            <?php
            // Větvení 1 – aktivní filtr
            foreach (['general', 'question', 'compliment', 'complaint'] as $cat) {
                $active = ($currentCategory === $cat) ? 'active' : '';
                $displayCat = ucfirst($cat);
                echo "<a href='index.php?category=$cat' class='$active'>$displayCat</a>";
            }
            ?>
        </div>

        <div class="boxes">
            <?php
            // Větvení 2 – žádné zprávy
            if (count($messages) === 0) {
                echo "<p>Žádné zprávy k zobrazení.</p>";
            } else {
                // Cyklus 4 – výpis zpráv
                foreach ($messages as $msg) {
                    $name = htmlspecialchars($msg['name']);
                    $message = htmlspecialchars($msg['message']);
                    $date = $msg['created_at'];
                    $category = $msg['category'];
                    
                    // Větvení 3 – zkrácení dlouhých zpráv
                    $shortMsg = (strlen($message) > 200) ? substr($message, 0, 200) . "..." : $message;
                    
                    echo "<div class='message-box $category'>
                            <h3>$name <span class='category'>($category)</span></h3>
                            <p>$shortMsg</p>
                            <small>$date</small>
                          </div>";
                }
            }
            ?>
        </div>

        <footer>
            <hr>
            <h3>Poslední návštěvníci:</h3>
            <ul>
                <?php foreach ($recentNames as $visitorName): ?>
                    <li><?= htmlspecialchars($visitorName) ?></li>
                <?php endforeach; ?>
            </ul>
        </footer>
    </div>
</body>
</html>