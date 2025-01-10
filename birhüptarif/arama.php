<?php
include('includes/db.php');
include('includes/header.php'); // Header dosyası

// GET parametrelerini al
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$arama = isset($_GET['arama']) ? trim($_GET['arama']) : '';

// Tarifleri filtrelemek için SQL sorgusu oluştur
$query = "SELECT * FROM tarifler WHERE 1";
$params = [];

// Kategori filtresi
if (!empty($kategori)) {
    $query .= " AND kategori = :kategori";
    $params[':kategori'] = $kategori;
}

// Arama filtresi
if (!empty($arama)) {
    $query .= " AND baslik LIKE :arama";
    $params[':arama'] = '%' . $arama . '%';
}

// Sonuçları tarihe göre sırala
$query .= " ORDER BY tarih DESC";

// Sorguyu çalıştır
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tarifler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları</title>
    <link rel="stylesheet" href="css/arama.css">
</head>
<body>
<header>
    <h1>Arama Sonuçları</h1>
    <a href="index.php">Ana Sayfa</a>
</header>

<main>
    <!-- Sonuçları listele -->
    <div class="tarif-listesi mt-4">
        <?php if (count($tarifler) > 0): ?>
            <?php foreach ($tarifler as $tarif): ?>
                <div class="recipe-card">
                    <img src="images/<?php echo htmlspecialchars($tarif['image']); ?>" alt="Tarif Resmi" width="300" height="200">
                    <h3><?php echo htmlspecialchars($tarif['baslik']); ?></h3>
                    <p><?php echo substr(htmlspecialchars($tarif['hazirlanis']), 0, 100); ?>...</p>
                    <button onclick="window.location.href='tarif_detay.php?id=<?php echo $tarif['id']; ?>'">Detayı Gör</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Bu kriterlere uygun tarif bulunamadı.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
