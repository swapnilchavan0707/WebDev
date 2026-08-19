<?php
include "config/db.php";

$first = "Swapnil";
$last = "Chavan";
$role = "Super Admin";
$mobile = "9876543210";
$email = "admin@flowtrack.com";
$password = password_hash("swapnil@123", PASSWORD_DEFAULT);

$sql = "INSERT INTO users 
        (first_name, last_name, role, mobile_number, email, password)
        VALUES 
        (:first, :last, :role, :mobile, :email, :password)";

$stmt = $conn->prepare($sql);

$success = $stmt->execute([
    ':first'    => $first,
    ':last'     => $last,
    ':role'     => $role,
    ':mobile'   => $mobile,
    ':email'    => $email,
    ':password' => $password
]);

if ($success) {
    echo "Super Admin created successfully";
} else {
    echo "Error creating admin";
}
?>