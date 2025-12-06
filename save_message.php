<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db.php";

$username      = isset($_POST['username']) ? trim($_POST['username']) : '';
$user_password = isset($_POST['user_password']) ? trim($_POST['user_password']) : '';
$message       = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($username === '' || $user_password === '') {
    echo "MISSING";
    exit;
}

$stmt = $conn->prepare(
    "UPDATE chat_messages SET message = ? WHERE username = ? AND user_password = ?"
);
$stmt->bind_param("sss", $message, $username, $user_password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "OK";
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

$verify = $conn->prepare(
    "SELECT username FROM chat_messages WHERE username = ? AND user_password = ? LIMIT 1"
);
$verify->bind_param("ss", $username, $user_password);
$verify->execute();
$verify->store_result();

if ($verify->num_rows > 0) {
    echo "OK";
    $verify->close();
    $conn->close();
    exit;
}

$verify->close();

$check = $conn->prepare(
    "SELECT username FROM chat_messages WHERE username = ?"
);
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "WRONG";
    $check->close();
    $conn->close();
    exit;
}

$check->close();

$ins = $conn->prepare(
    "INSERT INTO chat_messages (username, user_password, message) VALUES (?, ?, ?)"
);
$ins->bind_param("sss", $username, $user_password, $message);

if ($ins->execute()) {
    echo "OK_NEW";
} else {
    echo "DB_ERROR";
}

$ins->close();
$conn->close();
?>
