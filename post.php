<?php require_once 'share.php'; ?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Мини-форум для одной газеты</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <?php if (empty($user) || $user["is_ban"] === "1"): ?>
            <a href="index.php">На главную</a>
        <?php else: ?>
            <div class='posts'>
                <div class='post'>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($_POST["id"]))
                            switch ($_POST["act"]) {
                                case "create-comment":
                                    $text = htmlspecialchars(trim($_POST['text']));
                                    dbQuery('INSERT comments(user_id, post_id, text, create_date) values (?, ?, ?, now())', [$user['id'], $_POST['id'], $text]);
                                    echo "<p>Комментарий опубликован</p>";
                                    break;
                            }
                    } ?>
                    <a href="index.php">На главную</a>
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>
