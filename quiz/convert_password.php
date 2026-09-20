<?php
require_once 'config/db.php';

$username = "swapnil";
$newPassword = "swapnil";

$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// UPDATE QUERY
$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
$result = $stmt->execute([$hashed, $username]);

if ($result) {
    echo "Update executed successfully<br>";
    echo "Rows affected: " . $stmt->rowCount();
} else {
    echo "Update failed";
}
?>