<?php
include 'Includes/dbcon.php';
session_start();

$hashedPassword = password_hash('@admin_', PASSWORD_BCRYPT);
echo password_hash('@admin_', PASSWORD_BCRYPT);

?>
