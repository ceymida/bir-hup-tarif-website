<?php

global $pdo;
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isim = $_POST['isim'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];
    $sifre_onay = $_POST['sifre_onay'];

    if ($sifre !== $sifre_onay) {
        $hata = "Şifreler uyuşmuyor.";
    } else {
        $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);
        $query = "INSERT INTO kullanicilar (isim, email, sifre) VALUES (:isim, :email, :sifre)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':isim', $isim);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':sifre', $sifre_hash);
        $stmt->execute();
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="text-center">Kayıt Ol</h3>
                </div>
                <div class="card-body">
<!--                    --><?php //if (isset($hata)): ?>
<!--                        <div class="alert alert-danger">-->
<!--                            --><?php //= htmlspecialchars($hata); ?>
<!--                        </div>-->
<!--                    --><?php //endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="isim" class="form-label">Ad Soyad</label>
                            <input type="text" name="isim" id="isim" class="form-control" placeholder="Ad ve soyadınızı girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-posta adresinizi girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="sifre" class="form-label">Şifre</label>
                            <input type="password" name="sifre" id="sifre" class="form-control" placeholder="Şifrenizi girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="sifre_onay" class="form-label">Şifre Onayı</label>
                            <input type="password" name="sifre_onay" id="sifre_onay" class="form-control" placeholder="Şifrenizi tekrar girin" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Kayıt Ol</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
