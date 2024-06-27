<?php
require_once 'init.php';

if (isset($_SESSION['user'])) {
  header('Location: /tasks.php');
} else {
  header('Location: /login.php');
}
exit;
