<?php
require_once 'config/database.php';

// Password baru untuk admin
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Update password admin
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    if ($stmt->execute([$hashed_password])) {
        echo "Password admin berhasil direset!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<a href='login.php'>Klik di sini untuk login</a>";
    } else {
        echo "Gagal mereset password admin!";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 