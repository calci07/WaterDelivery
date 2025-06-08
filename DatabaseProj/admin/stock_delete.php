<?php


session_start();
require_once '../config.php';


if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true');
    exit();
}


$id = intval($_GET['id'] ?? 0);


if ($id <= 0) {
    die("Error: Invalid item ID.");
}

try {

    $stmt = $pdo->prepare("DELETE FROM stocks WHERE id = ?");
    $stmt->execute([$id]);

   
    if ($stmt->rowCount() > 0) {
      
        header("Location: admin_stocks.php");
        exit();
    } else {
        die("Error: Stock item not found.");
    }

} catch (PDOException $e) {

    die("Error deleting stock item: " . $e->getMessage());
}
?>