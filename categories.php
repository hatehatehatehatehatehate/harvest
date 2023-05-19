<?php
require_once 'share.php';

if (empty($user) || $user["is_admin"] === "0") {
    redirect("index.php");
    exit;
}

if ($_GET["del"] !== null) {
    dbQuery('delete from categories c where c.id = ?', [$_GET["del"]]);
    redirect("categories.php", ["delok" => "1"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ($_POST["emoji"] !== null && $_POST["title"] !== null) {
        dbQuery('insert into categories(emoji, title) value (?, ?)', [$_POST["emoji"], $_POST["title"]]);
    }
    redirect("categories.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Интернет-портал</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='css/style.css'>
        <link rel='stylesheet' href='css/categories.css'>
        <script src="js/categories.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="header-wrap">
                <a href="index.php" class="logo">
                    <img src="sos.png" alt="logo" width="50px" height="50px">
                </a>

                <a href="profile.php">Мои заявки</a>
                <?php if ($user["is_ban"] === "0"): ?>
                    <a href="create-post.php">Создать заявку</a>
                <?php endif ?>
                <a href="admin.php">Модерировать</a>
                <a href="profile.php" class="header-right">👑<?= $user["is_ban"] ? "🙊" : "" ?><?= $user["login"] ?></a>
            </div>
        </div>
        <h1>Категории</h1>
        <div class="cards">
            <a href="admin.php">🔁 Модерировать</a>
            <div class="card request">
                <table>
                    <tr>
                        <th>id</th>
                        <th>emoji</th>
                        <th>title</th>
                        <th>del</th>
                    </tr>
                    <?php foreach (dbQueryAll('SELECT c.* from categories c limit 500') as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td class="icon"><?= $row['emoji'] ?></td>
                            <td id="categiry-title-<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></td>
                            <td class="del" onclick="deleteCategory(<?= $row['id'] ?>)">❌</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <form method="post" action="">
                            <td>+</td>
                            <td class="icon">
                                <input type="text" name="emoji" minlength="1" min="1" maxlength="10" max="10">
                            </td>
                            <td>
                                <input type="text" name="title" minlength="3" min="3" maxlength="10" max="10">
                            </td>
                            <td>
                                <input type="submit" name="new" value="Создать">
                            </td>
                        </form>
                    </tr>
                </table>
            </div>
        </div>
        <?php if ($_GET["delok"] === "1"): ?>
            <script>alert("Категория удалена");</script>
        <?php elseif ($_GET["delok"] === "0"): ?>
            <script>alert("Не удалось удалить категорию");</script>
        <?php endif; ?>
    </body>
</html>
