<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskFlow</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">TaskFlow</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['user'])) : ?>
            <li class="nav-item">
              <span class="nav-link text-primary fw-bold"><?php echo htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8'); ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/tasks.php">Minhas Tarefas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/create_task.php">Criar Tarefa</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger fw-bold" href="/logout.php">Logout</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="/login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/register.php">Registrar</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container mt-4">