<?php
require_once 'share.php';

if (empty($user)) {
    redirect("index.php");
    exit;
}

$modRequestCount = +dbQuery('SELECT count(*) cnt from `posts` where author = ? and is_fixed is null', [$user['id']])["cnt"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $modRequestCount < 3) {
    if ($_FILES["file"] !== null && $_FILES["file"]["size"] > 0) {
        $title = htmlspecialchars(trim($_POST['title']));
        $text = htmlspecialchars(trim($_POST['text']));
        $category = htmlspecialchars(trim($_POST['category']));

        dbQuery('INSERT posts (title, text, author, create_time, is_fixed, category_id) values (?, ?, ?, now(), null, ?)', [$title, $text, $user['id'], $category]);

        move_uploaded_file($_FILES['file']['tmp_name'], "userimg/" . lastInsertId() . "-before.png");

        redirect("create-post.php", ["newok" => "1"]);
        exit;
    } else {
        redirect("create-post.php", ["newok" => "0", "size" => $_FILES["file"]["size"]]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Интернет-портал</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='css/style.css'>
        <link rel='stylesheet' href='css/index.css'>
        <style>
            p {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="header-wrap">
                <a href="index.php" class="logo">
                    <img src="sos.png" alt="logo" width="50px" height="50px">
                </a>
                <?php if (empty($user)) : ?>
                    <a href="authorize.php" class="header-right">Авторизация</a>
                    <a href="register.php">Регистрация</a>
                <?php else: ?>
                    <a href="profile.php">Мои заявки</a>
                    <?php if ($user["is_ban"] === "0"): ?>
                        <a href="create-post.php">Создать заявку</a>
                    <?php endif ?>
                    <?php if ($user["is_admin"]) : ?>
                        <a href="admin.php">Модерировать</a>
                    <?php endif ?>
                    <a href="profile.php" class="header-right">
                        <?= ($user["is_admin"] ? "👑" : "") . ($user["is_ban"] ? "🙊" : "") . $user["login"] ?>
                    </a>
                <?php endif ?>
            </div>
        </div>
        <div class="cards">
            <?php if ($_GET['newok'] === "1"): ?>
                <div class='card'>
                    <p>Ваша заявка опубликована</p>
                    <a href='profile.php'>Список моих постов</a>
                </div>
            <?php endif; ?>
            <h1>Добавить пост</h1>
            <?php if ($modRequestCount < 3): ?>
                <div class="card">
                    <form enctype="multipart/form-data" action="" method="post">
                        <label>
                            <p>Заголовок</p>
                            <input type="text" name="title" required minlength="3" min="3" maxlength="50"
                                   max="50"
                                   placeholder="Заголовок поста">
                        </label>
                        <label>
                            <p>Категория</p>
                            <select name="category" required>
                                <?php foreach (dbQueryAll('SELECT * from categories limit 500', []) as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['emoji'] ?> <?= $cat['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>
                            <p>Текст</p>
                            <textarea name="text" required minlength="3" maxlength="1000"
                                      placeholder="Введите текст"></textarea>
                        </label>
                        <label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><!--10mb-->
                            <p>Фото (макс. 10мб):</p>
                            <input name="file" type="file" accept="image/png, image/jpeg"
                                   required />
                        </label>
                        <p>
                            <input type="submit" value="Добавить">
                        </p>
                    </form>
                </div>
            <?php else: ?>
                <div class="post">
                    <p>Для добавления нового поста дождитесь проверки предыдущих</p>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($_GET['newok'] === "1"): ?>
            <script>
                alert("Заявка опубликована");
            </script>
        <?php elseif ($_GET['newok'] === "0"): ?>
            <script>
                alert("Ошибка");
            </script>
        <?php endif; ?>
    </body>
</html>
