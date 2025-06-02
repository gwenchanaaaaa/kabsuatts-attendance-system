<?php
include 'Includes/dbcon.php';

// Alter table to increase password field length
$query = "ALTER TABLE tbladmin MODIFY password VARCHAR(255) NOT NULL";
if ($conn->query($query)) {
    echo "Table structure updated successfully!\n";
    
    // Now let's update the admin password again
    $password = '@admin_';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $updateQuery = "UPDATE tbladmin SET password = ? WHERE emailAddress = 'admin@gmail.com'";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("s", $hashedPassword);
    
    if ($stmt->execute()) {
        echo "Admin password updated successfully!\n";
        
        // Verify the password
        $verifyQuery = "SELECT password FROM tbladmin WHERE emailAddress = 'admin@gmail.com'";
        $result = $conn->query($verifyQuery);
        $admin = $result->fetch_assoc();
        
        if (password_verify($password, $admin['password'])) {
            echo "Password verification successful! You can now log in with:\n";
            echo "Email: admin@gmail.com\n";
            echo "Password: @admin_\n";
        } else {
            echo "Warning: Password verification failed!\n";
        }
    } else {
        echo "Error updating password: " . $conn->error;
    }
} else {
    echo "Error updating table structure: " . $conn->error;
}

$conn->close();
?> 