<?php

$dbh = new PDO('mysql:host=127.0.0.1;dbname=do_world_better', "root", "1234");
global $user;

if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_COOKIE && !IsNullOrEmptyString($_COOKIE["uid"]) && !IsNullOrEmptyString($_COOKIE["pass"])) {
        $user = dbQuery("SELECT u.* from `users` u where id=? and password=md5(?) limit 1", [$_COOKIE["uid"], $_COOKIE["pass"]]);
    }
}

function dbQuery($query, $params = [])
{
    global $dbh;
    $qry = $dbh->prepare($query);
    $qry->execute($params);
    return $qry->fetch(PDO::FETCH_ASSOC);
}

function dbQueryAll($query, $params = [])
{
    global $dbh;
    $qry = $dbh->prepare($query);
    $qry->execute($params);
    return $qry->fetchAll(PDO::FETCH_ASSOC);
}

function lastInsertId()
{
    global $dbh;
    return +$dbh->lastInsertId();
}

function errorInfo()
{
    global $dbh;
    return $dbh->errorInfo();
}

function IsNullOrEmptyString($str)
{
    return (!isset($str) || trim($str) === '');
}

function redirect($url, $params = null)
{
    if ($params === null) {
        $prms = "";
    } else {
        $array = [];
        foreach ($params as $key => $value) {
            array_push($array, urlencode($key) . "=" . urlencode($value));
        }
        $prms = "?" . join("&", $array);
    }

    /* Перенаправление браузера на другую страницу в той же директории, что и
    изначально запрошенная */
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: http://$host$uri/$url$prms");
    exit;
}

?>
