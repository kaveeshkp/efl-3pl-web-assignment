<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$counts = [
    'products'  => (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'suppliers' => (int)$pdo->query('SELECT COUNT(*) FROM suppliers')->fetchColumn(),
    'users'     => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
];

$pageTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>
<p class="kicker">Operations</p>
<div class="page-head">
  <div>
    <h1>Dashboard</h1>
    <p class="muted">Warehouse product desk · signed in as <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
  </div>
  <a class="btn" href="add_product.php">Add product</a>
</div>

<section class="stats">
  <div class="stat"><b><?php echo $counts['products']; ?></b><span>Active products</span></div>
  <div class="stat"><b><?php echo $counts['suppliers']; ?></b><span>Suppliers</span></div>
  <div class="stat"><b><?php echo $counts['users']; ?></b><span>Staff accounts</span></div>
</section>

<div class="card">
  <p class="kicker">Shortcuts</p>
  <h2>Daily work</h2>
  <p>
    <a class="btn" href="products_list.php">Open inventory</a>
    <a class="btn ghost" href="product_suppliers.php">Supplier join report</a>
  </p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>