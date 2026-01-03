<?php
require_once '../config.php';
require_once '../functions.php';

$orderId = $_GET['order_id'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ödeme Başarısız</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .failed-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .failed-box {
            background: var(--surface);
            padding: 3rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #ff4444;
            max-width: 500px;
            width: 100%;
        }
        .failed-icon {
            color: #ff4444;
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
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 255, 136, 0.3);
        }
    </style>
</head>
<body>
    <div class="failed-container">
        <div class="failed-box">
            <div class="failed-icon">✗</div>
            <h2>Ödeme Başarısız!</h2>
            
            <p style="color: var(--text-dim); margin: 1rem 0;">
                Ödeme işleminiz sırasında bir hata oluştu.<br>
                Lütfen tekrar deneyin veya destek ekibiyle iletişime geçin.
            </p>
            
            <?php if ($orderId): ?>
                <p style="font-size: 0.9rem; color: var(--text-dim);">
                    Sipariş ID: <?php echo htmlspecialchars($orderId); ?>
                </p>
            <?php endif; ?>
            
            <div>
                <a href="../index.php" class="btn">Ana Sayfaya Dön</a>
                <a href="javascript:history.back()" class="btn" style="background: var(--accent2); margin-left: 1rem;">Tekrar Dene</a>
            </div>
        </div>
    </div>
</body>
</html>