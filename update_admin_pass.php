<?php
include 'Includes/dbcon.php';

$password = '@admin_';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$query = "UPDATE tbladmin SET password = ? WHERE emailAddress = 'admin@gmail.com'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $hashedPassword);

if ($stmt->execute()) {
    echo "Admin password updated successfully!";
} else {
    echo "Error updating password: " . $conn->error;
}

$stmt->close();
$conn->close();
?> 