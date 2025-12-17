<?php
session_start();
include 'db.php'; 


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: giris.php");
    exit;
}

$admin_adi = $_SESSION['name'];
$mesaj = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hoca_ekle'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $eposta = $_POST['eposta'];
    $uzmanlik = $_POST['brans']; 
    $sifre_ham = $_POST['sifre'];

    
    $sifre_hash = password_hash($sifre_ham, PASSWORD_DEFAULT);

    try {
        
        $sql = "INSERT INTO egitmenler (ad_soyad, eposta, sifre, uzmanlik) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$ad_soyad, $eposta, $sifre_hash, $uzmanlik]);
        $mesaj = "<div class='alert basarili'>✅ Yeni eğitmen başarıyla eklendi!</div>";
    } catch (PDOException $e) {
        $mesaj = "<div class='alert hatali'>❌ Hata: " . $e->getMessage() . "</div>";
    }
}


if (isset($_GET['sil_hoca'])) {
    $id = $_GET['sil_hoca'];
    $conn->prepare("DELETE FROM egitmenler WHERE id = ?")->execute([$id]);
    header("Location: admin_panel.php");
    exit;
}
if (isset($_GET['sil_uye'])) {
    $id = $_GET['sil_uye'];
    $conn->prepare("DELETE FROM uyeler WHERE id = ?")->execute([$id]);
    header("Location: admin_panel.php");
    exit;
}


$toplam_uye = $conn->query("SELECT COUNT(*) FROM uyeler")->fetchColumn();
$toplam_hoca = $conn->query("SELECT COUNT(*) FROM egitmenler")->fetchColumn();

try {
    $toplam_ders = $conn->query("SELECT COUNT(*) FROM dersler")->fetchColumn();
} catch (Exception $e) {
    $toplam_ders = 0;
}

$egitmenler = $conn->query("SELECT * FROM egitmenler ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$uyeler = $conn->query("SELECT * FROM uyeler ORDER BY id DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        
        body {
            background-color: #F7F9F9;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #000000 100%);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
        }

        .sidebar-header img {
            width: 80px;
            margin-bottom: 10px;
            border-radius: 50%;
            background: white;
            padding: 5px;
        }

        .menu-links a {
            display: block;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .menu-links a:hover,
        .menu-links a.aktif {
            background: rgba(255, 255, 255, 0.1);
            color: #D35400;
            border-left: 4px solid #D35400;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-top: 4px solid #2C7A7B;
        }

        .stat-card h4 {
            margin: 0;
            color: #777;
            font-size: 0.9rem;
        }

        .stat-card .sayi {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .box-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }

        .box-title {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
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
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-ekle {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
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
            color: #333;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            background: #e3f2fd;
            color: #0d47a1;
        }

        .btn-sil {
            background: #ffcccc;
            color: #c0392b;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.85rem;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .basarili {
            background: #d4edda;
            color: #155724;
        }

        .hatali {
            background: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .menu-links {
                display: flex;
                overflow-x: auto;
                gap: 5px;
            }

            .menu-links a {
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="resimler/foto4.png" alt="Admin">
            <h3 style="margin:0;">YÖNETİCİ</h3>
            <small><?php echo htmlspecialchars($admin_adi); ?></small>
        </div>
        <nav class="menu-links">
            <a href="#ozet" class="aktif">📊 Genel Durum</a>
            <a href="#hoca-yonetimi">🎓 Eğitmenler</a>
            <a href="#uye-yonetimi">👥 Üyeler</a>
            <a href="index.php" style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.1);">🏠 Siteye Git</a>
            <a href="cikis.php" style="background: #c0392b; color:white;">🚪 Çıkış</a>
        </nav>
    </aside>

    <main class="main-content">
        <h2>Yönetim Paneli</h2>
        <?php echo $mesaj; ?>

        <div class="stats-grid" id="ozet">
            <div class="stat-card" style="border-top-color: #ff7f00;">
                <h4>Toplam Üye</h4>
                <div class="sayi"><?php echo $toplam_uye; ?></div>
            </div>
            <div class="stat-card" style="border-top-color: #28a745;">
                <h4>Toplam Eğitmen</h4>
                <div class="sayi"><?php echo $toplam_hoca; ?></div>
            </div>
            <div class="stat-card" style="border-top-color: #007bff;">
                <h4>Aktif Dersler</h4>
                <div class="sayi"><?php echo $toplam_ders; ?></div>
            </div>
        </div>

        <div class="box-container" id="hoca-yonetimi">
            <h3 class="box-title">🎓 Eğitmen Yönetimi</h3>

            <div style="background:#f9f9f9; padding:15px; border-radius:8px; margin-bottom:20px;">
                <h4 style="margin-top:0;">➕ Yeni Eğitmen Ekle</h4>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Ad Soyad</label>
                            <input type="text" name="ad_soyad" required placeholder="Örn: Ahmet Yılmaz">
                        </div>
                        <div class="form-group">
                            <label>E-posta</label>
                            <input type="email" name="eposta" required placeholder="ornek@zirve.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Uzmanlık (Branş)</label>
                            <select name="brans">
                                <option value="Fitness">Fitness</option>
                                <option value="Pilates">Pilates</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Boks">Boks</option>
                                <option value="Yüzme">Yüzme</option>
                                <option value="HIIT Kardiyo">HIIT Kardiyo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Şifre</label>
                            <input type="text" name="sifre" required placeholder="Örn: 123456">
                        </div>
                    </div>
                    <button type="submit" name="hoca_ekle" class="btn-ekle">Eğitmeni Kaydet</button>
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-posta</th>
                            <th>Branş</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($egitmenler as $hoca): ?>
                            <tr>
                                <td>#<?php echo $hoca['id']; ?></td>
                                <td><?php echo htmlspecialchars($hoca['ad_soyad']); ?></td>
                                <td><?php echo htmlspecialchars($hoca['eposta']); ?></td>
                                <td><span class="badge badge-admin"><?php echo htmlspecialchars($hoca['uzmanlik']); ?></span></td>
                                <td>
                                    <a href="?sil_hoca=<?php echo $hoca['id']; ?>" class="btn-sil" onclick="return confirm('Silmek istediğine emin misin?')">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box-container" id="uye-yonetimi">
            <h3 class="box-title">👥 Son Kayıt Olan Üyeler</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-posta</th>
                            <th>Kayıt Tarihi</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($uyeler as $uye): ?>
                            <tr>
                                <td>#<?php echo $uye['id']; ?></td>
                                <td><?php echo htmlspecialchars($uye['ad_soyad']); ?></td>
                                <td><?php echo htmlspecialchars($uye['eposta']); ?></td>
                                <td style="font-size:0.9rem; color:#666;"><?php echo date("d.m.Y", strtotime($uye['kayit_tarihi'])); ?></td>
                                <td>
                                    <a href="?sil_uye=<?php echo $uye['id']; ?>" class="btn-sil" onclick="return confirm('Bu üyeyi silmek istediğine emin misin?')">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="kutu" style="margin-top: 20px; background: #fff; padding: 20px;">
            <h3 style="border-bottom: 2px solid var(--vurgu-renk); padding-bottom: 10px; color: var(--ana-renk);">📊 Salon İstatistikleri & Analizler</h3>

            <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 20px;">
                <div style="text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <h4 style="margin:0 0 10px 0;">Haftalık Yoğunluk</h4>
                    <img src="resimler/analiz_grafigi.png" style="max-width: 300px; height: auto;">
                </div>

                <div style="text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <h4 style="margin:0 0 10px 0;">Saatlik Yoğunluk</h4>
                    <img src="resimler/analiz_saatlik.png" style="max-width: 300px; height: auto;">
                </div>

                <div style="text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <h4 style="margin:0 0 10px 0;">Üye Dağılımı</h4>
                    <img src="resimler/analiz_uyelik.png" style="max-width: 300px; height: auto;">
                </div>

                <div style="text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <h4 style="margin:0 0 10px 0;">Üye Dağılımı</h4>
                    <img src="resimler/analiz_cinsiyet.png" style="max-width: 300px; height: auto;">
                </div>

                <div style="text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <h4 style="margin:0 0 10px 0;">Üye Dağılımı</h4>
                    <img src="resimler/analiz_memnuniyet.png" style="max-width: 300px; height: auto;">
                </div>
            </div>
        </div>

    </main>

</body>

</html>