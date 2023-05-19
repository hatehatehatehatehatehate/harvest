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
                <a href="authorize.php" class="header-right">Авторизация</a>
            </div>
        </div>
        <div class="cards">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $first_name = htmlspecialchars($_POST['first_name']);
                $last_name = htmlspecialchars($_POST['last_name']);
                $email = htmlspecialchars($_POST['email']);
                $login = htmlspecialchars($_POST['login']);
                $pass = htmlspecialchars($_POST['pass']);

                $checkLogin = dbQuery("SELECT id FROM `users` WHERE `login` = ?", [$login]);

                if ($checkLogin) {
                    $query = dbQuery('INSERT users (email, login, first_name, last_name, password, is_admin) values (?, ?, ?, ?, md5(?), 0)',
                        [$email, $login, $first_name, $last_name, $pass]);
                    echo '<div class="card"><p>Регистрация прошла успешно</p></div>';
                } else {
                    echo '<div class="card"><p>Логин занят</p></div>';
                }
            }
            ?>
            <div class="card">
                <h1>Регистрация</h1>
                <form action="" method="post">
                    <label>
                        <p>Фамилия</p>
                        <input type="text" name="last_name" required minlength="3" min="3" maxlength="30" max="30"
                               placeholder="Фамилия" pattern="^[А-Яа-яЁё -]+$">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <label>
                        <p>Имя</p>
                        <input type="text" name="first_name" required minlength="3" min="3" maxlength="20"
                               max="20"
                               placeholder="Имя" pattern="^[А-Яа-яЁё -]+$">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <label>
                        <p>Отчество</p>
                        <input type="text" name="middle_name" required minlength="3" min="3" maxlength="30"
                               max="30"
                               placeholder="Отчество" pattern="^[А-Яа-яЁё -]+$">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <label>
                        <p>Логин</p>
                        <input type="text" name="login" required minlength="3" min="3" maxlength="30"
                               max="30" placeholder="Логин" pattern="^[A-Za-z]+$">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <label>
                        <p>Email</p>
                        <input type="email" name="email" required placeholder="example@mail.ru">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <label>
                        <p>Пароль</p>
                        <input type="password" name="pass" required placeholder="Пароль">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <label>
                        <p>Повтор пароля</p>
                        <input type="password" name="pass-reply" required placeholder="Повторите пароль">
                        <a class="required-label">Заполните это поле</a>
                    </label>
                    <p>
                        <input id="checkbox-eula" type="checkbox" name="eula" required>
                        <label for="checkbox-eula">Согласие на обработку персональных данных</label>
                    </p>
                    <p>
                        <input type="submit" value="Зарегистрироваться">
                    </p>
                </form>
            </div>
        </div>
    </body>
</html>
