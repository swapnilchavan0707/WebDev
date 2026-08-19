<?php
include "../config/db.php";

$first   = $_POST['first'] ?? '';
$last    = $_POST['last'] ?? '';
$mobile  = $_POST['mobile'] ?? '';
$email   = $_POST['email'] ?? '';
$passRaw = $_POST['password'] ?? '';
$role    = "Customer";

if (!$first || !$last || !$mobile || !$email || !$passRaw) {
    echo "error";
    exit;
}

$password = password_hash($passRaw, PASSWORD_DEFAULT);

try {

    $check = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $check->execute([':email' => $email]);

    if ($check->fetch()) {
        echo "exists";
        exit;
    }

    $sql = "INSERT INTO users 
            (first_name, last_name, mobile_number, email, password, role)
            VALUES 
            (:first, :last, :mobile, :email, :password, :role)";

    $stmt = $conn->prepare($sql);

    $result = $stmt->execute([
        ':first'    => $first,
        ':last'     => $last,
        ':mobile'   => $mobile,
        ':email'    => $email,
        ':password' => $password,
        ':role'     => $role
    ]);

    echo $result ? "success" : "error";

} catch (PDOException $e) {
    echo "error";
}
?>