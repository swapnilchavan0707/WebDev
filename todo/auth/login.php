<?php
include "../config/db.php";
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo "error";
    exit;
}

try {

    // Secure query using PDO
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Session variables (correct user_id used)
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];

            echo "success";
        } else {
            echo "error";
        }

    } else {
        echo "error";
    }

} catch (PDOException $e) {
    echo "error";
}
?>