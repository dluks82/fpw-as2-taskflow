<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}

$user = $_SESSION['user'];
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$allQuery = 'SELECT * FROM tasks WHERE user_id = ?';
$completedQuery = 'SELECT * FROM tasks WHERE user_id = ? AND completed = 1';
$pendingQuery = 'SELECT * FROM tasks WHERE user_id = ? AND completed = 0 AND due_date >= CURDATE()';
$overdueQuery = 'SELECT * FROM tasks WHERE user_id = ? AND completed = 0 AND due_date < CURDATE()';

$stmt = $conn->prepare($allQuery);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$allCount = $result->num_rows;
$stmt->close();

$stmt = $conn->prepare($completedQuery);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$completedCount = $result->num_rows;
$stmt->close();

$stmt = $conn->prepare($pendingQuery);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$pendingCount = $result->num_rows;
$stmt->close();

$stmt = $conn->prepare($overdueQuery);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$overdueCount = $result->num_rows;
$stmt->close();

$query = 'SELECT * FROM tasks WHERE user_id = ?';
$title = 'Todas as Tarefas';
if ($filter === 'completed') {
  $query .= ' AND completed = 1';
  $title = 'Tarefas Concluídas';
} elseif ($filter === 'pending') {
  $query .= ' AND completed = 0 AND due_date >= CURDATE()';
  $title = 'Tarefas Pendentes';
} elseif ($filter === 'overdue') {
  $query .= ' AND completed = 0 AND due_date < CURDATE()';
  $title = 'Tarefas Vencidas';
}

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include 'templates/header.php';
?>

<div class="container">
  <h1><?php echo $title; ?></h1>
  <div class="d-flex justify-content-between mb-3 flex-wrap">
    <a href="/create_task.php" class="btn btn-primary me-2 mb-2">
      <i class="fas fa-plus"></i><span class="d-none d-md-inline"> Criar Nova Tarefa</span>
    </a>
    <div class="btn-group mb-2" role="group">
      <a href="/tasks.php?filter=all" class="btn btn-outline-primary <?php echo $filter === 'all' ? 'active' : ''; ?>">
        <span class="badge bg-primary"><?php echo $allCount; ?></span><span class="d-none d-md-inline"> Todas</span>
      </a>
      <a href="/tasks.php?filter=completed" class="btn btn-outline-success <?php echo $filter === 'completed' ? 'active' : ''; ?>">
        <span class="badge bg-success"><?php echo $completedCount; ?></span><span class="d-none d-md-inline"> Concluídas</span>
      </a>
      <a href="/tasks.php?filter=pending" class="btn btn-outline-warning <?php echo $filter === 'pending' ? 'active' : ''; ?>">
        <span class="badge bg-warning"><?php echo $pendingCount; ?></span><span class="d-none d-md-inline"> Pendentes</span>
      </a>
      <a href="/tasks.php?filter=overdue" class="btn btn-outline-danger <?php echo $filter === 'overdue' ? 'active' : ''; ?>">
        <span class="badge bg-danger"><?php echo $overdueCount; ?></span><span class="d-none d-md-inline"> Vencidas</span>
      </a>
    </div>
  </div>
  <?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success">
      <?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8');
      unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($tasks)) : ?>
    <ul class="list-group">
      <?php foreach ($tasks as $task) : ?>
        <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $task['completed'] ? 'list-group-item-success' : ''; ?>" style="border-radius: 0.25rem;">
          <div>
            <h5><?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?></h5>
            <p><?php echo htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Data de Entrega: <?php echo htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="btn-group">
            <?php if (!$task['completed']) : ?>
              <form action="/complete_task.php" method="post" style="display:inline;">
                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                <button type="submit" class="btn btn-success me-1" title="Completar"><i class="fas fa-check"></i><span class="d-none d-md-inline"> Completar</span></button>
              </form>
            <?php else : ?>
              <form action="/incomplete_task.php" method="post" style="display:inline;">
                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                <button type="submit" class="btn btn-warning me-1" title="Desmarcar"><i class="fas fa-undo"></i><span class="d-none d-md-inline"> Desmarcar</span></button>
              </form>
            <?php endif; ?>
            <a href="/edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-warning me-1" title="Editar"><i class="fas fa-edit"></i><span class="d-none d-md-inline"> Editar</span></a>
            <button type="button" class="btn btn-danger" title="Excluir" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-task-id="<?php echo $task['id']; ?>">
              <i class="fas fa-trash"></i><span class="d-none d-md-inline"> Excluir</span>
            </button>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else : ?>
    <p>Nenhuma tarefa encontrada.</p>
  <?php endif; ?>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Você tem certeza que deseja excluir esta tarefa?
      </div>
      <div class="modal-footer">
        <form id="deleteTaskForm" action="/delete_task.php" method="post">
          <input type="hidden" name="task_id" id="deleteTaskId">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var confirmDeleteModal = document.getElementById('confirmDeleteModal');
    confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var taskId = button.getAttribute('data-task-id');
      var deleteTaskId = confirmDeleteModal.querySelector('#deleteTaskId');
      deleteTaskId.value = taskId;
    });
  });
</script>

<?php include 'templates/footer.php'; ?>