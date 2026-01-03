<?php
require_once '../config.php';
require_once '../functions.php';

if (!isAdmin()) redirect('login.php');

$orders = getOrders();

// Lisans anahtarı ekleme/güncelleme
if (isset($_POST['update_license'])) {
    $orderId = $_POST['order_id'];
    $licenseKey = $_POST['license_key'];
    $downloadLink = $_POST['download_link'];
    
    updateOrderLicense($orderId, $licenseKey, $downloadLink);
    redirect('orders.php');
}

// Sipariş durumu güncelleme
if (isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];
    
    foreach ($orders as &$order) {
        if ($order['id'] == $orderId) {
            $order['status'] = $newStatus;
            break;
        }
    }
    
    saveOrders($orders);
    redirect('orders.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sipariş Yönetimi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: Arial, sans-serif; 
            background: #0d0d12; 
            color: white;
            min-height: 100vh;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(0, 255, 136, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        .header { 
            background: #16161d;
            padding: 1.2rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header h2 {
            font-size: 1.5rem;
            color: #00ff88;
            letter-spacing: 1px;
        }
        
        .header > div {
            display: flex;
            gap: 0.8rem;
        }
        
        .header a { 
            background: #00ff88;
            color: #0d0d12; 
            padding: 0.6rem 1.3rem; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .header a:hover {
            background: #00dd77;
            transform: translateY(-2px);
        }
        
        .container { 
            padding: 2rem; 
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .section-header {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #00ff88;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #1f1f2a;
        }
        
        .table-wrapper {
            background: #16161d;
            border-radius: 8px;
            overflow-x: auto;
            border: 1px solid #1f1f2a;
        }
        
        .orders-table { 
            width: 100%; 
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .orders-table th, 
        .orders-table td { 
            padding: 1rem; 
            text-align: left; 
            border-bottom: 1px solid #1f1f2a;
        }
        
        .orders-table th { 
            background: #1f1f2a;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            color: #999;
            letter-spacing: 0.5px;
        }
        
        .orders-table tbody tr {
            transition: background 0.2s;
        }
        
        .orders-table tbody tr:hover {
            background: #1a1a20;
        }
        
        .orders-table td {
            font-size: 0.95rem;
        }
        
        select { 
            padding: 0.5rem 0.8rem; 
            background: #1f1f2a; 
            color: white; 
            border: 1px solid #2d2d3a; 
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        
        select:focus {
            outline: none;
            border-color: #00ff88;
        }
        
        .license-form {
            background: rgba(0, 255, 136, 0.05);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .license-form input {
            width: 100%;
            margin: 0.5rem 0;
            padding: 10px;
            background: #2d2d3a;
            border: 1px solid #444;
            color: white;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .license-form input:focus {
            outline: none;
            border-color: #00ff88;
        }
        
        .license-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #00ff88;
        }
        
        .license-info {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 6px;
            padding: 1rem;
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }
        
        .license-key {
            font-family: monospace;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.5rem;
            border-radius: 4px;
            margin: 0.5rem 0;
            word-break: break-all;
        }
        
        .btn-license {
            background: #00ff88;
            color: #0d0d12;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        
        .btn-license:hover {
            background: #00dd77;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: #ff4444;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .btn-cancel:hover {
            background: #ff2222;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state p {
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .container { padding: 1.5rem; }
            .header { 
                padding: 1rem; 
                flex-direction: column; 
                gap: 1rem; 
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Sipariş Yönetimi</h2>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Ürünler</a>
            <a href="orders.php">Siparişler</a>
        </div>
    </div>
    
    <div class="container">
        <h3 class="section-header">Sipariş Listesi</h3>
        
        <?php if (!empty($orders)): ?>
        <div class="table-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Sipariş ID</th>
                        <th>Ürün</th>
                        <th>Fiyat</th>
                        <th>Müşteri</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>Lisans</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td>₺<?php echo htmlspecialchars($order['price']); ?></td>
                        <td>
                            <div><?php echo htmlspecialchars($order['customer_name']); ?></div>
                            <div style="font-size: 0.8rem; color: #888;"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                        </td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Bekliyor</option>
                                    <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Tamamlandı</option>
                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>İptal Edildi</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td><?php echo htmlspecialchars($order['date']); ?></td>
                        <td>
                            <?php if (isset($order['license_key'])): ?>
                                <div class="license-info">
                                    <strong>Lisans:</strong>
                                    <div class="license-key"><?php echo htmlspecialchars($order['license_key']); ?></div>
                                    <?php if (isset($order['download_link'])): ?>
                                        <div style="margin-top: 0.5rem;">
                                            <a href="<?php echo htmlspecialchars($order['download_link']); ?>" 
                                               target="_blank" 
                                               style="color: #00ff88; text-decoration: none;">
                                                📽 İndirme Linki
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($order['updated_at'])): ?>
                                        <div style="font-size: 0.7rem; color: #888; margin-top: 0.5rem;">
                                            Güncellenme: <?php echo date('d.m.Y H:i', strtotime($order['updated_at'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span style="color: #ffaa00; font-style: italic;">Lisans Bekliyor</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button onclick="toggleLicenseForm('<?php echo $order['id']; ?>')" 
                                    class="btn-license">
                                <?php echo isset($order['license_key']) ? 'Lisansı Düzenle' : 'Lisans Ekle'; ?>
                            </button>
                            
                            <div id="licenseForm-<?php echo $order['id']; ?>" class="license-form" style="display: none;">
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    
                                    <label>Lisans Anahtarı:</label>
                                    <input type="text" 
                                           name="license_key" 
                                           placeholder="Lisans anahtarını buraya yapıştırın..." 
                                           value="<?php echo isset($order['license_key']) ? htmlspecialchars($order['license_key']) : ''; ?>" 
                                           required
                                           style="font-family: monospace; letter-spacing: 1px;">
                                    
                                    <label style="margin-top: 1rem;">İndirme Linki:</label>
                                    <input type="url" 
                                           name="download_link" 
                                           placeholder="https://example.com/download/cheat.zip" 
                                           value="<?php echo isset($order['download_link']) ? htmlspecialchars($order['download_link']) : ''; ?>" 
                                           required>
                                    
                                    <div style="margin-top: 1rem;">
                                        <button type="submit" 
                                                name="update_license" 
                                                class="btn-license"
                                                style="margin-right: 10px;">
                                            ✅ Kaydet
                                        </button>
                                        <button type="button" 
                                                onclick="toggleLicenseForm('<?php echo $order['id']; ?>')"
                                                class="btn-cancel">
                                            ❌ İptal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>Henüz sipariş bulunmuyor.</p>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleLicenseForm(orderId) {
            const form = document.getElementById('licenseForm-' + orderId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        
        // Lisans formunu açık bırakma - sayfa yüklendiğinde tüm formları kapat
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id^="licenseForm-"]').forEach(form => {
                form.style.display = 'none';
            });
        });
    </script>
</body>
</html>