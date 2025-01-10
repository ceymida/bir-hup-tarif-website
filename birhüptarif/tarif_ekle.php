<?php
session_start();
include('includes/db.php');


// Giriş yapmamış kullanıcıyı engelle
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Formdan gelen veriler
    $baslik = $_POST['baslik'];
    $kategori = $_POST['kategori'];
    $malzemeler = $_POST['malzemeler'];
    $hazirlanis = $_POST['hazirlanis'];
    $kullanici_id = $_SESSION['kullanici_id'];

    // Resim dosyasını kontrol et
    $image = '';
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
        $resimAdi = $_FILES['resim']['name'];
        $resimYolu = 'images/' . basename($resimAdi);

        // Resmi images/ klasörüne kaydetme
        if (move_uploaded_file($_FILES['resim']['tmp_name'], $resimYolu)) {
            $image = $resimAdi;  // Resmi kaydettikten sonra ismini veritabanına kaydedin
        } else {
            echo "Resim yüklenemedi.";
            exit;
        }
    }

    // Tarif veritabanına ekleme
    $stmt = $pdo->prepare("INSERT INTO tarifler (kullanici_id, baslik, kategori, malzemeler, hazirlanis, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$kullanici_id, $baslik, $kategori, $malzemeler, $hazirlanis, $image]);

    echo "<p>Tarif başarıyla eklendi!</p>";
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Ekle</title>
    <link rel="stylesheet" href="/css/tarif_style.css">
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="container">
    <h1>Tarif Ekle</h1>

    <form action="tarif_ekle.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="baslik" placeholder="Tarif Başlığı" required>
        <textarea name="malzemeler" placeholder="Malzemeler" required></textarea>
        <textarea name="hazirlanis" placeholder="Hazırlık" required></textarea>

        <!-- Kategori Seçimi -->
        <select name="kategori" required>

            <option value="">Kategori Seçin</option>
            <option value="Diyet">Diyet</option>
            <option value="Tatlı">Tatlı</option>
            <option value="Çorba">Çorba</option>
            <option value="Ana Yemek">Ana Yemek</option>
            <option value="Aperatif">Aperatif</option>
        </select>

        <input type="file" name="resim" accept="image/*">
        <button type="submit">Tarifi Ekle</button>
    </form>

</div>

<?php include('includes/footer.php'); ?> <!-- Footer -->
</body>
</html>
