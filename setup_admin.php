<?php
include 'Includes/dbcon.php';

// First, check if admin exists
$checkQuery = "SELECT * FROM tbladmin WHERE emailAddress = 'admin@gmail.com'";
$result = $conn->query($checkQuery);

$password = '@admin_';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

if ($result->num_rows > 0) {
    // Update existing admin
    $query = "UPDATE tbladmin SET 
              password = ?,
              firstName = 'Admin',
              lastName = 'User',
              emailAddress = 'admin@gmail.com'
              WHERE emailAddress = 'admin@gmail.com'";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $hashedPassword);
} else {
    // Create new admin
    $query = "INSERT INTO tbladmin (firstName, lastName, emailAddress, password) 
              VALUES ('Admin', 'User', 'admin@gmail.com', ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $hashedPassword);
}

if ($stmt->execute()) {
    echo "Admin account setup/updated successfully!\n";
    
    // Verify the password hash
    $verifyQuery = "SELECT password FROM tbladmin WHERE emailAddress = 'admin@gmail.com'";
    $verifyResult = $conn->query($verifyQuery);
    $admin = $verifyResult->fetch_assoc();
    
    echo "Verifying password...\n";
    if (password_verify($password, $admin['password'])) {
        echo "Password verification successful!\n";
    } else {
        echo "Warning: Password verification failed!\n";
    }
} else {
    echo "Error setting up admin account: " . $conn->error;
}

$stmt->close();
$conn->close();
?> 