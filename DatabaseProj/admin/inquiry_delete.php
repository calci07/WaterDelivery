<?php
require 'config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Inquiry deleted.'); window.location.href='admin_inquiries.php';</script>";
} else {
    echo "<script>alert('Invalid inquiry ID.'); window.location.href='admin_inquiries.php';</script>";
}
?>
