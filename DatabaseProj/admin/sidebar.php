<?php
$activePage = $activePage ?? '';

function isActive($page) {
  global $activePage;
  return $activePage === $page ? 'active' : '';
}
?>
<link rel="stylesheet" href="sidebar.css">
<div class="sidebar">
  <div class="logo-container">
    <img src="admin.svg" alt="Logo" />
    <h1>ADMIN</h1>
  </div>
  <button onclick="location.href='admin_order.php'" class="<?= isActive('orders') ?>">Orders</button>
  <button onclick="location.href='admin_stocks.php'" class="<?= isActive('stocks') ?>">Stocks</button>
  <button onclick="location.href='admin_inquiries.php'" class="<?= isActive('inquiries') ?>">User Inquires</button>
  <button onclick="location.href='admin_manage_accounts.php'" class="<?= isActive('accounts') ?>">Manage Accounts</button>
</div>
