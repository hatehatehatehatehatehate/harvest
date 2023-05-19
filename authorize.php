<?php
require_once 'share.php';

if (!empty($user)) {
    http_redirect("index.php");
}
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Интернет-портал</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='css/style.css'>
        <link rel='stylesheet' href='css/auth.css'>
    </head>
    <body>
        <div class="header">
            <div class="header-wrap">
                <a href="index.php" class="logo">
                    <img src="sos.png" alt="logo" width="50px" height="50px">
                </a>
                <a href="index.php">На главную</a>
                <a href="register.php" class="header-right">Регистрация</a>
            </div>
        </div>
        <div class="cards">
            <div class="card">
                <form action="auth.php" method="post">
                    <h2>Авторизация</h2>
                    <label>
                        <p>Логин</p>
                        <input type="text" id="name" name="l" placeholder="Логин" required>
                    </label>
                    <label>
                        <p>Пароль</p>
                        <input type="password" id="pass" name="p" placeholder="Пароль" required>
                    </label>
                    <p>
                        <input type="submit" value="Войти">
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>
