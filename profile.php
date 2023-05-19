<?php
require_once 'share.php';

if (empty($user)) {
    redirect("index.php");
    exit;
}
if ($_GET["del"] !== null && is_numeric($_GET["del"])) {
    $request = dbQuery('SELECT p.title as title from `posts` p where (p.id = ?) and (p.author = ?) and (p.is_fixed is null) and (p.is_deleted = false) limit 1', [$_GET["del"], $user["id"]]);
    if ($request !== null && $request["title"] !== null) {
        dbQuery('update posts p set p.is_deleted=true where p.id=?', [$_GET["del"]]);
        redirect("profile.php", array("title" => $request["title"], "delok" => "1"));
    } else {
        redirect("profile.php", array("delok" => "0"));
    }
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
        <link rel='stylesheet' href='css/profile.css'>
        <script src="js/profile.js"></script>
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
                <?php if ($user["is_admin"]) : ?>
                    <a href="admin.php">Модерировать</a>
                <?php endif ?>
                <a href="profile.php"
                   class="header-right"><?= ($user["is_admin"] ? "👑" : "") . ($user["is_ban"] ? "🙊" : "") . $user["login"] ?></a>
            </div>
        </div>

        <?php foreach (dbQueryAll('SELECT c.* from categories c limit 50') as $row) : ?>
            <input type="checkbox" class="filter-checkbox" id="filter-<?= $row["id"] ?>" checked>
        <?php endforeach; ?>
        <style>
            <?php foreach (dbQueryAll('SELECT c.* from categories c limit 50') as $row) : ?>
            #filter-<?= $row["id"] ?>:not(:checked) ~ .cards .category-<?= $row["id"] ?> {
                display: none;
            }

            #filter-<?= $row["id"] ?>:checked ~ .cards #label-<?= $row["id"] ?> {
                background-color: #1890ff;
                border-radius: 5px;
                color: snow;
            }

            <?php endforeach; ?>
        </style>
        <div class="cards">
            <div class="card">
                <h2>Заявки пользователя <?= $user["login"] ?></h2>
                <?php if ($user["is_ban"] === "0"): ?>
                    <a href="create-post.php">Добавить пост</a>
                <?php endif; ?>
                <a href="auth.php">Выйти</a>
                <div>
                    <?php foreach (dbQueryAll('SELECT c.* from categories c limit 50') as $row) : ?>
                        <label id="label-<?= $row["id"] ?>" class="filter-label" for="filter-<?= $row["id"] ?>">
                            <?= $row["emoji"] ?> <?= $row["title"] ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="requests">
                <?php foreach (dbQueryAll('SELECT p.*, u.login as login, u.first_name as fn, u.last_name as ln, c.title as category_title, c.id as category_id from `posts` p inner join users u on p.author = u.id inner join categories c on c.id = p.category_id where p.author = ? and p.is_deleted=false order by p.create_time desc limit 500', [$user['id']]) as $row) : ?>
                    <?php
                    $classes = ["card", "request", "category-" . $row["category_id"]];
                    $modStatus = null;
                    switch ($row["is_fixed"]) {
                        case "1":
                            $modStatus = "✔ Решена";
                            array_push($classes, "resolve");
                            break;
                        case "0":
                            $modStatus = "❌ Отклонена";
                            array_push($classes, "reject");
                            break;
                        default:
                            $modStatus = "⭐ Новая";
                            array_push($classes, "new");
                            break;
                    }
                    ?>
                    <div class="<?= join(" ", $classes) ?>">
                        <h2><?= $row['title']; ?></h2>
                        <p class="request-info">
                            <span class="mod-status"><?= $modStatus ?></span>
                            •
                            <?= $row['create_time']; ?>
                            •
                            <?= $row['category_title']; ?>
                        </p>
                        <p>
                            <?= str_replace("\n", "<br>", $row['text']); ?>
                        </p>
                        <details>
                            <summary>
                                Комментарии
                            </summary>
                            <?php foreach (dbQueryAll('SELECT c.*, u.login as login, u.first_name as fn, u.last_name as ln from `comments` c inner join users u on c.user_id=u.id where c.post_id = ? limit 500', [$row["id"]]) as $comment): ?>
                                <p>
                                    <?php echo "<strong>" . $comment["login"] . " " . $comment["create_date"] . "</strong>: " . $comment["text"] ?>
                                </p>
                            <?php endforeach; ?>
                            <div class="new-comment">
                                <?php if (empty($user)): ?>
                                    <p>Комментарии могут писать только авторизированные пользователи</p>
                                <?php elseif ($user["is_ban"] === "1"): ?>
                                    <p>Вы не можете писать комментарии</p>
                                <?php else: ?>
                                    <form class="add-comment" action="post.php" method="post">
                                        <input type="number" name="id" value="<?= $row["id"] ?>" hidden>
                                        <input type="text" name="act" value="create-comment" hidden>
                                        <textarea name="text" required minlength="3" maxlength="1000"
                                                  placeholder="Комментарий">
                                </textarea>
                                        <input type="submit" value="Отправить">
                                    </form>
                                <?php endif; ?>
                            </div>
                        </details>
                        <?php if ($row["is_fixed"] === null): ?>
                            <form action="">
                                <input type="number" name="del" hidden value="<?=$row["id"]?>">
                                <input type="submit" class="button-negative" value="Удалить заявку" />
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($_GET["delok"] === "1"): ?>
            <script>alert("Заявка \"<?=$_GET["title"]?>\" удалена");</script>
        <?php elseif ($_GET["delok"] === "0"): ?>
            <script>alert("Не удалось удалить заявку");</script>
        <?php endif; ?>
    </body>
</html>
