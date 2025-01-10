<?php
session_start();
include('includes/db.php');  // Veritabanı bağlantısı
include('includes/header.php'); // Header dosyası

// Kullanıcı giriş kontrolü (örn. favoriler veya tarif ekleme sayfası için)
$is_logged_in = isset($_SESSION['kullanici_id']);
?>

<div class="container mt-5">
    <h1>Hoşgeldiniz<?php if ($is_logged_in): ?>, <?= htmlspecialchars($_SESSION['user_name']); ?><?php endif; ?>!</h1>
    <div class="search-bar mt-4">
        <form action="arama.php" method="GET" class="d-flex">
            <!-- Kategori Dropdown -->
            <select name="kategori" class="form-select me-2">
                <option value="">Kategori Seçin</option>
                <option value="Diyet">Diyet</option>
                <option value="Tatlı">Tatlı</option>
                <option value="Çorba">Çorba</option>
                <option value="Ana Yemek">Ana Yemek</option>
                <option value="Aperatif">Aperatif</option>
            </select>
            <!-- Arama Alanı -->
            <input type="text" name="arama" class="form-control me-2" placeholder="Tarif Ara...">
            <!-- Arama Butonu -->
            <button type="submit" class="btn btn-danger">Ara</button>
        </form>
    </div>


    <div class="tarif-listesi">
        <h2>Son Eklenen Tarifler</h2>
        <?php
        // Tarifleri veritabanından çekme
        $stmt = $pdo->prepare("SELECT * FROM tarifler ORDER BY tarih DESC");
        $stmt->execute();
        $tarifler = $stmt->fetchAll();

        foreach ($tarifler as $tarif): ?>
            <div class="recipe-card">
                <img src="images/<?php echo htmlspecialchars($tarif['image']); ?>" alt="Tarif Resmi" width="300" height="200">
                <h3><?php echo htmlspecialchars($tarif['baslik']); ?></h3>
                <p><?php echo substr(htmlspecialchars($tarif['hazirlanis']), 0, 100); ?>...</p>

                <!-- Tarif Detayı -->
                <button onclick="window.location.href='tarif_detay.php?id=<?php echo $tarif['id']; ?>'">Detayı Gör</button>

                <!-- Favorilere Ekle Butonu -->
                <?php if ($is_logged_in): ?>
                    <button onclick="window.location.href='favori_ekle.php?id=<?php echo $tarif['id']; ?>'">Favorilere Ekle</button>
                <?php else: ?>
                    <button onclick="window.location.href='login.php'">Favorilere Ekle</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="actions mt-4">
        <!-- Tarif Ekle Butonu -->
        <?php if ($is_logged_in): ?>
            <a href="tarif_ekle.php" class="btn btn-danger">Tarif Ekle</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-danger">Tarif Ekle</a>
        <?php endif; ?>

        <!-- Favoriler Butonu -->
        <?php if ($is_logged_in): ?>
            <a href="favoriler.php" class="btn btn-danger">Favorilerim</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-danger">Favorilerim</a>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
