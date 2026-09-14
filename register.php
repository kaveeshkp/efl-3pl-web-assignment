<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$old = ['username' => '', 'email' => '', 'full_name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['username']  = trim($_POST['username'] ?? '');
    $old['email']     = trim($_POST['email'] ?? '');
    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $password         = $_POST['password'] ?? '';

    if ($old['username'] === '' || $old['email'] === '' || $old['full_name'] === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($password) > 0 && strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
        $check->execute([$old['email'], $old['username']]);
        if ($check->fetch()) {
            $errors[] = 'Username or email is already registered.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare(
            'INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)'
        )->execute([$old['username'], $hash, $old['email'], $old['full_name']]);
        header('Location: login.php?registered=1');
        exit;
    }
}

$pageTitle = 'Register';
require __DIR__ . '/includes/header.php';
?>
<section class="login-shell">
  <div class="login-brand">
    <span class="mark">3PL</span>
    <p class="brand-kicker">EFL Global · Internal system</p>
    <h1>EFL 3PL</h1>
    <p class="brand-system">Create staff account</p>
    <p class="brand-lead">Register with your official name and work email before using the warehouse desk.</p>
    <ul class="brand-list">
      <li>Unique username and email</li>
      <li>Password stored securely</li>
      <li>Sign in after registration</li>
    </ul>
    <p class="fine">Use a work email. Do not share this account.</p>
  </div>

  <form class="login-form compact" method="post">
    <p class="kicker">Registration</p>
    <h3>Create an account</h3>
    <p class="hint">All fields are required.</p>

    <?php foreach ($errors as $err): ?>
      <div class="flash err"><?php echo htmlspecialchars($err); ?></div>
    <?php endforeach; ?>

    <label>Full name
      <input name="full_name" required value="<?php echo htmlspecialchars($old['full_name']); ?>">
    </label>
    <label>Username
      <input name="username" required value="<?php echo htmlspecialchars($old['username']); ?>">
    </label>
    <label>Email
      <input name="email" type="email" required value="<?php echo htmlspecialchars($old['email']); ?>">
    </label>
    <label>Password
      <input name="password" type="password" required minlength="6">
    </label>

    <button class="btn login-submit" type="submit">Create account</button>
    <p class="form-foot">Already registered? <a href="login.php">Sign in</a></p>
  </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>