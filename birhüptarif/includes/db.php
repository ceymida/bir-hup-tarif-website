<?php
$host = "127.0.0.1";
$db = 'birhup_tarif'; // Veritabanı adı
$user = 'root';       // XAMPP varsayılan kullanıcı adı
$pass = '';           // XAMPP için varsayılan şifre (boş)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}