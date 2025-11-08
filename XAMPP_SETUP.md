# Настройка проекта для XAMPP

## Шаг 1: Установка XAMPP

Если XAMPP еще не установлен:
1. Скачайте XAMPP с официального сайта: https://www.apachefriends.org/
2. Установите XAMPP в папку `C:\xampp` (рекомендуется)

## Шаг 2: Перемещение проекта

### Вариант А: Переместить весь проект в htdocs

1. Скопируйте всю папку `Todo_List_задание` в:
   ```
   C:\xampp\htdocs\Todo_List_задание
   ```

2. Или переименуйте папку для удобства:
   ```
   C:\xampp\htdocs\todo
   ```

### Вариант Б: Создать символьную ссылку (для разработки в VS Code)

Если вы хотите работать в VS Code из текущей папки, но проект должен быть доступен через XAMPP:

1. Откройте командную строку от имени администратора
2. Выполните команду:
   ```cmd
   mklink /D C:\xampp\htdocs\todo C:\Users\nurzigit\Desktop\Todo_List_задание
   ```

Теперь проект будет доступен по адресу: `http://localhost/todo`

## Шаг 3: Запуск XAMPP

1. Откройте **XAMPP Control Panel**
2. Запустите **Apache** (нажмите Start)
3. Запустите **MySQL** (нажмите Start)
4. Убедитесь, что оба сервиса запущены (зеленый индикатор)

## Шаг 4: Настройка базы данных

### Настройка подключения

Откройте файл `config/database.php` и проверьте настройки:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_list');
define('DB_USER', 'root');
define('DB_PASS', '');  // Обычно пустой в XAMPP
```

**Важно:** Если вы установили пароль для MySQL в XAMPP, укажите его в `DB_PASS`.

### Создание базы данных

База данных создастся автоматически при первом открытии проекта!

Или создайте вручную через phpMyAdmin:
1. Откройте: `http://localhost/phpmyadmin`
2. Создайте новую базу данных: `todo_list`
3. Таблица `tasks` создастся автоматически при первом запуске

## Шаг 5: Открытие проекта

1. Убедитесь, что Apache и MySQL запущены в XAMPP
2. Откройте в браузере:
   ```
   http://localhost/Todo_List_задание
   ```
   или
   ```
   http://localhost/todo
   ```
   (в зависимости от того, как вы назвали папку)

3. Проект должен открыться и автоматически создать базу данных и таблицу

## Шаг 6: Работа в VS Code

### Если проект в htdocs:

1. Откройте VS Code
2. File → Open Folder
3. Выберите: `C:\xampp\htdocs\Todo_List_задание`
4. Редактируйте файлы как обычно
5. Сохраняйте и обновляйте страницу в браузере

### Если используете символьную ссылку:

1. Откройте VS Code
2. File → Open Folder
3. Выберите: `C:\Users\nurzigit\Desktop\Todo_List_задание`
4. Редактируйте файлы
5. Изменения сразу будут видны через XAMPP

## Проверка работы

### Тест 1: Проверка Apache
Откройте: `http://localhost`
Должна открыться страница XAMPP

### Тест 2: Проверка MySQL
Откройте: `http://localhost/phpmyadmin`
Должен открыться phpMyAdmin

### Тест 3: Проверка проекта
Откройте: `http://localhost/Todo_List_задание` или `http://localhost/todo`
Должна открыться главная страница проекта

### Тест 4: Проверка базы данных
Откройте: `http://localhost/Todo_List_задание/test_db.php`
Должна открыться страница с информацией о подключении к БД

## Решение проблем

### Проблема: Apache не запускается

**Решение:**
1. Проверьте, не занят ли порт 80 другим приложением (Skype, IIS и т.д.)
2. В XAMPP Control Panel нажмите Config → Apache → httpd.conf
3. Измените порт с 80 на 8080 (найдите `Listen 80` и замените на `Listen 8080`)
4. Перезапустите Apache
5. Откройте: `http://localhost:8080/Todo_List_задание`

### Проблема: MySQL не запускается

**Решение:**
1. Проверьте, не занят ли порт 3306
2. Закройте другие программы, использующие MySQL
3. Перезапустите MySQL в XAMPP Control Panel

### Проблема: Ошибка подключения к БД

**Решение:**
1. Убедитесь, что MySQL запущен в XAMPP
2. Проверьте настройки в `config/database.php`
3. Если установили пароль для root, укажите его в `DB_PASS`

### Проблема: Страница не найдена (404)

**Решение:**
1. Убедитесь, что папка проекта находится в `C:\xampp\htdocs\`
2. Проверьте правильность пути в браузере
3. Убедитесь, что Apache запущен

## Структура проекта в XAMPP

После перемещения в htdocs структура будет такой:

```
C:\xampp\htdocs\Todo_List_задание\
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── actions/
│   ├── delete.php
│   └── toggle_status.php
├── assets/
│   └── css/
│       └── style.css
├── index.php
├── add.php
├── edit.php
├── view.php
└── test_db.php
```

## Полезные ссылки

- **Главная страница проекта:** `http://localhost/Todo_List_задание`
- **phpMyAdmin:** `http://localhost/phpmyadmin`
- **XAMPP Dashboard:** `http://localhost/dashboard`

## Дополнительные настройки

### Изменение порта Apache (если порт 80 занят)

1. Откройте: `C:\xampp\apache\conf\httpd.conf`
2. Найдите: `Listen 80`
3. Измените на: `Listen 8080`
4. Сохраните файл
5. Перезапустите Apache
6. Используйте: `http://localhost:8080/Todo_List_задание`

### Настройка виртуального хоста (опционально)

Для удобства можно создать виртуальный хост:

1. Откройте: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Добавьте:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/Todo_List_задание"
       ServerName todo.local
   </VirtualHost>
   ```
3. Откройте: `C:\Windows\System32\drivers\etc\hosts` (от имени администратора)
4. Добавьте: `127.0.0.1 todo.local`
5. Перезапустите Apache
6. Откройте: `http://todo.local`

---

**Готово!** Теперь вы можете работать с проектом в VS Code, а запускать его через XAMPP.

