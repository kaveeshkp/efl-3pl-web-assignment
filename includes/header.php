<?php
require_once __DIR__ . '/auth.php';
$pageTitle = $pageTitle ?? 'EFL 3PL';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle); ?> · EFL 3PL</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
  <script src="js/app.js" defer></script>
</head>
<body>
<?php if ($user): ?>
  <aside class="sidebar" role="navigation" aria-label="Main">
    <div class="brand">
      <span class="logo">3PL</span>
      <div>
        <strong>EFL 3PL</strong>
        <small>Warehouse inventory</small>
      </div>
    </div>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="products_list.php">Products</a>
      <a href="add_product.php">Add product</a>
      <a href="product_suppliers.php">Product + suppliers</a>
    </nav>
    <a class="logout-btn" href="logout.php">Logout</a>
  </aside>
  <main class="wrap">
    <p class="who-inline"><?php echo htmlspecialchars($user['full_name']); ?></p>
<?php else: ?>
  <main class="guest-wrap">
<?php endif; ?>