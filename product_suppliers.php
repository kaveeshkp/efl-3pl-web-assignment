<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$rows = $pdo->query(
    'SELECT p.product_name, p.category, p.price, p.quantity,
            s.supplier_name, s.contact_info
     FROM products p
     LEFT JOIN suppliers s ON s.id = p.supplier_id
     ORDER BY p.product_name'
)->fetchAll();

$pageTitle = 'Products and suppliers';
require __DIR__ . '/includes/header.php';
?>
<h1>Products with suppliers</h1>
<p class="muted">SQL JOIN between products and suppliers.</p>
<table class="grid">
  <thead>
    <tr><th>Product</th><th>Category</th><th>Price</th><th>Qty</th><th>Supplier</th><th>Contact</th></tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><?php echo htmlspecialchars($r['product_name']); ?></td>
      <td><?php echo htmlspecialchars($r['category']); ?></td>
      <td><?php echo number_format((float)$r['price'], 2); ?></td>
      <td><?php echo (int)$r['quantity']; ?></td>
      <td><?php echo htmlspecialchars($r['supplier_name'] ?? 'Unassigned'); ?></td>
      <td><?php echo htmlspecialchars($r['contact_info'] ?? '—'); ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php require __DIR__ . '/includes/footer.php'; ?>