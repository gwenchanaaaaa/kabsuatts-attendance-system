<?php
echo "Loaded extensions:\n";
print_r(get_loaded_extensions());
echo "\nPHP.ini location:\n";
echo php_ini_loaded_file();
echo "\n\nMySQLi extension loaded: " . (extension_loaded('mysqli') ? 'Yes' : 'No');
?> 