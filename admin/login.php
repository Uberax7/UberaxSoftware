<?php
require_once '../config.php';
require_once '../functions.php';

if (isset($_GET['logout'])) {
    session_destroy();
    redirect('login.php');
}

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
        redirect('dashboard.php');
    } else {
        $error = "Geçersiz kullanıcı adı veya şifre!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Giriş</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: Arial, sans-serif; 
            background: #0d0d12; 
            color: white; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 30% 40%, rgba(0, 255, 136, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 70% 60%, rgba(0, 255, 136, 0.05) 0%, transparent 40%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .login-container {
            position: relative;
            z-index: 1;
        }
        
        .login-form { 
            background: #16161d; 
            padding: 2.5rem; 
            border-radius: 12px; 
            width: 360px;
            border: 1px solid #1f1f2a;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        
        .login-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #00ff88, transparent);
            border-radius: 12px 12px 0 0;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h2 {
            font-size: 1.8rem;
            color: #00ff88;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }
        
        .login-header p {
            color: #888;
            font-size: 0.9rem;
        }
        
        .input-group {
            margin-bottom: 1.2rem;
        }
        
        .input-group label {
            display: block;
            color: #999;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        input { 
            width: 100%; 
            padding: 0.9rem; 
            background: #1f1f2a; 
            border: 1px solid #2d2d3a; 
            color: white; 
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: #00ff88;
            background: #252530;
        }
        
        input::placeholder {
            color: #666;
        }
        
        button { 
            width: 100%; 
            padding: 1rem; 
            background: #00ff88; 
            color: #0d0d12; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 1rem;
            margin-top: 0.5rem;
            transition: all 0.2s;
        }
        
        button:hover {
            background: #00dd77;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.3);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .error { 
            background: rgba(255, 68, 68, 0.1);
            color: #ff4444; 
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 68, 68, 0.3);
            text-align: center;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.85rem;
        }
        
        @media (max-width: 480px) {
            .login-form {
                width: 90%;
                padding: 2rem;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <h2>CHEATMARKET</h2>
                <p>Admin Panel Girişi</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="input-group">
                    <label>Kullanıcı Adı</label>
                    <input type="text" name="username" placeholder="Kullanıcı adınızı girin" required autofocus>
                </div>
                
                <div class="input-group">
                    <label>Şifre</label>
                    <input type="password" name="password" placeholder="Şifrenizi girin" required>
                </div>
                
                <button type="submit">Giriş Yap</button>
            </form>
            
            <div class="footer-text">
                © 2024 CHEATMARKET
            </div>
        </div>
    </div>
</body>
</html>