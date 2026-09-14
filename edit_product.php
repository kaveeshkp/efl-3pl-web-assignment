<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    header('Location: products_list.php');
    exit;
}

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
            'UPDATE products
             SET product_name=?, category=?, price=?, quantity=?, description=?, supplier_id=?
             WHERE id=?'
        )->execute([$name, $category, $price, $qty, $desc, $sid, $id]);
        header('Location: products_list.php');
        exit;
    }
}

$pageTitle = 'Edit product';
require __DIR__ . '/includes/header.php';
?>
<h1>Edit product</h1>
<form id="product-form" class="card" method="post">
  <label>Product name
    <input id="product_name" name="product_name" required value="<?php echo htmlspecialchars($product['product_name']); ?>">
  </label>
  <label>Category
    <input id="category" name="category" required value="<?php echo htmlspecialchars($product['category']); ?>">
  </label>
  <label>Price
    <input id="price" name="price" type="number" step="0.01" required value="<?php echo htmlspecialchars($product['price']); ?>">
  </label>
  <label>Quantity
    <input id="quantity" name="quantity" type="number" required value="<?php echo htmlspecialchars($product['quantity']); ?>">
  </label>
  <label>Supplier
    <select name="supplier_id">
      <option value="">— None —</option>
      <?php foreach ($suppliers as $s): ?>
        <option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)$product['supplier_id'] === (int)$s['id']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($s['supplier_name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Description
    <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
  </label>
  <p id="form-error" class="flash err" hidden></p>
  <button class="btn" type="submit">Update product</button>
</form>
<script src="js/validate.js" defer></script>
<?php require __DIR__ . '/includes/footer.php'; ?>