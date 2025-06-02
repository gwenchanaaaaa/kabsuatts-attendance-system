<?php
session_start(); 

// Check if user is logged in and is an admin
if (!isset($_SESSION['userId']) || !isset($_SESSION['userType']) || ($_SESSION['userType'] !== 'admin' && $_SESSION['userType'] !== 'instructor'))
{
    // Clear any existing session data
    session_unset();
    session_destroy();
    
    echo "<script type = \"text/javascript\">
    window.location = (\"../index.php\");
    </script>";
    exit();
}

    
?>