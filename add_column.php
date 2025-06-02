<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Includes/dbcon.php';

$query = "ALTER TABLE tbllecture ADD COLUMN passwordresetrequired TINYINT(1) NOT NULL DEFAULT 0";
if ($conn->query($query)) {
    echo "Column 'passwordresetrequired' added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}
?> 