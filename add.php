<?php
require_once 'config/database.php';

$pdo = getDBConnection();
$error = '';

// Обработка формы добавления задачи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'В процессе';
    
    // Валидация
    if (empty($title)) {
        $error = 'Название задачи обязательно для заполнения!';
    } else {
        try {
            // Используем подготовленный запрос
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
            $stmt->execute([$title, $description, $status]);
            
            // Перенаправляем на главную страницу
            header('Location: index.php?added=1');
            exit;
        } catch (PDOException $e) {
            $error = 'Ошибка при добавлении задачи: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Добавить задачу';
include 'includes/header.php';
?>

<h1>Добавить новую задачу</h1>

<div class="actions">
    <a href="index.php" class="btn btn-secondary">Вернуться на главную</a>
</div>

<div class="form-container">
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="add.php">
        <div class="form-group">
            <label for="title">Название задачи *</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" 
                   required 
                   placeholder="Введите название задачи">
        </div>
        
        <div class="form-group">
            <label for="description">Описание</label>
            <textarea id="description" 
                      name="description" 
                      rows="5" 
                      placeholder="Введите описание задачи (необязательно)"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="status">Статус</label>
            <select id="status" name="status">
                <option value="В процессе" <?php echo (isset($status) && $status === 'В процессе') ? 'selected' : ''; ?>>В процессе</option>
                <option value="Выполнено" <?php echo (isset($status) && $status === 'Выполнено') ? 'selected' : ''; ?>>Выполнено</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
