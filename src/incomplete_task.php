<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $task_id = intval($_POST['task_id']);
  $user_id = $_SESSION['user']['id'];

  $stmt = $conn->prepare('UPDATE tasks SET completed = 0 WHERE id = ? AND user_id = ?');
  $stmt->bind_param('ii', $task_id, $user_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = 'Tarefa desmarcada como concluída.';
  } else {
    $_SESSION['error'] = 'Erro ao desmarcar tarefa como concluída. Tente novamente.';
  }

  $stmt->close();
  header('Location: /tasks.php');
  exit;
}
