<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
  $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
  $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
  $due_date = DateTime::createFromFormat('d/m/Y', $_POST['due_date'])->format('Y-m-d');
  $user_id = $user['id'];

  $stmt = $conn->prepare('INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)');
  $stmt->bind_param('isss', $user_id, $title, $description, $due_date);

  if ($stmt->execute()) {
    $_SESSION['success'] = 'Tarefa criada com sucesso.';
    header('Location: /tasks.php');
  } else {
    $_SESSION['error'] = 'Erro ao criar tarefa. Tente novamente.';
    header('Location: /create_task.php');
  }

  $stmt->close();
  exit;
}

include 'templates/header.php';
?>

<div class="container">
  <h1>Nova Tarefa</h1>
  <?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
      unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>
  <form action="/create_task.php" method="post">
    <div class="mb-3">
      <label for="title" class="form-label">Título</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Descrição</label>
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <div class="mb-3">
      <label for="due_date" class="form-label">Data de Entrega</label>
      <input type="text" class="form-control" id="due_date" name="due_date" required>
    </div>
    <input type="hidden" name="action" value="create">
    <button type="submit" class="btn btn-primary">Criar Tarefa</button>
    <a href="/tasks.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php include 'templates/footer.php'; ?>