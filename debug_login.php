<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Includes/dbcon.php';

// Check admin credentials in database
$query = "SELECT * FROM tbladmin WHERE emailAddress = 'admin@gmail.com'";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        echo "Admin found:\n";
        echo "Email: " . $admin['emailAddress'] . "\n";
        echo "Stored password hash: " . $admin['password'] . "\n";
        
        // Test password verification
        $test_password = '@admin_';
        $is_valid = password_verify($test_password, $admin['password']);
        echo "\nPassword '@admin_' verification result: " . ($is_valid ? 'Valid' : 'Invalid') . "\n";
        
        // Show password hash for comparison
        echo "Generated hash for '@admin_': " . password_hash($test_password, PASSWORD_DEFAULT) . "\n";
    } else {
        echo "No admin account found";
    }
} else {
    echo "Query error: " . $conn->error;
}
?> 