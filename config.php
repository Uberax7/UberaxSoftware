<?php
session_start();

// Shopier API bilgileri
define('SHOPIER_API_KEY', 'sohpier_keyini_gir');
define('SHOPIER_API_SECRET', 'secret_gir');

// Admin bilgileri
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'password123');

// Dosya yolları
define('DATA_DIR', __DIR__ . '/data/');
define('PRODUCTS_FILE', DATA_DIR . 'products.json');
define('ORDERS_FILE', DATA_DIR . 'orders.json');

// JSON dosyalarını oluştur
if (!file_exists(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
if (!file_exists(PRODUCTS_FILE)) file_put_contents(PRODUCTS_FILE, '[]');
if (!file_exists(ORDERS_FILE)) file_put_contents(ORDERS_FILE, '[]');

// Hata raporlama
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Shopier autoload
spl_autoload_register(function ($class) {
    $prefix = 'Shopier\\';
    $base_dir = __DIR__ . '/shopier/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
?>