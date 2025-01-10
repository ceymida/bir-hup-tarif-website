
<?php

session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$tarif_id = $_GET['id'];

// Tarif detaylarını çekme
//$query = "SELECT * FROM tarifler WHERE id = :id";
$query = "SELECT t.*, k.isim AS ekleyen_isim 
          FROM tarifler t
          INNER JOIN kullanicilar k ON t.kullanici_id = k.id
          WHERE t.id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $tarif_id);
$stmt->execute();
$tarif = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarif) {
    header('Location: index.php');
    exit;
}

// Yorumları çekme
//$query = "SELECT * FROM yorumlar WHERE tarif_id = :tarif_id";
$query = "SELECT y.*, k.isim AS kullanici_adi 
          FROM yorumlar y
          INNER JOIN kullanicilar k ON y.kullanici_id = k.id
          WHERE y.tarif_id = :tarif_id
          ORDER BY y.tarih DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':tarif_id', $tarif_id);
$stmt->execute();
$yorumlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Favoriler tablosuna ekleme işlemi
if (isset($_POST['favori'])) {
    if (isset($_SESSION['kullanici_id'])) {
        $kullanici_id = $_SESSION['kullanici_id'];

        // Aynı tarif daha önce favorilere eklenmiş mi kontrol et
        $check_query = "SELECT COUNT(*) FROM favoriler WHERE kullanici_id = :kullanici_id AND tarif_id = :tarif_id";
        $check_stmt = $pdo->prepare($check_query);
        $check_stmt->execute([
            ':kullanici_id' => $kullanici_id,
            ':tarif_id' => $tarif_id
        ]);
        $exists = $check_stmt->fetchColumn();

        if (!$exists) {
            // Favorilere ekleme
            $favori_query = "INSERT INTO favoriler (kullanici_id, tarif_id) VALUES (:kullanici_id, :tarif_id)";
            $favori_stmt = $pdo->prepare($favori_query);
            $favori_stmt->execute([
                ':kullanici_id' => $kullanici_id,
                ':tarif_id' => $tarif_id
            ]);
            echo "<div class='alert alert-success'>Tarif favorilere eklendi!</div>";
        } else {
            echo "<div class='alert alert-warning'>Bu tarif zaten favorilerinizde!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Favorilere eklemek için giriş yapmalısınız!</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tarif['baslik']); ?></title>
    <link rel="stylesheet" href="/css/tarif_detay.css">
</head>
<body >

<div class="container">
    <h1><?= htmlspecialchars($tarif['baslik']); ?></h1>
    <p>Kategori: <?= htmlspecialchars($tarif['kategori']); ?></p>
    <p>Malzemeler: <?= nl2br(htmlspecialchars($tarif['malzemeler'])); ?></p>
    <p>Hazırlanış: <?= nl2br(htmlspecialchars($tarif['hazirlanis'])); ?></p>

    <!-- Tarif Resmi -->
    <?php if (!empty($tarif['image'])): ?>
        <img src="images/<?= $tarif['image']; ?>" alt="Tarif Resmi" class="tarif-resim">
    <?php endif; ?>

    <!-- Tarif Ekleyen Kullanıcı -->
    <p>Ekleyen: <?= htmlspecialchars($tarif['ekleyen_isim']); ?></p>

    <!-- Favorilere Ekle Butonu -->
    <?php if (isset($_SESSION['kullanici_id'])): ?>
        <form action="tarif_detay.php?id=<?= $tarif['id']; ?>" method="POST">
            <button type="submit" name="favori" class="favori-btn">Favorilere Ekle</button>
        </form>
    <?php endif; ?>
    <br>
    <?php if (isset($_SESSION['kullanici_id'])): ?>
        <form action="favorilerden_cikar.php?id=<?= $tarif['id']; ?>" method="POST" class="d-inline">
            <button type="submit" class="btn btn-danger">Favorilerden Çıkar</button>
        </form>
    <?php endif; ?>

    <!-- Yorumlar -->
    <div class="yorumlar">
        <h2>Yorumlar</h2>
        <ul>
<!--            --><?php //foreach ($yorumlar as $yorum): ?>
<!--                <li>-->
<!--                    <p>--><?php //= htmlspecialchars($yorum['yorum']); ?><!--</p>-->
<!--                    <small>--><?php //= $yorum['tarih']; ?><!--</small>-->
<!--                </li>-->
<!--            --><?php //endforeach; ?>
            <?php foreach ($yorumlar as $yorum): ?>
                <li>
                    <p><strong><?= htmlspecialchars($yorum['kullanici_adi']); ?>:</strong></p>
                    <p><?= htmlspecialchars($yorum['yorum']); ?></p>
                    <small><?= htmlspecialchars($yorum['tarih']); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($_SESSION['kullanici_id'])): ?>
            <form action="yorum_ekle.php" method="POST" class="yorum-form">
                <div class="mb-3">
                    <label for="yorum" class="form-label">Yorumunuzu Yazın:</label>
                    <textarea
                            name="yorum"
                            id="yorum"
                            rows="4"
                            class="form-control"
                            placeholder="Tarifle ilgili düşüncelerinizi paylaşın..."
                            required
                    ></textarea>
                </div>
                <input type="hidden" name="tarif_id" value="<?= $tarif['id']; ?>">
                <button type="submit" class="btn btn-primary w-100">
                    Yorum Yap
                </button>
            </form>

        <?php else: ?>
            <p class="alert alert-warning mt-4">Yorum yapmak için <a href="login.php" class="fw-bold">giriş yapın</a>.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
