<?php
require_once '../config.php';
require_once '../functions.php';

use Shopier\Enums\Currency;
use Shopier\Enums\ProductType;
use Shopier\Models\Address;
use Shopier\Models\Buyer;
use Shopier\Shopier;

$productId = $_POST['product_id'] ?? '';
$customerEmail = $_POST['email'] ?? '';
$customerName = $_POST['name'] ?? '';

if (empty($productId) || empty($customerEmail) || empty($customerName)) {
    die("Eksik bilgi! Lütfen tüm alanları doldurun.");
}

$product = getProductById($productId);
if (!$product) {
    die("Ürün bulunamadı!");
}

// Sipariş oluştur
$orderId = generateOrderId();
$orders = getOrders();

$newOrder = [
    'id' => $orderId,
    'product_id' => $productId,
    'product_name' => $product['name'],
    'price' => $product['price'],
    'customer_email' => $customerEmail,
    'customer_name' => $customerName,
    'status' => 'pending',
    'date' => date('Y-m-d H:i:s')
];

$orders[] = $newOrder;
saveOrders($orders);

try {
    // Shopier SDK ile ödeme oluştur
    $shopier = new Shopier(SHOPIER_API_KEY, SHOPIER_API_SECRET);
    
    // Müşteri bilgileri
    $nameParts = explode(' ', $customerName, 2);
    $firstName = $nameParts[0];
    $lastName = $nameParts[1] ?? '.';
    
    // Buyer modelini doğru şekilde oluştur
    $buyer = new Buyer([
        'id' => rand(1000, 9999), // buyer_id_nr
        'name' => $firstName,     // buyer_name
        'surname' => $lastName,   // buyer_surname  
        'email' => $customerEmail, // buyer_email
        'phone' => '5555555555',  // buyer_phone
        'account_age' => 0        // buyer_account_age
    ]);
    
    // Adres bilgileri (zorunlu)
    $address = new Address([
        'address' => 'Test Mah. Test Sk. No:1', // address
        'city' => 'Istanbul',                   // city
        'country' => 'Turkey',                  // country
        'postcode' => '34000'                   // postcode
    ]);
    
    // Shopier parametrelerini ayarla
    $shopierParams = $shopier->getParams();
    $shopierParams->setBuyer($buyer);
    $shopierParams->setAddress($address);
    $shopierParams->setOrderData($orderId, $product['price'], getCallbackUrl());
    $shopierParams->setProductData($product['name'], ProductType::DIGITAL_PRODUCT);
    
    // Ödeme sayfasına yönlendir
    $shopier->go();
    
} catch (Exception $e) {
    // Hata durumunda logla ve hata göster
    file_put_contents('../data/shopier_error.txt', 
        "Error: " . date('Y-m-d H:i:s') . "\n" .
        "Message: " . $e->getMessage() . "\n" .
        "Trace: " . $e->getTraceAsString() . "\n\n",
        FILE_APPEND
    );
    
    echo "<h2>Ödeme Hatası</h2>";
    echo "<p>Ödeme işlemi sırasında bir hata oluştu: " . $e->getMessage() . "</p>";
    echo '<a href="../index.php">Ana Sayfaya Dön</a>';
}

function getCallbackUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname(dirname($_SERVER['PHP_SELF']));
    return $protocol . '://' . $host . $path . '/payment/callback.php';
}
?>