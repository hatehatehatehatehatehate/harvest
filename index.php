<?php require_once 'share.php'; ?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Интернет-портал</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='css/style.css'>
        <link rel='stylesheet' href='css/index.css'>
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
                    <a href="profile.php"
                       class="header-right"><?= ($user["is_admin"] ? "👑" : "") . ($user["is_ban"] ? "🙊" : "") . $user["login"] ?></a>
                <?php endif ?>
            </div>
        </div>
        <div class="cards">
            <div class="card card-counter">
                <h2>Количество решенных заявок</h2>
                <a id="fixed-count">...</a>
            </div>
            <?php foreach (dbQueryAll('SELECT p.*, u.login as login, u.first_name as fn, u.last_name as ln, c.title as category_title from `posts` p inner join users u on p.author = u.id inner join categories c on c.id = p.category_id where p.is_fixed = true and p.is_deleted=false order by rand() limit 4') as $row) : ?>
                <div class="card request resolve">
                    <h2><?= $row['title'] ?></h2>
                    <p class="request-info">
                        <span class="mod-status">✔ Решена</span>
                        •
                        <?= $row['create_time']; ?>
                        •
                        <?= $row['category_title']; ?>
                    </p>
                    <div class="img">
                        <div class="img-before"
                             style="background-image: url('userimg/<?= $row['id'] ?>-before.png')"></div>
                        <div class="img-after"
                             style="background-image: url('userimg/<?= $row['id'] ?>-after.png')"></div>
                    </div>
                    <details>
                        <summary>
                            Комментарии
                        </summary>
                        <?php foreach (dbQueryAll('SELECT c.*, u.login as login, u.first_name as fn, u.last_name as ln from `comments` c inner join users u on c.user_id=u.id where c.post_id = ? limit 500', [$row["id"]]) as $comment): ?>
                            <p>
                                <?php echo "<strong>" . $comment["login"] . "</strong>: " . $comment["text"] ?>
                            </p>
                        <?php endforeach; ?>
                        <div class="new-comment">
                            <?php if (empty($user)): ?>
                                <span>Комментарии могут писать только авторизированные пользователи</span>
                            <?php elseif ($user["is_ban"] === "1"): ?>
                                <span>Вы не можете писать комментарии</span>
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
                </div>
            <?php endforeach ?>
        </div>
        <script src="js/index.js"></script>
    </body>
</html>
