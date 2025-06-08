<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inquiry Form</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/navbar.css" />
</head>
<body>
<?php include 'header.php'; ?>
  <section class="hero inquiry-section">
    <div class="inquiry-form-wrapper">
      <h1 class="inquiry-title">INQUIRY FORM</h1>
      <form class="inquiry-form" method="post" action="submit-inquiry.php">
        <input type="text" name="fullname" placeholder="Full Name" required />
        <input type="email" name="email" placeholder="Email Address" required />
        <input type="tel" name="contact" placeholder="Contact Number" required />
        <textarea name="message" placeholder="Message" rows="4" required></textarea>
        <button type="submit" class="order-btn">SUBMIT</button>
      </form>
    </div>
    <div class="hero-img">
      <img src="css/Group.svg" alt="Glass of water" />
    </div>
  </section>
  <?php include 'footer.php'; ?>
</body>
</html>
