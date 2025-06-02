<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Includes/dbcon.php';

$query = "SHOW COLUMNS FROM tbllecture";
$result = $conn->query($query);

if ($result) {
    echo "Table structure for tbllecture:\n\n";
    while ($row = $result->fetch_assoc()) {
        echo "Field: " . $row['Field'] . "\n";
        echo "Type: " . $row['Type'] . "\n";
        echo "Null: " . $row['Null'] . "\n";
        echo "Key: " . $row['Key'] . "\n";
        echo "Default: " . $row['Default'] . "\n";
        echo "Extra: " . $row['Extra'] . "\n\n";
    }
} else {
    echo "Error getting table structure: " . $conn->error;
}

$conn->close();
?> 