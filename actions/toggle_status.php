<?php
require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();

// Получаем ID задачи и номер страницы
$id = $_GET['id'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!$id) {
    header('Location: ../index.php');
    exit;
}

// Получаем текущий статус задачи
try {
    $stmt = $pdo->prepare("SELECT status FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    
    if (!$task) {
        header('Location: ../index.php');
        exit;
    }
    
    // Переключаем статус
    $newStatus = $task['status'] === 'Выполнено' ? 'В процессе' : 'Выполнено';
    
    // Обновляем статус в базе данных
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);
    
    // Перенаправляем на главную страницу с номером страницы
    $redirectUrl = '../index.php';
    if ($page > 1) {
        $redirectUrl .= '?page=' . $page;
    }
    header('Location: ' . $redirectUrl);
    exit;
} catch (PDOException $e) {
    die("Ошибка при изменении статуса задачи: " . $e->getMessage());
}
?>

