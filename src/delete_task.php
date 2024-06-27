<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $task_id = intval($_POST['task_id']);
  $user_id = $_SESSION['user']['id'];

  $stmt = $conn->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
  $stmt->bind_param('ii', $task_id, $user_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = 'Tarefa excluída com sucesso.';
  } else {
    $_SESSION['error'] = 'Erro ao excluir tarefa. Tente novamente.';
  }

  $stmt->close();
  header('Location: /tasks.php');
  exit;
}
