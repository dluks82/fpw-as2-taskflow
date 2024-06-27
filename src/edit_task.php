<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}

$user = $_SESSION['user'];
$task_id = intval($_GET['id']);

$stmt = $conn->prepare('SELECT * FROM tasks WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $task_id, $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
  $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
  $due_date = DateTime::createFromFormat('d/m/Y', $_POST['due_date'])->format('Y-m-d');

  $stmt = $conn->prepare('UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ? AND user_id = ?');
  $stmt->bind_param('sssii', $title, $description, $due_date, $task_id, $user['id']);

  if ($stmt->execute()) {
    $_SESSION['success'] = 'Tarefa atualizada com sucesso.';
    header('Location: /tasks.php');
  } else {
    $_SESSION['error'] = 'Erro ao atualizar tarefa. Tente novamente.';
    header('Location: /edit_task.php?id=' . $task_id);
  }

  $stmt->close();
  exit;
}

include 'templates/header.php';
?>

<div class="container">
  <h1>Editar Tarefa</h1>
  <?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger">
      <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8');
      unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>
  <form action="/edit_task.php?id=<?php echo $task_id; ?>" method="post">
    <div class="mb-3">
      <label for="title" class="form-label">Título</label>
      <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Descrição</label>
      <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>
    <div class="mb-3">
      <label for="due_date" class="form-label">Data de Entrega</label>
      <input type="text" class="form-control" id="due_date" name="due_date" value="<?php echo (new DateTime($task['due_date']))->format('d/m/Y'); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar Tarefa</button>
  </form>
</div>

<?php include 'templates/footer.php'; ?>