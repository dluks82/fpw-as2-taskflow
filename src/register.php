<?php
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email inválido.';
    header('Location: /register.php');
    exit;
  }

  $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
  $stmt->bind_param('sss', $name, $email, $password);

  if ($stmt->execute()) {
    $_SESSION['user'] = ['id' => $stmt->insert_id, 'name' => $name, 'email' => $email];
    header('Location: /tasks.php');
  } else {
    $_SESSION['error'] = 'Erro ao registrar. Tente novamente.';
    header('Location: /register.php');
  }

  $stmt->close();
  exit;
}

include 'templates/header.php';
?>

<div class="container">
  <h1>Registrar</h1>
  <?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
      unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>
  <form action="/register.php" method="post">
    <div class="mb-3">
      <label for="name" class="form-label">Nome</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Senha</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrar</button>
  </form>
</div>

<?php include 'templates/footer.php'; ?>