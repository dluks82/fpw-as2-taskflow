<?php
require_once 'init.php';

if (isset($_SESSION['user'])) {
  header('Location: /tasks.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email inválido.';
    header('Location: /login.php');
    exit;
  }

  $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;
      header('Location: /tasks.php');
      exit;
    }
  }

  $_SESSION['error'] = 'Email ou senha incorretos';
  header('Location: /login.php');
  exit;
}

include 'templates/header.php';
?>

<div class="container">
  <h1>Login</h1>
  <?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
      unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>
  <form action="/login.php" method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Senha</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
  </form>

  <p class="mt-3">Não tem uma conta? <a href="/register.php">Registre-se</a></p>
</div>

<?php include 'templates/footer.php'; ?>