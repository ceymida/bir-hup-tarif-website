<?php
session_start();
include('includes/db.php');  // Veritabanı bağlantısı
include('includes/header.php'); // Header dosyası

// Giriş yapılmışsa, ana sayfaya yönlendir
if (isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit();
}

// Login işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $sifre = trim($_POST['sifre']);

    if (empty($email) || empty($sifre)) {
        $error = "E-posta ve şifre alanı boş olamaz!";
    } else {
        // Veritabanında kullanıcıyı bul
        $stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Kullanıcıyı bulduysak ve şifre doğruysa giriş yap
        if ($user && password_verify($sifre, $user['sifre'])) {
            // Giriş başarılı
            $_SESSION['kullanici_id'] = $user['id'];
            $_SESSION['user_name'] = $user['isim'];
            header("Location: index.php");
            exit();
        } else {
            // Giriş başarısız
            $error = "Geçersiz e-posta veya şifre!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="text-center">Giriş Yap</h3>
                </div>
                <div class="card-body">
<!--                    --><?php //if (isset($error)): ?>
<!--                        <div class="alert alert-danger">-->
<!--                            --><?php //echo $error; ?>
<!--                        </div>-->
<!--                    --><?php //endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-posta adresinizi girin" required>
                        </div>
                        <div class="mb-3">
                            <label for="sifre" class="form-label">Şifre</label>
                            <input type="password" name="sifre" id="sifre" class="form-control" placeholder="Şifrenizi girin" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Giriş Yap</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Hesabınız yok mu? <a href="register.php">Kayıt Olun</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
