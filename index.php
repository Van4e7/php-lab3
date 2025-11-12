<?php
require 'vendor/autoload.php';

use App\Database;
use App\User;
use App\Auth;

// Конфигурация базы данных
$config = require 'config/database.php';
$database = new Database($config);
$user = new User($database);
$auth = new Auth($user);

// Обработка форм
if ($_POST) {
    if (isset($_POST['register'])) {
        $auth->register($_POST['username'], $_POST['password'], $_POST['bg_color'], $_POST['text_color']);
    } elseif (isset($_POST['login'])) {
        $auth->login($_POST['username'], $_POST['password']);
    } elseif (isset($_POST['update_settings'])) {
        $auth->updateSettings($_POST['bg_color'], $_POST['text_color']);
    } elseif (isset($_POST['logout'])) {
        $auth->logout();
    }
}

// Получение текущих настроек
$currentUser = $auth->getCurrentUser();
$bgColor = $_COOKIE['bg_color'] ?? '#ffffff';
$textColor = $_COOKIE['text_color'] ?? '#000000';
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Lab 3 - Authentication System</title>
    <style>
        body {
            background-color: <?= $bgColor ?>;
            color: <?= $textColor ?>;
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, button {
            padding: 8px;
            margin: 5px 0;
            width: 100%;
            box-sizing: border-box;
        }
        .tabs {
            margin-bottom: 20px;
        }
        .tab {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
        .tab.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP Lab 3 - Authentication System</h1>
        
        <?php if (!$auth->isLoggedIn()): ?>
        
        <div class="tabs">
            <div class="tab active" onclick="showTab('login')">Login</div>
            <div class="tab" onclick="showTab('register')">Register</div>
        </div>
        
        <!-- Форма входа -->
        <div id="login-form">
            <h2>Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
        
        <!-- Форма регистрации -->
        <div id="register-form" style="display: none;">
            <h2>Register</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Background Color:</label>
                    <input type="color" name="bg_color" value="#ffffff">
                </div>
                <div class="form-group">
                    <label>Text Color:</label>
                    <input type="color" name="text_color" value="#000000">
                </div>
                <button type="submit" name="register">Register</button>
            </form>
        </div>
        
        <?php else: ?>
        
        <!-- Панель пользователя -->
        <div id="user-panel">
            <h2>Welcome, <?= htmlspecialchars($currentUser['username']) ?>!</h2>
            
            <h3>Update Settings</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Background Color:</label>
                    <input type="color" name="bg_color" value="<?= $bgColor ?>">
                </div>
                <div class="form-group">
                    <label>Text Color:</label>
                    <input type="color" name="text_color" value="<?= $textColor ?>">
                </div>
                <button type="submit" name="update_settings">Update Settings</button>
            </form>
            
            <form method="POST" style="margin-top: 20px;">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
        
        <?php endif; ?>
    </div>

    <script>
        function showTab(tabName) {
            // Скрыть все формы
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'none';
            
            // Показать выбранную форму
            document.getElementById(tabName + '-form').style.display = 'block';
            
            // Обновить активные табы
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>
</body>
</html>