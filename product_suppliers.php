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

$pageTitle = 'Supplier report';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
  <div>
    <p class="kicker">Procurement</p>
    <h1>Products by supplier</h1>
    <p class="muted"><?php echo count($rows); ?> items · linked to supplier master data</p>
  </div>
</div>

<div class="card">
  <label class="search-label" for="report-search">Find product or supplier
    <input id="report-search" type="search" placeholder="Type product, supplier or contact" autocomplete="off">
  </label>

  <table class="grid" id="report-table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Category</th>
        <th>Price (LKR)</th>
        <th>Qty</th>
        <th>Supplier</th>
        <th>Contact</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php echo htmlspecialchars($r['product_name']); ?></td>
        <td><?php echo htmlspecialchars($r['category']); ?></td>
        <td><?php echo number_format((float)$r['price'], 2); ?></td>
        <td><?php echo (int)$r['quantity']; ?></td>
        <td><?php echo htmlspecialchars($r['supplier_name'] ?? 'Unassigned'); ?></td>
        <td class="contact-cell">
          <?php
            $contact = $r['contact_info'] ?? '';
            $parts = preg_split('/\s*·\s*/', $contact);
            $email = $parts[0] ?? '';
            $phone = $parts[1] ?? '';
          ?>
          <?php if ($email && $email !== '—'): ?>
            <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a>
          <?php else: ?>
            —
          <?php endif; ?>
          <?php if ($phone): ?>
            <small><?php echo htmlspecialchars($phone); ?></small>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
document.getElementById('report-search').addEventListener('input', function () {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#report-table tbody tr').forEach(function (row) {
    row.style.display = row.innerText.toLowerCase().indexOf(q) === -1 ? 'none' : '';
  });
});
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>