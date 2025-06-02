<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Includes/dbcon.php';

// Test with lecturer email and specific password
$email = 'czyrellabelita@gmail.com';
$test_password = 'gwentot';

// Check lecture table
$query = "SELECT * FROM tbllecture WHERE emailAddress = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "Lecturer found:\n";
    echo "ID: " . $user['Id'] . "\n";
    echo "Email: " . $user['emailAddress'] . "\n";
    echo "First Name: " . $user['firstName'] . "\n";
    echo "Last Name: " . $user['lastName'] . "\n";
    echo "Password Hash: " . $user['password'] . "\n";
    echo "Password Reset Required: " . (isset($user['passwordresetrequired']) ? $user['passwordresetrequired'] : 'Not set') . "\n";
    
    // Test password verification
    echo "\nPassword verification test:\n";
    echo "Testing password 'gwentot': " . (password_verify($test_password, $user['password']) ? 'Valid' : 'Invalid') . "\n";
    
    // Generate a new hash for comparison
    echo "\nNew hash generated for 'gwentot': " . password_hash($test_password, PASSWORD_DEFAULT) . "\n";
    
    // Update the password in the database
    $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
    $update_query = "UPDATE tbllecture SET password = ?, passwordresetrequired = 0 WHERE Id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_hash, $user['Id']);
    
    if ($update_stmt->execute()) {
        echo "\nPassword updated successfully!\n";
        echo "New password hash: " . $new_hash . "\n";
    } else {
        echo "\nError updating password: " . $conn->error . "\n";
    }
} else {
    echo "No lecturer found with email: $email\n";
}

// Show all columns in tbllecture table
echo "\nColumns in tbllecture table:\n";
$result = $conn->query("SHOW COLUMNS FROM tbllecture");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

$conn->close();
?> 