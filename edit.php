<?php
require_once 'config/database.php';

$pdo = getDBConnection();
$error = '';
$task = null;

// Получаем ID задачи и номер страницы
$id = $_GET['id'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!$id) {
    header('Location: index.php');
    exit;
}

// Получаем задачу из базы данных
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

// Обработка формы редактирования задачи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $task) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'В процессе';
    
    // Валидация
    if (empty($title)) {
        $error = 'Название задачи обязательно для заполнения!';
    } else {
        try {
            // Используем подготовленный запрос для обновления
            $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $description, $status, $id]);
            
            // Перенаправляем на главную страницу с номером страницы
            $redirectUrl = 'index.php';
            if ($page > 1) {
                $redirectUrl .= '?page=' . $page;
            }
            header('Location: ' . $redirectUrl);
            exit;
        } catch (PDOException $e) {
            $error = 'Ошибка при обновлении задачи: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Редактировать задачу';
include 'includes/header.php';
?>

<h1>Редактировать задачу</h1>

<div class="actions">
    <a href="index.php<?php echo $page > 1 ? '?page=' . $page : ''; ?>" class="btn btn-secondary">Вернуться на главную</a>
</div>

<div class="form-container">
    <?php if ($error && !$task): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <a href="index.php<?php echo $page > 1 ? '?page=' . $page : ''; ?>" class="btn btn-secondary">Вернуться к списку</a>
    <?php elseif ($task): ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="edit.php?id=<?php echo $task['id']; ?>&page=<?php echo $page; ?>">
            <div class="form-group">
                <label for="title">Название задачи *</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="<?php echo htmlspecialchars($task['title']); ?>" 
                       required 
                       placeholder="Введите название задачи">
            </div>
            
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" 
                          name="description" 
                          rows="5" 
                          placeholder="Введите описание задачи (необязательно)"><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="status">Статус</label>
                <select id="status" name="status">
                    <option value="В процессе" <?php echo $task['status'] === 'В процессе' ? 'selected' : ''; ?>>В процессе</option>
                    <option value="Выполнено" <?php echo $task['status'] === 'Выполнено' ? 'selected' : ''; ?>>Выполнено</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <a href="index.php<?php echo $page > 1 ? '?page=' . $page : ''; ?>" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
