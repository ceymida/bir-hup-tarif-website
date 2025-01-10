<?php
session_start();
include 'includes/db.php'; // Veritabanı bağlantısı

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit();
}

// Formdan gelen veriler
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // burda 3 tane eşittir niye normalde 2 ydi 3 yaptım bunu değiştir
    $yorum = trim($_POST['yorum']);
    $tarif_id = intval($_POST['tarif_id']);
    $kullanici_id = $_SESSION['kullanici_id'];

    if (!empty($yorum)) {
        try {
            // Yorum ekleme sorgusu
            $query = "INSERT INTO yorumlar (tarif_id, kullanici_id, yorum, tarih) VALUES (:tarif_id, :kullanici_id, :yorum, NOW())";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':tarif_id', $tarif_id,PDO::PARAM_INT);
            $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
            $stmt->bindParam(':yorum', $yorum, PDO::PARAM_STR);
            $stmt->execute();

            // Başarıyla eklendikten sonra tarif_detay.php'ye yönlendir
            header("Location: tarif_detay.php?id=$tarif_id");
            exit();
        } catch (PDOException $e) {
            // Hata mesajını göster (Geliştirme aşamasında)
            die("Yorum eklenirken hata oluştu: " . $e->getMessage());
        }
    } else {
        // Yorum boşsa hata mesajı
        echo "Yorum boş olamaz!";
    }
} else {
    // Geçersiz yöntemle sayfa açılmışsa
    echo "Geçersiz işlem!";
}
