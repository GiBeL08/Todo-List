<?php
require_once 'config/database.php';

$pdo = getDBConnection();
$error = '';
$task = null;

$id = $_GET['id'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!$id) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    
    if (!$task) {
        $error = 'Задача не найдена!';
    }
} catch (PDOException $e) {
    $error = 'Ошибка при получении задачи: ' . $e->getMessage();
}

$pageTitle = 'Просмотр задачи';
include 'includes/header.php';
?>

<h1>Просмотр задачи</h1>

<div class="actions">
    <a href="index.php<?php echo $page > 1 ? '?page=' . $page : ''; ?>" class="btn btn-secondary">Вернуться к списку</a>
</div>

<?php if ($error && !$task): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <a href="index.php" class="btn btn-secondary">Вернуться к списку</a>
<?php elseif ($task): ?>
    <div class="task-view">
        <div class="task-view-header">
            <h2><?php echo htmlspecialchars($task['title']); ?></h2>
            <span class="status-badge status-<?php echo $task['status'] === 'Выполнено' ? 'completed' : 'in-progress'; ?>">
                <?php echo htmlspecialchars($task['status']); ?>
            </span>
        </div>
        
        <div class="task-view-info">
            <div class="task-info-item">
                <strong>ID задачи:</strong> <?php echo htmlspecialchars($task['id']); ?>
            </div>
            <div class="task-info-item">
                <strong>Статус:</strong> <?php echo htmlspecialchars($task['status']); ?>
            </div>
            <div class="task-info-item">
                <strong>Создано:</strong> <?php echo date('d.m.Y H:i', strtotime($task['created_at'])); ?>
            </div>
        </div>
        
        <div class="task-view-description">
            <h3>Описание:</h3>
            <?php if (!empty($task['description'])): ?>
                <p><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
            <?php else: ?>
                <p class="no-description">Описание отсутствует</p>
            <?php endif; ?>
        </div>
        
        <div class="task-view-actions">
            <?php if ($task['status'] === 'В процессе'): ?>
                <a href="actions/toggle_status.php?id=<?php echo $task['id']; ?>&page=<?php echo $page; ?>" 
                   class="btn btn-complete">
                    Отметить выполненным
                </a>
            <?php else: ?>
                <a href="actions/toggle_status.php?id=<?php echo $task['id']; ?>&page=<?php echo $page; ?>" 
                   class="btn btn-in-progress">
                    Отметить в процессе
                </a>
            <?php endif; ?>
            <a href="edit.php?id=<?php echo $task['id']; ?>&page=<?php echo $page; ?>" class="btn btn-edit">Редактировать</a>
            <a href="actions/delete.php?id=<?php echo $task['id']; ?>&page=<?php echo $page; ?>" 
               class="btn btn-delete" 
               onclick="return confirm('Вы уверены, что хотите удалить эту задачу?');">
                Удалить
            </a>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

