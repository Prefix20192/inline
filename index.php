
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Inline</title>
</head>
<body>
<?php
require_once __DIR__ . '/script.php';
$pdo = db_connect('127.0.0.1', 'inline', 'root', '');
if(isset($_GET['query'])) {
	$query = $_GET['query'];
	if(strlen($query) >= 3){
		$sth = $pdo->query("SELECT `posts`.`title`, `posts`.`body` FROM `posts` LEFT JOIN `comments` ON `posts`.`id` = `comments`.`postId` WHERE `comments`.`body` LIKE '%{$query}%'");
		$arr = $sth->fetchAll(PDO::FETCH_ASSOC);
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/pdo_error.txt", print_r($sth, true), FILE_APPEND);
		}
		else {
		echo "Min search length is 3 characters | Минимальная длинна запроса 3 символа"; 
		}

		if (isset($arr)){
			if (count($arr) > 0){
				echo "<h2>Search Results | Результаты Поиска</h2>";
				echo "<table style=\"width:80%\"><tr><th>Post Title | Запись</th><th>Comment | Комментарий</th></tr>";
				foreach ($arr as $item) {
					echo("<tr><td>{$item['title']}</td><td>{$item['body']}</td></tr>"); }
		} 	
		else {
			echo "No results found | Результаты не найдены";
		}

	echo "</table>";
}
}
?>
    
</body>
</html>