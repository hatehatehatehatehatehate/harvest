<?php
require_once 'share.php';

$old_value = $_GET['value'];
$result = $old_value;

while (time() - $_SERVER['REQUEST_TIME'] <= 25 && !connection_aborted()) {
    $result = dbQuery("SELECT count(*) as 'count' FROM posts p where is_deleted is false and is_fixed = 1")["count"];
    if ($result !== $old_value) {
        break;
    }
    sleep(1);
}

echo $result;



