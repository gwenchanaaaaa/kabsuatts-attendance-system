<?php
include 'Includes/dbcon.php';

// Check if admin exists and get their details
$query = "SELECT * FROM tbladmin WHERE emailAddress = 'admin@gmail.com'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "Admin found:\n";
    echo "Email: " . $admin['emailAddress'] . "\n";
    echo "Password hash length: " . strlen($admin['password']) . "\n";
    echo "Full hash: " . $admin['password'] . "\n";
} else {
    echo "No admin account found with email admin@gmail.com";
}

$conn->close();
?> 