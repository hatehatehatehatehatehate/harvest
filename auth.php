<?php
require_once 'share.php';

$user = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!IsNullOrEmptyString($_POST["l"]) && !IsNullOrEmptyString($_POST["p"])) {
        $user = dbQuery("SELECT u.* from `users` u where (login=? or email=?) and password=md5(?) limit 1", [$_POST["l"], $_POST["l"], $_POST["p"]]);
        if ($user != false) {
            setcookie("uid", $user["id"]);
            setcookie("pass", $_POST["p"]);
        }
    }
}

if (empty($user)) {
    setcookie("uid", null);
    setcookie("pass", null);
}

redirect("index.php");
?>

<!--<!DOCTYPE html>-->
<!--<html lang="ru">-->
<!--    <head>-->
<!--        <meta charset="UTF-8">-->
<!--        <title>Мини-форум для одной газеты</title>-->
<!--        <link rel='stylesheet' href='style.css'>-->
<!--    </head>-->
<!--    <body>-->
<!--        <div class="posts">-->
<!--            <div class="post" style="text-align: center">-->
<!--                --><?php //if (empty($user)): ?>
<!--                    <p>Вы не авторизированны</p>-->
<!--                --><?php //else: ?>
<!--                    <p>Вы авторизированны как <strong>--><? //= $user["login"] ?><!--</strong></p>-->
<!--                    --><?php //if ($user["is_ban"] === "1"): ?>
<!--                        <p>-->
<!--                            <strong>-->
<!--                                Ваш аккаунт забанен.<br>-->
<!--                                Вы не можете добавлять посты и комментировать.-->
<!--                            </strong>-->
<!--                        </p>-->
<!--                    --><?php //endif; ?>
<!--                --><?php //endif; ?>
<!---->
<!--                <a href="index.php">На главную</a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </body>-->
<!--</html>-->