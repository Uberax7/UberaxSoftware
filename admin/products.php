<?php
require_once '../config.php';
require_once '../functions.php';

if (!isAdmin()) redirect('login.php');

$products = getProducts();
$message = '';

// Ürün ekleme/güncelleme
if ($_POST) {
    $id = $_POST['id'] ?? uniqid();
    $name = $_POST['name'] ?? '';
    $games = $_POST['games'] ?? '';
    $price = $_POST['price'] ?? '';
    $badge = $_POST['badge'] ?? '';
    $features = explode("\n", $_POST['features'] ?? '');
    $features = array_map('trim', $features);
    
    $productData = [
        'id' => $id,
        'name' => $name,
        'games' => $games,
        'price' => $price,
        'badge' => $badge,
        'features' => $features
    ];
    
    if (isset($_POST['edit_id'])) {
        // Güncelleme
        foreach ($products as &$product) {
            if ($product['id'] == $_POST['edit_id']) {
                $product = $productData;
                break;
            }
        }
        $message = "Ürün güncellendi!";
    } else {
        // Yeni ürün
        $products[] = $productData;
        $message = "Ürün eklendi!";
    }
    
    saveProducts($products);
    redirect('products.php');
}

// Ürün silme
if (isset($_GET['delete'])) {
    $products = array_filter($products, function($product) {
        return $product['id'] != $_GET['delete'];
    });
    saveProducts($products);
    redirect('products.php');
}

// Düzenlenecek ürün
$editProduct = null;
if (isset($_GET['edit'])) {
    foreach ($products as $product) {
        if ($product['id'] == $_GET['edit']) {
            $editProduct = $product;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ürün Yönetimi</title>
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
        
        .nav-links {
            display: flex;
            gap: 0.8rem;
        }
        
        .nav-links a { 
            background: #00ff88;
            color: #0d0d12; 
            padding: 0.6rem 1.3rem; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .nav-links a:hover {
            background: #00dd77;
            transform: translateY(-2px);
        }
        
        .container { 
            padding: 2rem; 
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .alert {
            background: #00ff88;
            color: #0d0d12;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .form-box { 
            background: #16161d;
            padding: 2rem; 
            border-radius: 8px; 
            margin-bottom: 2.5rem;
            border: 1px solid #1f1f2a;
        }
        
        .form-box h3 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: #00ff88;
        }
        
        input, textarea { 
            width: 100%; 
            padding: 0.9rem; 
            margin: 0.7rem 0; 
            background: #1f1f2a; 
            border: 1px solid #2d2d3a; 
            color: white; 
            border-radius: 5px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            font-family: Arial, sans-serif;
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: #00ff88;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn { 
            background: #00ff88;
            color: #0d0d12; 
            padding: 0.9rem 2rem; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        
        .btn:hover {
            background: #00dd77;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: transparent;
            color: #ff4444;
            border: 1px solid #ff4444;
            margin-left: 1rem;
            padding: 0.8rem 1.8rem;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            transition: all 0.2s;
        }
        
        .btn-cancel:hover {
            background: #ff4444;
            color: white;
        }
        
        .section-header {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #00ff88;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #1f1f2a;
        }
        
        .products-grid { 
            display: grid; 
            gap: 1rem; 
        }
        
        .product-card { 
            background: #16161d;
            padding: 1.5rem; 
            border-radius: 8px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            border: 1px solid #1f1f2a;
            transition: all 0.2s;
        }
        
        .product-card:hover {
            border-color: #2d2d3a;
            transform: translateX(5px);
        }
        
        .product-details strong {
            font-size: 1.1rem;
            display: block;
            margin-bottom: 0.4rem;
        }
        
        .price {
            color: #00ff88;
            font-weight: 600;
            margin-left: 1rem;
        }
        
        .games {
            color: #888;
            font-size: 0.85rem;
            margin-top: 0.4rem;
        }
        
        .actions {
            display: flex;
            gap: 0.8rem;
        }
        
        .actions a {
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .btn-edit {
            background: #00ff88;
            color: #0d0d12;
        }
        
        .btn-edit:hover {
            background: #00dd77;
        }
        
        .btn-delete {
            background: transparent;
            color: #ff4444;
            border: 1px solid #ff4444;
        }
        
        .btn-delete:hover {
            background: #ff4444;
            color: white;
        }
        
        @media (max-width: 768px) {
            .container { padding: 1.5rem; }
            .header { 
                padding: 1rem; 
                flex-direction: column; 
                gap: 1rem; 
            }
            .product-card {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .actions {
                width: 100%;
            }
            .actions a {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Ürün Yönetimi</h2>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Ürünler</a>
            <a href="orders.php">Siparişler</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="form-box">
            <h3><?php echo $editProduct ? 'Ürün Düzenle' : 'Yeni Ürün Ekle'; ?></h3>
            <form method="POST">
                <?php if ($editProduct): ?>
                    <input type="hidden" name="edit_id" value="<?php echo $editProduct['id']; ?>">
                    <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                <?php endif; ?>
                
                <input type="text" name="name" placeholder="Ürün Adı" value="<?php echo $editProduct['name'] ?? ''; ?>" required>
                <input type="text" name="games" placeholder="Oyunlar (virgülle ayırın)" value="<?php echo $editProduct['games'] ?? ''; ?>" required>
                <input type="number" name="price" placeholder="Fiyat" value="<?php echo $editProduct['price'] ?? ''; ?>" required>
                <input type="text" name="badge" placeholder="Badge (POPULAR, NEW vb.)" value="<?php echo $editProduct['badge'] ?? ''; ?>" required>
                <textarea name="features" placeholder="Özellikler (her satıra bir özellik)" required><?php echo $editProduct ? implode("\n", $editProduct['features']) : ''; ?></textarea>
                
                <button type="submit" class="btn"><?php echo $editProduct ? 'Güncelle' : 'Ekle'; ?></button>
                <?php if ($editProduct): ?>
                    <a href="products.php" class="btn-cancel">İptal</a>
                <?php endif; ?>
            </form>
        </div>
        
        <h3 class="section-header">Mevcut Ürünler</h3>
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-details">
                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                    <span class="price">₺<?php echo htmlspecialchars($product['price']); ?></span>
                    <div class="games"><?php echo htmlspecialchars($product['games']); ?></div>
                </div>
                <div class="actions">
                    <a href="?edit=<?php echo $product['id']; ?>" class="btn-edit">Düzenle</a>
                    <a href="?delete=<?php echo $product['id']; ?>" class="btn-delete" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>