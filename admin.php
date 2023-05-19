<?php
require_once 'share.php';

if (empty($user) || $user["is_admin"] === "0") {
    redirect("index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ($_FILES["file"] !== null && $_FILES["file"]["size"] > 0) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], "userimg/" . $_POST['request_id'] . "-after.png")) {
            dbQuery('UPDATE posts set is_fixed=1 where id=?', [$_POST['request_id']]);
        }
        redirect("admin.php", ["modok" => "1"]);
    } else if ($_POST["reject-reason"] !== null) {
        dbQuery('UPDATE posts set is_fixed=0 where id=?', [$_POST['request_id']]);
        redirect("admin.php", ["modok" => "1"]);
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
        <h1 style="color: #f44336">Модераторская</h1>
        <div class="cards">
            <a href="categories.php">🔁 Редактировать категории</a>
            <?php foreach (dbQueryAll('SELECT p.*, u.login as login, u.first_name as fn, u.last_name as ln, c.title as category_title, c.id as category_id from `posts` p inner join users u on p.author = u.id inner join categories c on c.id = p.category_id where p.is_fixed is null and p.is_deleted=false limit 500') as $row): ?>
                <div class="card request">
                    <h2><?= $row['title']; ?></h2>
                    <p class="request-info">
                        <span class="mod-status">⭐ Новая</span>
                        •
                        <?= $row['create_time']; ?>
                        •
                        <?= $row['category_title']; ?>
                    </p>
                    <p><?= str_replace("\n", "<br>", $row['text']); ?></p>
                    <details>
                        <summary>
                            Решить
                        </summary>
                        <form enctype="multipart/form-data" action="?fixed=1" method="post">
                            <input type="number" value="<?= $row["id"] ?>" name="request_id" hidden>
                            <p>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /><!--2mb-->
                                Фото (макс. 2мб):
                                <input name="file" type="file" accept="image/png, image/jpeg"
                                       required />
                            </p>
                            <p>
                                <input type="submit" value="Решить" />
                            </p>
                        </form>
                    </details>
                    <details>
                        <summary>
                            Отклонить
                        </summary>
                        <form action="?fixed=0" method="post">
                            <input type="number" value="<?= $row["id"] ?>" name="request_id" hidden>
                            <p>
                                Причина отказа:
                                <textarea name="reject-reason" required minlength="3" maxlength="1000"
                                          placeholder="Причина отказа"></textarea>
                                <input type="submit" class="button-negative" value="Отклонить" />
                            </p>
                        </form>
                    </details>
                    <details>
                        <summary>Комментарии</summary>
                        <?php foreach (dbQueryAll('SELECT c.*, u.login as login, u.first_name as fn, u.last_name as ln from `comments` c inner join users u on c.user_id=u.id where c.post_id = ? limit 500', [$row["id"]]) as $comment): ?>
                            <p>
                                <?php echo "<strong>" . $comment["login"] . " [" . $comment["create_date"] . "]</strong>: " . $comment["text"] ?>
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
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>
