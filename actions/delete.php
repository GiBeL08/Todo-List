<?php
require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();

$id = $_GET['id'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!$id) {
    header('Location: ../index.php');
    exit;
}

$tasksPerPage = TASKS_PER_PAGE;

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tasks");
    $stmt->execute();
    $totalTasks = $stmt->fetch()['total'];
    
    $totalPages = ceil($totalTasks / $tasksPerPage);
    $totalPages = max(1, $totalPages);
    
    $redirectPage = $page;
    if ($totalTasks > 0 && $page > $totalPages) {
        $redirectPage = $totalPages;
    }
    
    $redirectUrl = '../index.php?deleted=1';
    if ($redirectPage > 1) {
        $redirectUrl .= '&page=' . $redirectPage;
    }
    header('Location: ' . $redirectUrl);
    exit;
} catch (PDOException $e) {
    die("Ошибка при удалении задачи: " . $e->getMessage());
}
?>

