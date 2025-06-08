<?php

//Add new stock item

session_start();
require_once '../config.php';


if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $product_name = trim($_POST['product_name'] ?? '');
    $quantity = intval($_POST['quantity_in_stock'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $unit = trim($_POST['unit'] ?? '');

 
    if (empty($product_name) || $quantity < 0 || $price < 0 || empty($unit)) {
        die("Error: Please fill all fields with valid values.");
    }

    try {
      
        $stmt = $pdo->prepare("INSERT INTO stocks (product_name, quantity_in_stock, price, unit) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product_name, $quantity, $price, $unit]);

       
        header("Location: admin_stocks.php");
        exit();

    } catch (PDOException $e) {
      
        die("Error adding stock item: " . $e->getMessage());
    }
} else {
   
    header("Location: admin_stocks.php");
    exit();
}
?>

<?php


//edit existing stock item


session_start();
require_once '../config.php';

//pang check if admin is authenticated
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = intval($_POST['id'] ?? 0);
    $product_name = trim($_POST['product_name'] ?? '');
    $quantity = intval($_POST['quantity_in_stock'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $unit = trim($_POST['unit'] ?? '');

    //pang validate ng required fields
    if ($id <= 0 || empty($product_name) || $quantity < 0 || $price < 0 || empty($unit)) {
        die("Error: Please fill all fields with valid values.");
    }

    try {
        
        $stmt = $pdo->prepare("UPDATE stocks SET product_name = ?, quantity_in_stock = ?, price = ?, unit = ? WHERE id = ?");
        $stmt->execute([$product_name, $quantity, $price, $unit, $id]);

       
        if ($stmt->rowCount() > 0) {
               //redirect back to stocks page (pag success)
            header("Location: admin_stocks.php");
            exit();
        } else {
            die("Error: Stock item not found or no changes made.");
        }

    } catch (PDOException $e) {
       
        die("Error updating stock item: " . $e->getMessage());
    }
} else {
   
    header("Location: admin_stocks.php");
    exit();
}
?>

<?php

//delete stock item

session_start();
require_once '../config.php';

//pang check if admin is authenticated
if (!isset($_SESSION['admin_username'])) {
    header('Location: DatabaseProj/index.php?login_required=true');
    exit();
}


$id = intval($_GET['id'] ?? 0);

//pang validate ng ID
if ($id <= 0) {
    die("Error: Invalid item ID.");
}

try {
    //delete item from database 
    $stmt = $pdo->prepare("DELETE FROM stocks WHERE id = ?");
    $stmt->execute([$id]);

    // Check if any row was actually deleted
    if ($stmt->rowCount() > 0) {
        //redirect back to stocks page (pag success)
        header("Location: admin_stocks.php");
        exit();
    } else {
        die("Error: Stock item not found.");
    }

} catch (PDOException $e) {

    die("Error deleting stock item: " . $e->getMessage());
}
?>