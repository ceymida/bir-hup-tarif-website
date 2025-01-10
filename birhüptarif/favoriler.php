<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Giriş kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];

// Kullanıcının favorilerini çekme
$query = "
    SELECT t.id, t.baslik, t.image, t.hazirlanis
    FROM favoriler f
    JOIN tarifler t ON f.tarif_id = t.id
    WHERE f.kullanici_id = :kullanici_id
";
$stmt = $pdo->prepare($query);
$stmt->execute([':kullanici_id' => $kullanici_id]);
$favoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1 class="text-center" style="color: #ff6f61;">Favorilerim</h1>

    <?php if (!empty($favoriler)): ?>
        <div class="row g-4 mt-4">
            <?php foreach ($favoriler as $favori): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <img src="images/<?= htmlspecialchars($favori['image']); ?>" class="card-img-top" alt="Tarif Resmi" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #ff6f61;"><?= htmlspecialchars($favori['baslik']); ?></h5>
                            <p class="card-text"><?= substr(htmlspecialchars($favori['hazirlanis']), 0, 100); ?>...</p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="tarif_detay.php?id=<?= $favori['id']; ?>" class="btn btn-outline-danger">Detayları Gör</a>
                            <a href="favorilerden_cikar.php?id=<?= $favori['id']; ?>" class="btn btn-danger">Favorilerden Çıkar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mt-4" role="alert">
            Favorilere eklediğiniz tarif yok.
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
