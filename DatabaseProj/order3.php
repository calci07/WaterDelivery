<?php
// order3.php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_SESSION['order']['gallon_5_qty'] = (int)$_POST['gallon_5_qty'];
  $_SESSION['order']['bottle_500ml_qty'] = (int)$_POST['bottle_500ml_qty'];
  $_SESSION['order']['bottle_1l_qty'] = (int)$_POST['bottle_1l_qty'];
  $_SESSION['order']['delivery_datetime'] = $_POST['delivery_datetime'];
}
$order = $_SESSION['order'] ?? null;
if (!$order) {
  header('Location: order1.php');
  exit;
}

$gallon_total = $order['gallon_5_qty'] * 50;
$bottle_500ml_total = $order['bottle_500ml_qty'] * 15;
$bottle_1l_total = $order['bottle_1l_qty'] * 50;
$total = $gallon_total + $bottle_500ml_total + $bottle_1l_total;
$_SESSION['order']['total_amount'] = $total;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Review Order</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/services.css" />

</head>
<body>
  <?php include 'header.php'; ?>
  <main class="form-container">
    <h2>Order Summary</h2>
        <div class="stepper">
      <div class="step">1 <span>Personal Information</span></div>
      <div class="step">2 <span>Order</span></div>
      <div class="step active">3 <span>Payment & Confirmation</span></div>
      <div class="step">4 <span>Done</span></div>
    </div>
    <center>
    <h3>Customer Details</h3>
    <p>Name: <?= htmlspecialchars($order['contact_name']) ?></p>
    <p>Address: <?= htmlspecialchars($order['delivery_address']) ?></p>
    <p>Contact: <?= htmlspecialchars($order['contact_number']) ?></p>
    <p>Email: <?= htmlspecialchars($order['email']) ?></p>
    <p>Payment: <?= htmlspecialchars($order['payment_method']) ?></p>

    <h3>Order</h3>
    <p>5 Gallon Jug: <?= $order['gallon_5_qty'] ?> × ₱50 = ₱<?= $gallon_total ?></p>
    <p>500ml Bottled Water: <?= $order['bottle_500ml_qty'] ?> × ₱15 = ₱<?= $bottle_500ml_total ?></p>
    <p>1L Bottled Water: <?= $order['bottle_1l_qty'] ?> × ₱50 = ₱<?= $bottle_1l_total ?></p>
    <p><strong>Total: ₱<?= $total ?></strong></p>

    <h3>Delivery Schedule</h3>
    <p><?= date("F d, Y – h:i A", strtotime($order['delivery_datetime'])) ?></p>

<form action="save-order.php" method="POST">
  <button type="submit" class="next-button">Confirm Payment</button>
</form>

    </center>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>