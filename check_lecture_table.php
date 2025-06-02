<?php
include 'Includes/dbcon.php';

// Check table structure
$query = "DESCRIBE tbllecture";
$result = $conn->query($query);

if ($result) {
    echo "Table structure for tbllecture:\n";
    while ($row = $result->fetch_assoc()) {
        echo "Field: " . $row['Field'] . 
             "\nType: " . $row['Type'] . 
             "\nNull: " . $row['Null'] . 
             "\nKey: " . $row['Key'] . 
             "\nDefault: " . $row['Default'] . 
             "\nExtra: " . $row['Extra'] . "\n\n";
    }
} else {
    echo "Error getting table structure: " . $conn->error;
}

// Also check a sample record
$query2 = "SELECT * FROM tbllecture LIMIT 1";
$result2 = $conn->query($query2);

if ($result2 && $result2->num_rows > 0) {
    echo "\nSample record:\n";
    $row = $result2->fetch_assoc();
    foreach ($row as $key => $value) {
        echo "$key: $value\n";
    }
} else {
    echo "\nNo records found in tbllecture table.";
}

$conn->close();
?> 