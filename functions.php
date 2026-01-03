<?php
require_once 'config.php';

function getProducts() {
    return json_decode(file_get_contents(PRODUCTS_FILE), true) ?: [];
}

function saveProducts($products) {
    file_put_contents(PRODUCTS_FILE, json_encode($products, JSON_PRETTY_PRINT));
}

function getOrders() {
    return json_decode(file_get_contents(ORDERS_FILE), true) ?: [];
}

function saveOrders($orders) {
    file_put_contents(ORDERS_FILE, json_encode($orders, JSON_PRETTY_PRINT));
}

function generateOrderId() {
    return 'ORD' . date('YmdHis') . rand(1000, 9999);
}

function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function getProductById($id) {
    $products = getProducts();
    foreach ($products as $product) {
        if ($product['id'] == $id) {
            return $product;
        }
    }
    return null;
}

function getOrdersByEmail($email) {
    $orders = getOrders();
    return array_filter($orders, function($order) use ($email) {
        return $order['customer_email'] === $email && $order['status'] === 'completed';
    });
}

function updateOrderLicense($orderId, $licenseKey, $downloadLink) {
    $orders = getOrders();
    foreach ($orders as &$order) {
        if ($order['id'] === $orderId) {
            $order['license_key'] = $licenseKey;
            $order['download_link'] = $downloadLink;
            $order['updated_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
    saveOrders($orders);
}
?>