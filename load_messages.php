<?php
require_once "db.php";

$name = isset($_GET['name']) ? trim($_GET['name']) : '';

if ($name === '') {
    echo "Enter a name to retrieve chat.";
    $conn->close();
    exit;
}

$stmt = $conn->prepare(
    "SELECT message FROM chat_messages WHERE username = ? LIMIT 1"
);
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->bind_result($message);

if ($stmt->fetch()) {
    echo $message;
} else {
    echo "No chat found for " . htmlspecialchars($name);
}

$stmt->close();
$conn->close();
?>
