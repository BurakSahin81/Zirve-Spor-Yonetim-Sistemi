<?php
session_start();
include 'db.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'egitmen') {
    header("Location: giris.php");
    exit;
}

$hoca_id = $_SESSION['user_id'];
$hoca_adi = $_SESSION['name'];
$mesaj = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ders_ekle'])) {
    $ders_adi = $_POST['ders_adi'];
    $kategori = $_POST['kategori'];
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];
    $kontenjan = $_POST['kontenjan'];

    
    $tarih_saat = $tarih . " " . $saat . ":00";

    try {
        $sql = "INSERT INTO dersler (egitmen_id, ders_adi, kategori_id, tarih_saat, kontenjan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$hoca_id, $ders_adi, $kategori, $tarih_saat, $kontenjan]);
        $mesaj = "<div class='alert basarili'>✅ Ders başarıyla oluşturuldu!</div>";
    } catch (PDOException $e) {
        $mesaj = "<div class='alert hatali'>❌ Hata: " . $e->getMessage() . "</div>";
    }
}


if (isset($_GET['sil_id'])) {
    $sil_id = $_GET['sil_id'];
    $sil_sorgu = $conn->prepare("DELETE FROM dersler WHERE id = ? AND egitmen_id = ?");
    $sil_sorgu->execute([$sil_id, $hoca_id]);
    header("Location: egitmen_panel.php");
    exit;
}


$stmt = $conn->prepare("SELECT * FROM egitmenler WHERE id = ?");
$stmt->execute([$hoca_id]);
$hoca = $stmt->fetch(PDO::FETCH_ASSOC);
$profil_resmi = !empty($hoca['resim_yolu']) ? $hoca['resim_yolu'] : "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";


$sql_yorum = "SELECT y.*, u.ad_soyad FROM yorumlar y JOIN uyeler u ON y.uye_id = u.id WHERE y.ders_id = ? ORDER BY y.tarih DESC";
$stmt2 = $conn->prepare($sql_yorum);
$stmt2->execute([$hoca_id]);
$yorumlar = $stmt2->fetchAll(PDO::FETCH_ASSOC);


$sql_dersler = "SELECT * FROM dersler WHERE egitmen_id = ? AND tarih_saat > NOW() ORDER BY tarih_saat ASC";
$stmt3 = $conn->prepare($sql_dersler);
$stmt3->execute([$hoca_id]);
$derslerim = $stmt3->fetchAll(PDO::FETCH_ASSOC);


$toplam_yorum = count($yorumlar);
$aktif_ders_sayisi = count($derslerim);
$ortalama_puan = 0;
if ($toplam_yorum > 0) {
    $puan_toplam = 0;
    foreach ($yorumlar as $y) $puan_toplam += $y['puan'];
    $ortalama_puan = number_format($puan_toplam / $toplam_yorum, 1);
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eğitmen Paneli</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f4f6f9;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        
        .sidebar {
            width: 260px;
            background: #0056b3;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: relative;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 20px;
        }

        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .menu-links {
            flex: 1;
        }

        .menu-links a {
            display: block;
            padding: 12px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .menu-links a:hover,
        .menu-links a.aktif {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .cikis-btn {
            background-color: #d9534f;
            text-align: center;
            font-weight: bold;
            margin-top: auto;
        }

        .cikis-btn:hover {
            background-color: #c9302c;
        }

        
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .basarili {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .hatali {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #ff7f00;
        }

        .stat-card .sayi {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            display: block;
            margin-top: 5px;
        }

        
        .box-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .box-title {
            margin-top: 0;
            color: #0056b3;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            
        }

        .form-group {
            flex: 1;
            min-width: 200px;
            
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-ekle {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            width: 100%;
            transition: 0.3s;
        }

        .btn-ekle:hover {
            background: #218838;
        }

        
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f1f1f1;
        }

        th {
            background: #f8f9fa;
            color: #555;
        }

        .sil-btn {
            color: white;
            background: #dc3545;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.8rem;
        }

       
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .menu-links {
                display: flex;
                gap: 10px;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .menu-links a {
                white-space: nowrap;
                font-size: 0.9rem;
            }

            .cikis-btn {
                margin-top: 0;
            }

            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="<?php echo $profil_resmi; ?>">
            <div style="margin-top:10px;">
                <strong><?php echo htmlspecialchars($hoca_adi); ?></strong>
            </div>
        </div>

        <nav class="menu-links">
            <a href="#ozet">📊 Özet</a>
            <a href="#ders-ekle">📅 Ders Ekle</a>
            <a href="cikis.php" class="cikis-btn">🚪 Çıkış</a>
        </nav>
    </aside>

    <main class="main-content">

        <h2>Hoş Geldin, Hocam 👋</h2>
        <?php echo $mesaj; ?>

        <div class="stats-grid" id="ozet">
            <div class="stat-card">
                <h4>Aktif Dersler</h4>
                <span class="sayi"><?php echo $aktif_ders_sayisi; ?></span>
            </div>
            <div class="stat-card">
                <h4>Toplam Yorum</h4>
                <span class="sayi"><?php echo $toplam_yorum; ?></span>
            </div>
            <div class="stat-card">
                <h4>Ortalama Puan</h4>
                <span class="sayi"><?php echo $ortalama_puan; ?> ⭐</span>
            </div>
        </div>

        <div class="box-container" id="ders-ekle">
            <h3 class="box-title">➕ Yeni Ders Programı Oluştur</h3>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Ders Adı</label>
                        <input type="text" name="ders_adi" placeholder="Örn: Akşam Yoga" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori">
                            <option value="1">Fitness</option>
                            <option value="2">Dövüş / Boks</option>
                            <option value="3">Yoga / Pilates</option>
                            <option value="4">Yüzme</option>
                            <option value="5">Tenis</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tarih</label>
                        <input type="date" name="tarih" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Saat</label>
                        <input type="time" name="saat" required>
                    </div>
                    <div class="form-group">
                        <label>Kontenjan</label>
                        <input type="number" name="kontenjan" value="15" min="1" required>
                    </div>
                </div>
                <button type="submit" name="ders_ekle" class="btn-ekle">Programı Kaydet</button>
            </form>
        </div>

        <div class="box-container">
            <h3 class="box-title">📅 Senin Ders Programın</h3>
            <div class="table-responsive">
                <?php if ($aktif_ders_sayisi > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Ders Adı</th>
                                <th>Tarih & Saat</th>
                                <th>Kontenjan</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($derslerim as $ders): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ders['ders_adi']); ?></td>
                                    <td><?php echo date("d.m.Y H:i", strtotime($ders['tarih_saat'])); ?></td>
                                    <td><?php echo $ders['kontenjan']; ?> Kişi</td>
                                    <td>
                                        <a href="?sil_id=<?php echo $ders['id']; ?>" class="sil-btn" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:#777; padding:10px;">Henüz ders eklemedin.</p>
                <?php endif; ?>
            </div>
        </div>

    </main>

</body>

</html>