<?php
require_once 'config.php';
require_once 'functions.php';

$customerEmail = $_GET['email'] ?? '';

if (empty($customerEmail)) {
    die("E-posta adresi gerekli!");
}

$orders = getOrdersByEmail($customerEmail);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişlerim - CHEATMARKET</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .orders-container {
            min-height: 100vh;
            padding: 120px 2rem 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .orders-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .orders-list {
            display: grid;
            gap: 1.5rem;
        }
        
        .order-card {
            background: var(--surface);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.3s;
        }
        
        .order-card:hover {
            border-color: rgba(0, 255, 136, 0.3);
            transform: translateY(-2px);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .order-id {
            color: var(--accent);
            font-weight: 600;
        }
        
        .order-date {
            color: var(--text-dim);
            font-size: 0.9rem;
        }
        
        .order-product {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .order-price {
            color: var(--accent);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .license-section {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .license-key {
            font-family: monospace;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.8rem 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            font-size: 1.1rem;
            letter-spacing: 1px;
            word-break: break-all;
        }
        
        .download-btn {
            display: inline-block;
            background: var(--accent);
            color: black;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }
        
        .no-license {
            color: var(--text-dim);
            font-style: italic;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-completed {
            background: rgba(0, 255, 136, 0.1);
            color: var(--accent);
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .empty-orders {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-dim);
        }
        
        .back-btn {
            display: inline-block;
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 2rem;
            transition: all 0.2s;
        }
        
        .back-btn:hover {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body>
    <nav id="nav">
        <div class="logo">CHEAT<span>MARKET</span></div>
        <div class="nav-right">
            <button onclick="openDiscord()">Join Discord</button>
            <button onclick="window.location.href='index.php'" style="margin-left: 10px;">Ana Sayfa</button>
        </div>
    </nav>

    <div class="orders-container">
        <div class="orders-header">
            <h1>Siparişlerim</h1>
            <p style="color: var(--text-dim);">E-posta: <?php echo htmlspecialchars($customerEmail); ?></p>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-orders">
                <h3>Henüz siparişiniz bulunmuyor</h3>
                <p>Tamamlanmış siparişleriniz burada görünecektir.</p>
                <a href="index.php" class="back-btn">Ürünlere Göz At</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Sipariş #<?php echo htmlspecialchars($order['id']); ?></div>
                            <div class="order-date"><?php echo date('d.m.Y H:i', strtotime($order['date'])); ?></div>
                        </div>
                        <span class="status-badge status-completed">Tamamlandı</span>
                    </div>
                    
                    <div class="order-product"><?php echo htmlspecialchars($order['product_name']); ?></div>
                    <div class="order-price">₺<?php echo htmlspecialchars($order['price']); ?></div>
                    
                    <div class="license-section">
                        <h4 style="margin-bottom: 1rem; color: var(--accent);">Lisans Bilgileri</h4>
                        
                        <?php if (isset($order['license_key'])): ?>
                            <div>
                                <strong>Lisans Anahtarınız:</strong>
                                <div class="license-key"><?php echo htmlspecialchars($order['license_key']); ?></div>
                            </div>
                            
                            <?php if (isset($order['download_link'])): ?>
                                <div style="margin-top: 1rem;">
                                    <a href="<?php echo htmlspecialchars($order['download_link']); ?>" 
                                       class="download-btn" 
                                       target="_blank">
                                        🔽 Programı İndir
                                    </a>
                                    <p style="font-size: 0.8rem; color: var(--text-dim); margin-top: 0.5rem;">
                                        Lisans anahtarınızı program içinde giriniz.
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="no-license">
                                Lisans anahtarınız hazırlanıyor...<br>
                                En kısa sürede e-posta adresinize gönderilecektir.
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <div style="font-size: 0.8rem; color: var(--text-dim);">
                        <strong>Destek:</strong> Sorularınız için Discord sunucumuza katılın.
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 3rem;">
                <a href="index.php" class="back-btn">← Yeni Ürünlere Göz At</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p><strong>CHEATMARKET</strong> - Premium Gaming Solutions</p>
        <p>© 2025 All rights reserved. Educational purposes only.</p>
    </footer>

    <script>
        const DISCORD_LINK = 'https://discord.gg/YourServerLink';

        function openDiscord() {
            window.open(DISCORD_LINK, '_blank');
        }

        // Navbar scroll efekti
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>