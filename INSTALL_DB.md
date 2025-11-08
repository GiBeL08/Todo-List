# Инструкция по запуску базы данных

## Вариант 1: XAMPP (Рекомендуется для Windows)

### Установка XAMPP:

1. **Скачайте XAMPP** с официального сайта: https://www.apachefriends.org/
2. **Установите XAMPP** в папку `C:\xampp` (по умолчанию)
3. **Запустите XAMPP Control Panel**

### Запуск MySQL в XAMPP:

1. Откройте **XAMPP Control Panel**
2. Нажмите кнопку **Start** напротив **MySQL**
3. Если MySQL запустился, вы увидите зеленый индикатор

### Настройка проекта:

Файл `config/database.php` уже настроен правильно:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_list');
define('DB_USER', 'root');
define('DB_PASS', '');
```

**База данных и таблица создадутся автоматически при первом запуске проекта!**

### Проверка подключения:

1. Откройте в браузере: `http://localhost/phpmyadmin`
2. Вы должны увидеть базу данных `todo_list` после первого запуска проекта

---

## Вариант 2: WAMP

### Установка WAMP:

1. **Скачайте WAMP** с официального сайта: https://www.wampserver.com/
2. **Установите WAMP**
3. **Запустите WAMP Server**

### Запуск MySQL в WAMP:

1. Кликните на иконку WAMP в системном трее
2. Выберите **MySQL** → **Start Service**
3. Иконка должна стать зеленой

### Настройка проекта:

Настройки в `config/database.php` остаются теми же:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
```

---

## Вариант 3: Отдельная установка MySQL

### Установка MySQL:

1. **Скачайте MySQL** с официального сайта: https://dev.mysql.com/downloads/mysql/
2. **Установите MySQL Server**
3. Запомните пароль для пользователя `root`

### Запуск MySQL:

1. Откройте **Службы** (Services) в Windows
2. Найдите **MySQL80** (или похожее название)
3. Запустите службу MySQL

### Настройка проекта:

Если у вас есть пароль для root, измените `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_list');
define('DB_USER', 'root');
define('DB_PASS', 'ваш_пароль');  // Укажите ваш пароль
```

---

## Вариант 4: Docker (для продвинутых пользователей)

### Запуск MySQL в Docker:

```bash
docker run --name mysql-todo -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=todo_list -p 3306:3306 -d mysql:8.0
```

### Настройка проекта:

Измените `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_list');
define('DB_USER', 'root');
define('DB_PASS', 'root');
```

---

## Проверка работы базы данных

### Способ 1: Через PHP приложение

1. Запустите PHP сервер:
   ```bash
   php -S localhost:8000
   ```

2. Откройте в браузере: `http://localhost:8000`

3. Если база данных работает, вы увидите страницу со списком задач

### Способ 2: Через phpMyAdmin (если используете XAMPP/WAMP)

1. Откройте: `http://localhost/phpmyadmin`
2. Войдите (обычно пользователь: `root`, пароль: пустой)
3. После первого запуска проекта вы увидите базу данных `todo_list`

### Способ 3: Через командную строку

```bash
mysql -u root -p
```

Если пароля нет:
```bash
mysql -u root
```

Затем выполните:
```sql
SHOW DATABASES;
USE todo_list;
SHOW TABLES;
SELECT * FROM tasks;
```

---

## Решение проблем

### Ошибка: "Access denied for user 'root'@'localhost'"

**Решение:**
1. Проверьте пароль в `config/database.php`
2. Если используете XAMPP/WAMP, пароль обычно пустой
3. Если устанавливали MySQL отдельно, используйте ваш пароль

### Ошибка: "Can't connect to MySQL server"

**Решение:**
1. Убедитесь, что MySQL сервер запущен
2. Проверьте, что порт 3306 не занят другим приложением
3. Проверьте настройки в `config/database.php`

### Ошибка: "Unknown database 'todo_list'"

**Решение:**
1. База данных создается автоматически при первом запуске
2. Убедитесь, что MySQL сервер запущен
3. Откройте главную страницу проекта в браузере
4. База данных и таблица создадутся автоматически

---

## Быстрый старт (XAMPP)

1. **Установите XAMPP** (если еще не установлен)
2. **Запустите XAMPP Control Panel**
3. **Запустите MySQL** (кнопка Start)
4. **Запустите PHP сервер**:
   ```bash
   cd C:\Users\nurzigit\Desktop\Todo_List_задание
   php -S localhost:8000
   ```
5. **Откройте в браузере**: `http://localhost:8000`
6. **Готово!** База данных и таблица создадутся автоматически

---

## Полезные команды

### Проверка статуса MySQL (XAMPP):
```
C:\xampp\mysql\bin\mysql.exe --version
```

### Запуск MySQL из командной строки (XAMPP):
```
C:\xampp\mysql\bin\mysql.exe -u root
```

### Остановка MySQL (XAMPP):
В XAMPP Control Panel нажмите **Stop** напротив MySQL

---

## Дополнительная информация

- **Хост**: localhost (или 127.0.0.1)
- **Порт**: 3306 (по умолчанию)
- **База данных**: todo_list (создается автоматически)
- **Таблица**: tasks (создается автоматически)
- **Пользователь**: root (по умолчанию в XAMPP/WAMP)
- **Пароль**: пустой (по умолчанию в XAMPP/WAMP)

Если у вас возникли проблемы, проверьте настройки в файле `config/database.php`.

