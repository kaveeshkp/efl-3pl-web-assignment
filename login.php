<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Enter username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password, full_name FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid username or password.';
    }
}

$pageTitle = 'Sign in';
require __DIR__ . '/includes/header.php';
?>
<section class="login-shell">
    <div class="login-brand">
    <span class="mark">3PL</span>
    <p class="brand-kicker">EFL Global · Internal system</p>
    <h1>EFL 3PL</h1>
    <p class="brand-system">Warehouse Product Desk</p>
    <p class="brand-lead">Staff access for inventory, suppliers and product records.</p>
    <ul class="brand-list">
      <li>Secure staff sign-in</li>
      <li>Product create, update and delete</li>
      <li>Supplier-linked stock view</li>
    </ul>
    <p class="fine">Authorized personnel only. Activity is recorded for audit.</p>
  </div>

  <form class="login-form" method="post">
    <p class="kicker">Staff portal</p>
    <h3>Sign in</h3>
    <p class="hint">Enter your username and password.</p>

    <?php if (!empty($_GET['registered'])): ?>
      <div class="flash">Account created. Please sign in.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="flash err" role="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <label>Username
      <input name="username" autocomplete="username" required>
    </label>
    <label>Password
      <input name="password" type="password" autocomplete="current-password" required>
    </label>
    <button class="btn login-submit" type="submit">Continue</button>
    <p class="form-foot">New staff? <a href="register.php">Request an account</a></p>
  </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>