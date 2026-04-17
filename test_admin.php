<?php
require 'vendor/autoload.php';
require 'app/Config/Database.php';

use Config\Database;

$db = Database::connect();

$user = $db->table('usuarios')->where('username', 'admin')->get()->getRowArray();

if ($user) {
    echo "User found: " . $user['username'] . " - Role: " . $user['rol'] . " - Status: " . $user['estado'] . PHP_EOL;
    echo "Password valid: " . (password_verify('admin123', $user['password']) ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo "User not found" . PHP_EOL;
}
?>