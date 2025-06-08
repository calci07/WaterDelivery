<?php 
//icall yung database connection
require_once 'config.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Water Delivery</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/navbar.css" />
</head>
<body>
  <?php include 'header.php'; ?>

  <section class="hero">
    <div class="hero-text">
      <h1>WATER DELIVERY</h1>
      <p>Water Delivery delivers water to users precisely<br> where and when it's needed.</p>
      <button class="order-btn" onclick="location.href='order1.php'">ORDER NOW</button>
    </div>
    <div class="hero-img">
      <img src="css/Group.svg" alt="Glass of water">
    </div>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>