<?php
include 'db.php';
session_start();

$mesaj = "";
$mesaj_tur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $girilen_eposta = $_POST['email'];
    $girilen_sifre = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM uyeler WHERE eposta = ?");
    $stmt->execute([$girilen_eposta]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $role = 'uye';

    
    if (!$user) {
        $stmt = $conn->prepare("SELECT * FROM egitmenler WHERE eposta = ?");
        $stmt->execute([$girilen_eposta]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $role = 'egitmen';
    }

    
    if (!$user) {
        $stmt = $conn->prepare("SELECT * FROM yoneticiler WHERE eposta = ?");
        $stmt->execute([$girilen_eposta]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $role = 'admin';
    }

    
    if ($user && password_verify($girilen_sifre, $user['sifre'])) {

       
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $user['ad_soyad'];

        
        if ($role == 'uye') {
            $_SESSION['uye_id'] = $user['id'];
            $_SESSION['ad_soyad'] = $user['ad_soyad'];
            header("Location: index.php");
        } elseif ($role == 'egitmen') {
            $_SESSION['egitmen_id'] = $user['id'];
            header("Location: egitmen_panel.php");
        } elseif ($role == 'admin') {
            header("Location: admin_panel.php");
        }
        exit;
    } else {
        $mesaj = "E-posta veya şifre hatalı!";
        $mesaj_tur = "hatali";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <style>
        html {
            scroll-behavior: smooth;
        }

        :root {
            --ana-renk: #2C7A7B;
            --vurgu-renk: #D35400;
            --arka-plan-rengi: 240, 244, 248;
            --kutu-arkaplan: #F7F9F9;
            --yazi-rengi: #2D3436;
            --kenarlik: #dceefb;
            --golge: 0 4px 8px rgba(0, 0, 0, 0.4);
            --bg-resim: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1470&auto=format&fit=crop');
            --perde-yogunlugu: 0.80;
        }

        body {
            background-image: linear-gradient(rgba(var(--arka-plan-rengi), var(--perde-yogunlugu)), rgba(var(--arka-plan-rengi), var(--perde-yogunlugu))), var(--bg-resim);
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: var(--yazi-rengi);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .kutu {
            background-color: var(--kutu-arkaplan);
            padding: 40px;
            border: 1px solid var(--kenarlik);
            border-radius: 10px;
            box-shadow: var(--golge);
            width: 100%;
            max-width: 400px;
            margin: 20px;
            text-align: center;
            
            box-sizing: border-box;
        }

        .form-logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 100%;
        }

        h2 {
            color: var(--ana-renk);
            margin-bottom: 30px;
            margin-top: 0;
        }

        .form-grup {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-grup input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--kenarlik);
            border-radius: 20px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-grup input:focus {
            border-color: var(--ana-renk);
            outline: none;
        }

        .btn-kayit {
            width: 100%;
            padding: 12px;
            background-color: var(--ana-renk);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-kayit:hover {
            background-color: #93bf85;
            transition: 0.3s;
        }

        .mesaj {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .hatali {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alt-link {
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .alt-link a {
            color: var(--ana-renk);
            text-decoration: none;
            font-weight: bold;
        }

        .alt-link a:hover {
            color: var(--vurgu-renk);
        }

        
        @media (max-width: 480px) {
            .kutu {
                padding: 20px;
                
                margin: 15px;
                
                width: 90%;
                
            }

            .form-logo {
                width: 90px;
                
                margin-bottom: 15px;
            }

            h1 {
                font-size: 1.5rem;
                margin-bottom: 5px;
            }

            h2 {
                font-size: 1.2rem;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="kutu">
        <img src="resimler/logo.png" alt="Logo" class="form-logo">
        <h1>HOŞGELDİNİZ</h1>
        <h2>GİRİŞ YAP</h2>

        <?php if ($mesaj): ?>
            <div class="mesaj <?php echo $mesaj_tur; ?>"><?php echo $mesaj; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-grup">
                <input type="email" name="email" required placeholder="E-posta">
            </div>
            <div class="form-grup">
                <input type="password" name="password" required placeholder="Şifre">
            </div>
            <button type="submit" class="btn-kayit">Giriş Yap</button>
        </form>

        <div class="alt-link">
            Hesabınız yok mu? <a href="kayit.php">Hemen Kayıt Ol</a>
        </div>
    </div>
</body>

</html>