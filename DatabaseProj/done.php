<?php
session_start();

if (!isset($_SESSION['user_email']) || empty($_SESSION['user_email'])) {
    header('Location: login.php?message=login_required');
    exit();
}


if (!isset($_SESSION['order_completed'])) {
    header('Location: order.php?message=invalid_access');
    exit();
}


unset($_SESSION['order_completed']);
?>


<?php
$orderStatusMessage = "Order Placed";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Success</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/navbar.css" />
</head>
<body>
<?php include 'header.php'; ?>
  <main class="form-container">
    <h2>Order Form</h2>
    <div class="stepper">
      <div class="step">1 <span>Fill Up</span></div>
      <div class="step">2 <span>Review</span></div>
      <div class="step">3 <span>Payment & Confirmation</span></div>
      <div class="step active">4 <span>Done!</span></div>
    </div>

    <div class="success-section">
      <img src="css/Vector.svg" alt="Order Placed" class="success-icon" />
      <p class="success-message"><?php echo htmlspecialchars($orderStatusMessage); ?></p>
      <a href="index.php" class="home-button">Home</a>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>
