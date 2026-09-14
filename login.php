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
    <div class="logo">3PL</div>
    <h1>EFL 3PL</h1>
    <h2>Warehouse product desk</h2>
    <p class="fine">Sign in with your staff account.</p>
  </div>
  <form class="login-form" method="post">
    <p class="kicker">Secure access</p>
    <h3>Sign in</h3>
    <?php if (!empty($_GET['registered'])): ?>
      <div class="flash">Account created. Please sign in.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="flash err" role="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <label>Username
      <input name="username" required>
    </label>
    <label>Password
      <input name="password" type="password" required>
    </label>
    <button class="btn login-submit" type="submit">Sign in</button>
    <p>New user? <a href="register.php">Create an account</a></p>
  </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>