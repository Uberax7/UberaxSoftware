<?php
require_once 'config.php';
require_once 'functions.php';

$products = getProducts();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHEATMARKET - Premium Cheats</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav id="nav">
        <div class="logo">CHEAT<span>MARKET</span></div>
        <div class="nav-right">
            <button onclick="openDiscord()">Join Discord</button>
            <button onclick="window.location.href='admin/login.php'" style="margin-left: 10px;">Admin</button>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <span class="hero-tag">NEW UPDATES AVAILABLE</span>
            <h1>Premium <span class="highlight">Gaming Cheats</span><br>Built Different</h1>
            <p>Industry-leading detection protection, instant updates, and 24/7 support. Join thousands of satisfied customers.</p>
            <div class="hero-cta">
                <button class="btn-primary" onclick="document.querySelector('.products').scrollIntoView({behavior: 'smooth'})">Browse Cheats</button>
                <button class="btn-secondary" onclick="openDiscord()">Join Discord</button>
            </div>
        </div>
    </section>

    <section class="products">
        <h2 class="section-title">Available Products</h2>
        <div class="products-container">
            <?php foreach ($products as $product): ?>
            <div class="product">
                <div class="product-top">
                    <div>
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-games"><?php echo htmlspecialchars($product['games']); ?></p>
                    </div>
                    <span class="product-badge"><?php echo htmlspecialchars($product['badge']); ?></span>
                </div>
                <ul class="product-features">
                    <?php foreach ($product['features'] as $feature): ?>
                    <li><?php echo htmlspecialchars($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="product-bottom">
                    <span class="product-price">₺<?php echo htmlspecialchars($product['price']); ?></span>
                    <button class="product-btn" onclick="openPaymentModal('<?php echo $product['id']; ?>', '<?php echo htmlspecialchars($product['name']); ?>', '<?php echo htmlspecialchars($product['price']); ?>')">Buy Now</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Sipariş Sorgulama -->
    <section class="order-check" style="padding: 4rem 2rem; background: var(--surface); border-top: 1px solid rgba(255,255,255,0.05);">
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <h3 style="margin-bottom: 1rem; color: var(--text); font-size: 2rem; font-weight: 700;">Siparişlerinizi Takip Edin</h3>
            <p style="color: var(--text-dim); margin-bottom: 2rem; font-size: 1.1rem; line-height: 1.6;">
                Satın aldığınız ürünlerin lisans anahtarlarını ve indirme linklerini görüntüleyin.<br>
                E-posta adresinizle giriş yaparak siparişlerinize ulaşabilirsiniz.
            </p>
            <form action="orders.php" method="GET" style="display: flex; gap: 1rem; justify-content: center; align-items: center; flex-wrap: wrap;">
                <input type="email" 
                       name="email" 
                       placeholder="E-posta adresiniz" 
                       required 
                       style="padding: 14px 18px; 
                              border: 1px solid rgba(255,255,255,0.2); 
                              border-radius: 8px; 
                              background: rgba(255,255,255,0.1); 
                              color: white; 
                              min-width: 280px;
                              font-family: 'Space Grotesk', sans-serif;
                              font-size: 1rem;
                              transition: all 0.2s;">
                <button type="submit" 
                        style="background: var(--accent); 
                               color: black; 
                               padding: 14px 28px; 
                               border: none; 
                               border-radius: 8px; 
                               cursor: pointer; 
                               font-weight: 600;
                               font-family: 'Space Grotesk', sans-serif;
                               font-size: 1rem;
                               transition: all 0.2s;">
                    Siparişlerimi Görüntüle
                </button>
            </form>
            <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 1.5rem;">
                Sipariş onayı ve lisans bilgilerinizi buradan kontrol edebilirsiniz.
            </p>
        </div>
    </section>

    <!-- Ödeme Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Ödeme Bilgileri</h3>
            <div class="product-info" style="background: rgba(0,255,136,0.1); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(0,255,136,0.2);">
                <p style="margin: 0; font-weight: 600;" id="selectedProductName"></p>
                <p style="margin: 0; color: var(--accent); font-size: 1.2rem; font-weight: 700;" id="selectedProductPrice"></p>
            </div>
            <form id="paymentForm" action="payment/process.php" method="POST">
                <input type="hidden" name="product_id" id="product_id">
                <input type="text" name="name" placeholder="Ad Soyad" required>
                <input type="email" name="email" placeholder="E-posta adresiniz" required>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Ödemeye Geç</button>
            </form>
            <p style="font-size: 0.8rem; color: var(--text-dim); margin-top: 1rem; text-align: center;">
                Ödeme işlemi Shopier güvenli ödeme sistemi üzerinden gerçekleşecektir.
            </p>
        </div>
    </div>

    <footer>
        <p><strong>CHEATMARKET</strong> - Premium Gaming Solutions</p>
        <p>© 2025 All rights reserved. Educational purposes only.</p>
        <p style="opacity: 0.5; margin-top: 1rem;">Using cheats may result in account bans.</p>
    </footer>

    <script>
        // Modal işlevsalliği
        const modal = document.getElementById('paymentModal');
        const closeBtn = document.querySelector('.close');

        function openPaymentModal(productId, productName, productPrice) {
            document.getElementById('product_id').value = productId;
            document.getElementById('selectedProductName').textContent = productName;
            document.getElementById('selectedProductPrice').textContent = '₺' + productPrice;
            modal.style.display = 'block';
            
            // Formu resetle
            document.getElementById('paymentForm').reset();
        }

        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // ESC tuşu ile modalı kapat
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                modal.style.display = 'none';
            }
        });

        // Form gönderiminde loading state
        document.getElementById('paymentForm').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = 'Yönlendiriliyor...';
            submitBtn.disabled = true;
        });

        // Input focus efektleri
        document.querySelectorAll('#paymentForm input').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = 'var(--accent)';
                this.style.boxShadow = '0 0 0 2px rgba(0, 255, 136, 0.1)';
            });
            
            input.addEventListener('blur', function() {
                this.style.borderColor = 'rgba(255,255,255,0.2)';
                this.style.boxShadow = 'none';
            });
        });

        // Sipariş sorgulama formu efektleri
        const emailInput = document.querySelector('input[name="email"]');
        const orderBtn = document.querySelector('form[action="orders.php"] button');

        if (emailInput && orderBtn) {
            emailInput.addEventListener('focus', function() {
                this.style.borderColor = 'var(--accent)';
                this.style.boxShadow = '0 0 0 2px rgba(0, 255, 136, 0.1)';
                this.style.background = 'rgba(255,255,255,0.15)';
            });
            
            emailInput.addEventListener('blur', function() {
                this.style.borderColor = 'rgba(255,255,255,0.2)';
                this.style.boxShadow = 'none';
                this.style.background = 'rgba(255,255,255,0.1)';
            });

            orderBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 5px 15px rgba(0, 255, 136, 0.3)';
            });
            
            orderBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        }

        // Önceki JavaScript kodları aynen kalacak
        const DISCORD_LINK = 'https://discord.gg/YourServerLink';

        window.addEventListener('scroll', () => {
            const nav = document.getElementById('nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, i * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.product').forEach(p => observer.observe(p));

        function openDiscord() {
            window.open(DISCORD_LINK, '_blank');
        }

        // Ürün butonlarına hover efekti
        document.querySelectorAll('.product-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 5px 15px rgba(0, 255, 136, 0.3)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });

        // Nav butonlarına hover efekti
        document.querySelectorAll('.nav-right button').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>

    <style>
        /* Modal stilleri */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: var(--surface);
            margin: 10% auto;
            padding: 2rem;
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 1rem;
            top: 0.5rem;
            transition: color 0.2s;
        }

        .close:hover {
            color: var(--accent);
        }

        #paymentForm input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            color: white;
            font-family: 'Space Grotesk', sans-serif;
            transition: all 0.2s;
        }

        #paymentForm input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(0, 255, 136, 0.1);
        }

        #paymentForm input::placeholder {
            color: var(--text-dim);
        }

        .product-info {
            background: rgba(0,255,136,0.1);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid rgba(0,255,136,0.2);
            text-align: center;
        }

        /* Order check section stilleri */
        .order-check {
            position: relative;
            overflow: hidden;
        }

        .order-check::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Responsive modal */
        @media (max-width: 768px) {
            .modal-content {
                margin: 20% auto;
                padding: 1.5rem;
            }
            
            .order-check {
                padding: 3rem 1.5rem;
            }
            
            form[action="orders.php"] {
                flex-direction: column;
                gap: 1rem;
            }
            
            form[action="orders.php"] input {
                min-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .modal-content {
                margin: 10% auto;
                width: 95%;
            }
            
            .order-check h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</body>
</html>