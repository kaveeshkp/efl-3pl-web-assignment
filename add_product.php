<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$suppliers = $pdo->query('SELECT id, supplier_name FROM suppliers ORDER BY supplier_name')->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['product_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = $_POST['price'] ?? '';
    $qty      = $_POST['quantity'] ?? '';
    $desc     = trim($_POST['description'] ?? '');
    $sid      = $_POST['supplier_id'] !== '' ? (int)$_POST['supplier_id'] : null;

    if ($name === '' || $category === '' || $price === '' || $qty === '') {
        $errors[] = 'Name, category, price and quantity are required.';
    }

    if (!$errors) {
        $pdo->prepare(
            'INSERT INTO products (product_name, category, price, quantity, description, supplier_id)
             VALUES (?, ?, ?, ?, ?, ?)'
        )->execute([$name, $category, $price, $qty, $desc, $sid]);
        header('Location: products_list.php');
        exit;
    }
}

$pageTitle = 'Add product';
require __DIR__ . '/includes/header.php';
?>
<h1>Add product</h1>
<?php foreach ($errors as $err): ?>
  <div class="flash err"><?php echo htmlspecialchars($err); ?></div>
<?php endforeach; ?>
<form id="product-form" class="card" method="post">
  <label>Product name <input id="product_name" name="product_name" required></label>
  <label>Category <input id="category" name="category" required></label>
  <label>Price <input id="price" name="price" type="number" step="0.01" min="0" required></label>
  <label>Quantity <input id="quantity" name="quantity" type="number" min="0" required></label>
  <label>Supplier
    <select id="supplier_id" name="supplier_id">
      <option value="">— None —</option>
      <?php foreach ($suppliers as $s): ?>
        <option value="<?php echo (int)$s['id']; ?>"><?php echo htmlspecialchars($s['supplier_name']); ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Description <textarea id="description" name="description" rows="3"></textarea></label>
  <p id="form-error" class="flash err" hidden></p>
  <button class="btn" type="submit">Save product</button>
</form>
<script src="js/validate.js" defer></script>
<?php require __DIR__ . '/includes/footer.php'; ?>