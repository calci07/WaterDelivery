<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Water Delivery - Fill Up</title>
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/services.css" />
</head>

<body>
    <?php include 'header.php'; ?>
  <main class="form-container">
    <h2>Order Form</h2>

    <div class="stepper">
      <div class="step active">1 <span>Personal Information</span></div>
      <div class="step">2 <span>Order</span></div>
      <div class="step">3 <span>Payment & Confirmation</span></div>
      <div class="step">4 <span>Done</span></div>
    </div>

      <p class="section-title">Customer Details</p>
      <p>Please fill out the form to schedule your water delivery.</p>
    
    <form action="order2.php" method="POST">
      <div class="form-grid">
        <label>Contact Name
          <input type="text" name="contact_name" required>
        </label>
        <label>Delivery Address
          <input type="text" name="delivery_address" required>
        </label>
        <label>Contact Number
          <div class="phone-input">
            <div class="country-code">+63</div> 
            <input type="tel" name="contact_number" pattern="[0-9]{10}" required style="margin-top: 0px; margin-bottom: 0px; border-radius: 0 6px 6px 0;">          
          </div>
        </label>
        <label>Email Address
          <input type="email" name="email" required>
        </label>
        
        <div class="payment-mode">
          <p class="section-title">Mode of Payment</p>
            <label><input type="radio" name="payment" value="Cash on Delivery"> Cash on Delivery</label>
            <label><input type="radio" name="payment" value="E-wallet" checked> E-wallet</label>
        </div>
        
        <button type="submit" class="next-button">Next</button>
      </div>
    </form>
  </main>

  <script>
  </script>
  <?php include 'footer.php'; ?>
</body>
</html>