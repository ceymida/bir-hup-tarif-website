<?php
session_start();
include('includes/db.php');

// Giriş kontrolü ve GET parametresi kontrolü
if (!isset($_SESSION['kullanici_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$tarif_id = (int)$_GET['id'];
$kullanici_id = (int)$_SESSION['kullanici_id'];

try {
    // Favori tarif silme sorgusu
    $query = "DELETE FROM favoriler WHERE tarif_id = :tarif_id AND kullanici_id = :kullanici_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tarif_id', $tarif_id, PDO::PARAM_INT);
    $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Başarılı silme işlemi
        header("Location: favoriler.php?mesaj=basarili");
        exit();
    } else {
        // Silme işlemi başarısız
        header("Location: favoriler.php?mesaj=hata");
        exit();
    }
} catch (PDOException $e) {
    // PDO hatası yakalama
    die("Hata oluştu: " . $e->getMessage());
}
?>
