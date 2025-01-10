<?php
session_start();
include('includes/db.php');

// Giriş kontrolü
if (!isset($_SESSION['kullanici_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$tarif_id = $_GET['id'];
$kullanici_id = $_SESSION['kullanici_id'];

// Tarifin zaten favorilerde olup olmadığını kontrol et
$query = "SELECT * FROM favoriler WHERE tarif_id = :tarif_id AND kullanici_id = :kullanici_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':tarif_id' => $tarif_id,
    ':kullanici_id' => $kullanici_id
]);

if ($stmt->rowCount() > 0) { //aradığım kısımda 1 bile olsa tarif zaten favorilerde
    $_SESSION['mesaj'] = "Tarif zaten favorilerde!";
} else {
    // Tarif favorilere eklenir
    $query = "INSERT INTO favoriler (tarif_id, kullanici_id) VALUES (:tarif_id, :kullanici_id)";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute([
        ':tarif_id' => $tarif_id,
        ':kullanici_id' => $kullanici_id
    ])) {
        $_SESSION['mesaj'] = "Tarif başarıyla favorilere eklendi!";
    } else {
        $_SESSION['mesaj'] = "Favorilere eklerken bir hata oluştu.";
    }
}

header("Location: favoriler.php");
exit();
?>
