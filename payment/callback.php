<?php
require_once '../config.php';
require_once '../functions.php';

use Shopier\Models\ShopierResponse;

try {
    // Shopier response'u al
    $response = ShopierResponse::fromPostData();
    
    // Loglama
    file_put_contents('../data/shopier_callback.txt', 
        "Callback: " . date('Y-m-d H:i:s') . "\n" .
        "Response: " . print_r($response->toArray(), true) . "\n\n",
        FILE_APPEND
    );
    
    // Signature doğrulama
    $isValid = $response->hasValidSignature(SHOPIER_API_SECRET);
    
    if ($isValid && $response->isSuccess()) {
        $orderId = $response->getPlatformOrderId();
        $orders = getOrders();
        
        // Siparişi güncelle
        foreach ($orders as &$order) {
            if ($order['id'] == $orderId) {
                $order['status'] = 'completed';
                $order['payment_date'] = date('Y-m-d H:i:s');
                $order['payment_id'] = $response->getPaymentId();
                $order['shopier_response'] = $response->toArray();
                break;
            }
        }
        
        saveOrders($orders);
        
        // Başarılı yanıt
        http_response_code(200);
        echo "OK";
    } else {
        // Hatalı signature veya başarısız ödeme
        http_response_code(400);
        echo "ERROR";
    }
    
} catch (Exception $e) {
    // Hata loglama
    file_put_contents('../data/shopier_callback_error.txt', 
        "Callback Error: " . date('Y-m-d H:i:s') . "\n" .
        "Message: " . $e->getMessage() . "\n" .
        "POST Data: " . print_r($_POST, true) . "\n\n",
        FILE_APPEND
    );
    
    http_response_code(500);
    echo "ERROR";
}
?>