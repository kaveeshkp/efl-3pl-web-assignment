<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

header('Content-Type: application/json');
$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode([]);
    exit;
}
$stmt = $pdo->prepare(
    'SELECT id, product_name, category, price FROM products WHERE product_name LIKE ? ORDER BY product_name LIMIT 8'
);
$stmt->execute(['%' . $q . '%']);
echo json_encode($stmt->fetchAll());