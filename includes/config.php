<?php
// Configuración de ejemplo para XAMPP. config.local.php no se sube a Git.
$config = ['host' => '127.0.0.1', 'port' => '3306', 'database' => 'proyecto_gestion',
    'user' => 'root', 'password' => ''];
if (file_exists(__DIR__ . '/config.local.php')) {
    $config = array_replace($config, require __DIR__ . '/config.local.php');
}
