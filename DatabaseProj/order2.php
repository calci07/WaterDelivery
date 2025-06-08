<?php
// order2.php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_SESSION['order'] = [
    'contact_name' => $_POST['contact_name'],
    'delivery_address' => $_POST['delivery_address'],
    'contact_number' => '+63' . $_POST['contact_number'],
    'email' => $_POST['email'],
    'payment_method' => $_POST['payment']
  ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Water Delivery - Review Order</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/services.css" />
<style>
  .form-layout {
  display: flex;
  justify-content: center; /* centers the form horizontally */
  }

  form {
  display: flex;
  flex-direction: column;
  align-items: center; /* centers items inside the form */
  width: 100%;
  }

  form label {
  width: 100%;
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
  font-family: Arial, sans-serif;
  }
</style>
</head>
<body>
        <?php include 'header.php'; ?>
  <main class="form-container">
    <h2>Order Permit</h2>
    <div class="stepper">
      <div class="step">1 <span>Personal Information</span></div>
      <div class="step active">2 <span>Review Order</span></div>
      <div class="step">3 <span>Payment & Confirmation</span></div>
      <div class="step">4 <span>Done</span></div>
    </div>
    <div class="form-layout">
      <form action="order3.php" method="POST">
        <p class="section-title">Select Water Product(s)</p>
        <label>5 Gallon Jug (₱50)
          <input type="number" name="gallon_5_qty" value="1" min="0">
        </label>
        <label>500ml Bottled Water (₱15)
          <input type="number" name="bottle_500ml_qty" value="1" min="0">
        </label>
        <label>1 Liter Bottled Water (₱50)
          <input type="number" name="bottle_1l_qty" value="1" min="0">
        </label>

          <label><p class="section-title">Preferred Delivery Date & Time</p>
            <input type="datetime-local" name="delivery_datetime" required>
          </label>
                <div class="button-group">
        <button type="button" class="next-button" onclick="window.location.href='order1.php'">&lt; Previous</button>
        <button type="submit" class="next-button">Next &gt;</button>
      </div>
    </div>
      </form>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>
