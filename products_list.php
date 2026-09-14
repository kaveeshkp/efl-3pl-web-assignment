<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$products = $pdo->query(
    'SELECT p.id, p.product_name, p.category, p.price, p.quantity, s.supplier_name
     FROM products p
     LEFT JOIN suppliers s ON s.id = p.supplier_id
     ORDER BY p.id DESC'
)->fetchAll();

$pageTitle = 'Products';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
  <div>
    <p class="kicker">Inventory</p>
    <h1>Products</h1>
  </div>
  <a class="btn" href="add_product.php">Add product</a>
</div>

<div class="card">
  <label class="search-label" for="search">Find a product
    <input id="search" type="search" placeholder="Start typing a product name" autocomplete="off" aria-label="Search products">
  </label>
  <div id="suggest-box" class="suggest" role="listbox"></div>

  <table class="grid">
    <thead>
      <tr>
        <th>Product</th>
        <th>Category</th>
        <th>Price (LKR)</th>
        <th>Qty</th>
        <th>Supplier</th>
        <th class="col-actions">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><?php echo htmlspecialchars($p['product_name']); ?></td>
        <td><?php echo htmlspecialchars($p['category']); ?></td>
        <td><?php echo number_format((float)$p['price'], 2); ?></td>
        <td><?php echo (int)$p['quantity']; ?></td>
        <td><?php echo htmlspecialchars($p['supplier_name'] ?? '—'); ?></td>
        <td class="col-actions">
          <a class="btn sm ghost" href="edit_product.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
          <a class="btn sm danger" href="delete_product.php?id=<?php echo (int)$p['id']; ?>"
             onclick="return confirm('Delete this product from inventory?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>