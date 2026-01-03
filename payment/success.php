<?php
require_once '../config.php';
require_once '../functions.php';

$orderId = $_GET['order_id'] ?? '';

$orders = getOrders();
$order = null;

foreach ($orders as $o) {
    if ($o['id'] == $orderId) {
        $order = $o;
        break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Başarılı</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .success-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .success-box {
            background: var(--surface);
            padding: 3rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--accent);
            max-width: 500px;
            width: 100%;
        }
        .success-icon {
            color: var(--accent);
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .btn {
            display: inline-block;
            background: var(--accent);
            color: black;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 1.5rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 1rem;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 255, 136, 0.3);
        }
        .btn.discord {
            background: var(--accent2);
            margin-left: 1rem;
        }
        .order-details {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
        }
        .orders-link {
            display: block;
            margin-top: 1rem;
            color: var(--accent);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-box">
            <div class="success-icon">✓</div>
            <h2>Ödeme Başarılı!</h2>
            
            <?php if ($order): ?>
                <div class="order-details">
                    <div>
                        <span><strong>Sipariş ID:</strong></span>
                        <span><?php echo htmlspecialchars($order['id']); ?></span>
                    </div>
                    <div>
                        <span><strong>Ürün:</strong></span>
                        <span><?php echo htmlspecialchars($order['product_name']); ?></span>
                    </div>
                    <div>
                        <span><strong>Fiyat:</strong></span>
                        <span>₺<?php echo htmlspecialchars($order['price']); ?></span>
                    </div>
                    <div>
                        <span><strong>Durum:</strong></span>
                        <span style="color: var(--accent);">Ödeme Onaylandı</span>
                    </div>
                </div>
                
                <p style="color: var(--text-dim); margin-top: 1rem;">
                    Ürün bilgileri ve kurulum talimatları en kısa sürede e-posta adresinize gönderilecektir.<br>
                    Siparişlerinizi aşağıdaki linkten takip edebilirsiniz.
                </p>
                
                <a href="../orders.php?email=<?php echo urlencode($order['customer_email']); ?>" class="orders-link">
                    📦 Siparişlerimi Görüntüle
                </a>
            <?php else: ?>
                <p style="color: var(--text-dim);">Sipariş bilgileri yükleniyor...</p>
            <?php endif; ?>
            
            <div>
                <a href="../index.php" class="btn">Ana Sayfaya Dön</a>
                <button onclick="openDiscord()" class="btn discord">Discord'a Katıl</button>
            </div>
        </div>
    </div>

    <script>
        function openDiscord() {
            window.open('https://discord.gg/YourServerLink', '_blank');
        }
    </script>
</body>
</html>