<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
}
header('Location: products_list.php');
exit;