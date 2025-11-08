<?php
/**
 * Тест подключения к базе данных
 * Этот файл можно использовать для проверки подключения к MySQL
 */

require_once 'config/database.php';

echo "<h2>Тест подключения к базе данных</h2>";

try {
    echo "<p>Попытка подключения к MySQL...</p>";
    echo "<p>Хост: " . DB_HOST . "</p>";
    echo "<p>База данных: " . DB_NAME . "</p>";
    echo "<p>Пользователь: " . DB_USER . "</p>";
    
    // Инициализируем базу данных
    initDatabase();
    echo "<p style='color: green;'>✓ База данных инициализирована успешно!</p>";
    
    // Подключаемся к базе данных
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✓ Подключение к базе данных успешно!</p>";
    
    // Проверяем таблицу
    $stmt = $pdo->query("SHOW TABLES LIKE 'tasks'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Таблица 'tasks' существует!</p>";
        
        // Показываем структуру таблицы
        $stmt = $pdo->query("DESCRIBE tasks");
        $columns = $stmt->fetchAll();
        
        echo "<h3>Структура таблицы tasks:</h3>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Поле</th><th>Тип</th><th>Null</th><th>Ключ</th><th>По умолчанию</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Показываем количество записей
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM tasks");
        $count = $stmt->fetch()['count'];
        echo "<p>Количество задач в базе: <strong>" . $count . "</strong></p>";
    } else {
        echo "<p style='color: orange;'>⚠ Таблица 'tasks' не найдена. Она будет создана при первом открытии главной страницы.</p>";
    }
    
    echo "<hr>";
    echo "<p><a href='index.php'>Перейти на главную страницу</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Возможные решения:</h3>";
    echo "<ul>";
    echo "<li>Убедитесь, что MySQL сервер запущен</li>";
    echo "<li>Проверьте настройки в config/database.php</li>";
    echo "<li>Если используете пароль для root, укажите его в config/database.php</li>";
    echo "</ul>";
}

?>

