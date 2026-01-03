<?php
require_once '../config.php';
require_once '../functions.php';

if (!isAdmin()) redirect('login.php');

$products = getProducts();
$orders = getOrders();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: Arial, sans-serif; 
            background: #0d0d12; 
            color: white;
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
            position: relative;
            z-index: 10;
        }
        
        .header h2 {
            font-size: 1.5rem;
            color: #00ff88;
            letter-spacing: 1px;
        }
        
        .logout-btn {
            color: #ff4444;
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border: 1px solid #ff4444;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background: #ff4444;
            color: white;
        }
        
        .container { 
            padding: 2rem; 
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .stats { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 1.5rem; 
            margin-bottom: 2rem; 
        }
        
        .stat-card { 
            background: #16161d;
            padding: 1.5rem; 
            border-radius: 8px; 
            text-align: center;
            border: 1px solid #1f1f2a;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.15);
        }
        
        .stat-card h3 {
            font-size: 0.9rem;
            color: #999;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            font-weight: normal;
        }
        
        .stat-card p {
            font-size: 2.5rem;
            color: #00ff88;
            font-weight: bold;
        }
        
        .menu { 
            display: flex; 
            gap: 1rem; 
            flex-wrap: wrap;
        }
        
        .menu a { 
            background: #00ff88;
            color: #0d0d12; 
            padding: 0.9rem 1.8rem; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .menu a:hover {
            background: #00dd77;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 255, 136, 0.25);
        }
        
        @media (max-width: 768px) {
            .container { padding: 1.5rem; }
            .header { padding: 1rem; flex-direction: column; gap: 1rem; }
            .stat-card p { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>CHEATMARKET Admin</h2>
        <a href="login.php?logout=1" class="logout-btn">Çıkış Yap</a>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3>Toplam Ürün</h3>
                <p><?php echo count($products); ?></p>
            </div>
            <div class="stat-card">
                <h3>Toplam Sipariş</h3>
                <p><?php echo count($orders); ?></p>
            </div>
            <div class="stat-card">
                <h3>Bugünkü Siparişler</h3>
                <p><?php 
                    $today = date('Y-m-d');
                    $todayOrders = array_filter($orders, function($order) use ($today) {
                        return date('Y-m-d', strtotime($order['date'])) === $today;
                    });
                    echo count($todayOrders);
                ?></p>
            </div>
        </div>
        
        <div class="menu">
            <a href="products.php">Ürün Yönetimi</a>
            <a href="orders.php">Siparişler</a>
        </div>
    </div>
</body>
</html>