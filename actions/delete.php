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

// Настройки пагинации
$tasksPerPage = TASKS_PER_PAGE;

// Удаляем задачу из базы данных
try {
    // Используем подготовленный запрос для удаления
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    
    // Получаем общее количество оставшихся задач
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tasks");
    $stmt->execute();
    $totalTasks = $stmt->fetch()['total'];
    
    // Вычисляем новое количество страниц
    $totalPages = ceil($totalTasks / $tasksPerPage);
    $totalPages = max(1, $totalPages);
    
    // Если текущая страница стала пустой, переходим на предыдущую или первую
    $redirectPage = $page;
    if ($totalTasks > 0 && $page > $totalPages) {
        $redirectPage = $totalPages;
    }
    
    // Перенаправляем на главную страницу с сообщением об успехе и номером страницы
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

