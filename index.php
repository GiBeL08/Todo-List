<?php
require_once 'config/database.php';

initDatabase();

$pdo = getDBConnection();

$tasksPerPage = TASKS_PER_PAGE;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, $currentPage);

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tasks");
$stmt->execute();
$totalTasks = $stmt->fetch()['total'];

$totalPages = ceil($totalTasks / $tasksPerPage);
$totalPages = max(1, $totalPages);

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $tasksPerPage;

$stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $tasksPerPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll();

$deleted = isset($_GET['deleted']) && $_GET['deleted'] == '1';
$added = isset($_GET['added']) && $_GET['added'] == '1';

$pageTitle = 'Список задач';
include 'includes/header.php';
?>

<h1>Список задач</h1>

<?php if ($deleted): ?>
    <div class="alert alert-success">Задача успешно удалена!</div>
<?php endif; ?>

<?php if ($added): ?>
    <div class="alert alert-success">Задача успешно добавлена!</div>
<?php endif; ?>

<div class="actions">
    <a href="add.php" class="btn btn-primary">Добавить задачу</a>
</div>

<?php if (empty($tasks)): ?>
    <div class="empty-state">
        <p>Пока нет задач. Создайте первую задачу!</p>
    </div>
<?php else: ?>
    <div class="pagination-info">
        <p>Всего задач: <strong><?php echo $totalTasks; ?></strong> | 
           Страница <strong><?php echo $currentPage; ?></strong> из <strong><?php echo $totalPages; ?></strong></p>
    </div>

    <div class="tasks-list">
        <?php foreach ($tasks as $task): ?>
            <div class="task-card <?php echo $task['status'] === 'Выполнено' ? 'completed' : ''; ?>">
                <div class="task-header">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <span class="status-badge status-<?php echo $task['status'] === 'Выполнено' ? 'completed' : 'in-progress'; ?>">
                        <?php echo htmlspecialchars($task['status']); ?>
                    </span>
                </div>
                
                <?php if (!empty($task['description'])): ?>
                    <p class="task-description"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                <?php endif; ?>
                
                <div class="task-footer">
                    <span class="task-date">
                        Создано: <?php echo date('d.m.Y H:i', strtotime($task['created_at'])); ?>
                    </span>
                    <div class="task-actions">
                        <a href="view.php?id=<?php echo $task['id']; ?>&page=<?php echo $currentPage; ?>" class="btn btn-view">Просмотреть</a>
                        <?php if ($task['status'] === 'В процессе'): ?>
                            <a href="actions/toggle_status.php?id=<?php echo $task['id']; ?>&page=<?php echo $currentPage; ?>" 
                               class="btn btn-complete">
                                Выполнено
                            </a>
                        <?php else: ?>
                            <a href="actions/toggle_status.php?id=<?php echo $task['id']; ?>&page=<?php echo $currentPage; ?>" 
                               class="btn btn-in-progress">
                                В процессе
                            </a>
                        <?php endif; ?>
                        <a href="edit.php?id=<?php echo $task['id']; ?>&page=<?php echo $currentPage; ?>" class="btn btn-edit">Редактировать</a>
                        <a href="actions/delete.php?id=<?php echo $task['id']; ?>&page=<?php echo $currentPage; ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Вы уверены, что хотите удалить эту задачу?');">
                            Удалить
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=1" class="pagination-btn">Первая</a>
                <a href="?page=<?php echo $currentPage - 1; ?>" class="pagination-btn">Предыдущая</a>
            <?php else: ?>
                <span class="pagination-btn disabled">Первая</span>
                <span class="pagination-btn disabled">Предыдущая</span>
            <?php endif; ?>

            <div class="pagination-numbers">
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1): ?>
                    <a href="?page=1" class="pagination-number">1</a>
                    <?php if ($startPage > 2): ?>
                        <span class="pagination-dots">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="pagination-number active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>" class="pagination-number"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <span class="pagination-dots">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $totalPages; ?>" class="pagination-number"><?php echo $totalPages; ?></a>
                <?php endif; ?>
            </div>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>" class="pagination-btn">Следующая</a>
                <a href="?page=<?php echo $totalPages; ?>" class="pagination-btn">Последняя</a>
            <?php else: ?>
                <span class="pagination-btn disabled">Следующая</span>
                <span class="pagination-btn disabled">Последняя</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
