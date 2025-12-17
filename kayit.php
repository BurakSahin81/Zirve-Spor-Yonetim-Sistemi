<?php
include 'db.php';

$mesaj = "";
$mesaj_tur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad_soyad = $_POST['ad_soyad'];
    $eposta = $_POST['email']; 
    $sifre = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    try {
        
        $sql = "INSERT INTO uyeler (ad_soyad, eposta, sifre) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ad_soyad, $eposta, $sifre]);

        $mesaj = "Kayıt başarılı! Giriş yapabilirsiniz.";
        $mesaj_tur = "basarili"; 

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mesaj = "Bu e-posta adresi zaten kayıtlı.";
        } else {
            $mesaj = "Bir hata oluştu: " . $e->getMessage();
        }
        $mesaj_tur = "hatali"; 
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Kayıt</title>
    <style>
        
        html {
            scroll-behavior: smooth;
        }

        :root {
            --ana-renk: #007fff;
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
            background-image: linear-gradient(rgba(var(--arka-plan-rengi), var(--perde-yogunlugu)),
                    rgba(var(--arka-plan-rengi), var(--perde-yogunlugu))), var(--bg-resim);
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
            max-width: 450px;
            margin: 20px;
            text-align: center;
        }

        .form-logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        h2 {
            color: var(--ana-renk);
            margin-bottom: 25px;
            margin-top: 0;
        }

        .form-grup {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-grup label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
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
            background-color: #007fff;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .btn-kayit:hover {
            background-color: #D35400;
        }

        .mesaj {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .basarili {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
    </style>
</head>

<body>

    <div class="kutu">
        <img src="resimler/logo.png" alt="Logo" class="form-logo">

        <h2>ÜYE KAYIT</h2>

        <?php if ($mesaj): ?>
            <div class="mesaj <?php echo $mesaj_tur; ?>"><?php echo $mesaj; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-grup">
                <input type="text" name="ad_soyad" required placeholder="Adınız Soyadınız">
            </div>

            <div class="form-grup">
                <input type="email" name="email" required placeholder="E-posta">
            </div>

            <div class="form-grup">
                <input type="password" name="password" required placeholder="Şifre">
            </div>

            <button type="submit" class="btn-kayit">Kayıt Ol</button>
        </form>

        <div class="alt-link">
            Zaten hesabınız var mı? <a href="giris.php">Giriş Yap</a>
        </div>
    </div>
</body>

</html>