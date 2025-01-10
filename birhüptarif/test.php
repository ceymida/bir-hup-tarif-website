
<?php
include 'includes/db.php';

if (isset($pdo)) {
    echo "PDO başarıyla dahil edildi!";
} else {
    echo "PDO dahil edilemedi. Dosya yolu veya içerik hatalı olabilir.";
}
?>
