<?php require_once 'share.php'; ?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Интернет-портал</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' href='css/style.css'>
    </head>
    <body>
        <a href="index.php">Назад</a>
        <?php if (!empty($user) && $user["is_admin"] === "1"): ?>
            <h1>Пользователи</h1>
            <div class="posts">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['set_is_admin'])) {
                        dbQuery('UPDATE users set is_admin=? where id=?', [+($_POST['set_is_admin'] === "1"), $_POST['id']]);
                    }
                    if (isset($_POST['set_is_ban'])) {
                        dbQuery('UPDATE users set is_ban=? where id=?', [+($_POST['set_is_ban'] === "1"), $_POST['id']]);
                    }
                }
                ?>
                <div class="post">
                    <table>
                        <tr>
                            <th>id</th>
                            <th>name</th>
                            <th>login</th>
                            <th>is_admin</th>
                            <th>is_ban</th>
                        </tr>
                        <?php foreach (dbQueryAll('SELECT u.* from `users` u limit 500') as $row): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                                <td><?= $row['login']; ?></td>
                                <form action="" method="post">
                                    <input type="number" value="<?= $row["id"] ?>" name="id" hidden>
                                    <td>
                                        <?php if ($row['is_admin'] === "1"): ?>
                                            <button type="submit" name="set_is_admin" value="0">Да</button>
                                        <?php else: ?>
                                            <button type="submit" name="set_is_admin" value="1">Нет</button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['is_ban'] === "1"): ?>
                                            <button type="submit" name="set_is_ban" value="0">Да</button>
                                        <?php else: ?>
                                            <button type="submit" name="set_is_ban" value="1">Нет</button>
                                        <?php endif; ?>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>
