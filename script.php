<?php
function db_connect($db_host, $db_db, $db_user, $db_pass)
{
    try {
        $pdo = new PDO(
            "mysql:host=" . $db_host . ";dbname=" . $db_db,
            $db_user,
            htmlspecialchars_decode($db_pass, ENT_QUOTES)
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_TIMEOUT, 5);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
    } catch (PDOException $e) {
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/pdo_error.txt","[" . date("Y-m-d H:i:s") . " | " . $db_host . " | " . $db_db . "] : [" . $e->getMessage() . "]\r\n", FILE_APPEND);
        return false;
    }

    return $pdo;
}

function getJson($url){
    $url = file_get_contents($url);
    return json_decode($url, true);
}

function setDBPosts($json){
    $pdo = db_connect('127.0.0.1', 'inline', 'root', '');
    $count_posts = 0;
    $STH = $pdo->query("SELECT * FROM `posts`");
    if(!$STH->rowCount()){
        foreach ($json as $item) {
            $STH = $pdo->prepare("INSERT INTO `posts` (`title`, `body`, `userId`) VALUES(?,?,?)");
            $STH->execute([$item['title'], $item['body'], $item['userId']]);
            $count_posts++;
        }
    }

    $pdo = null;
    $STH= null;
    return $count_posts;
}
function setDBComments($json){
    $pdo = db_connect('127.0.0.1', 'inline', 'root', '');
    $count_comments = 0;
    $STH = $pdo->query("SELECT * FROM `comments`");
    if(!$STH->rowCount()){
        foreach ($json as $item) {
            $STH = $pdo->prepare("INSERT INTO `comments`(`name`, `email`, `body`, `postId`) VALUES(?,?,?,?)");
            $STH->execute([$item['name'], $item['email'], $item['body'], $item['postId']]);
            $count_comments++;
        }
    }
    $pdo = null;
    $STH= null;
    return $count_comments;
}

$count_posts = setDBPosts(getJson('https://jsonplaceholder.typicode.com/posts'));
$count_comments = setDBComments(getJson('https://jsonplaceholder.typicode.com/comments'));
if(!isset($_GET['query'])){
    echo $count_comments == 0 && $count_posts == 0 ? "Вы уже загрузили данные в таблицы!" : "Загружено {$count_posts} записей и {$count_comments} комментариев";
}

//Запустить можно с консоли php .\script.php