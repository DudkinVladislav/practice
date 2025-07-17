<?php

$nomer=$_COOKIE['nomer'];
$nomer_write=$_COOKIE['nomer_write'];
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$stmt31 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer = ?", [$nomer_write]);

$row31 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC);
$write_name=$row31['nickname'];
$stmt5 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer =?", [$nomer]);

$row5=sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
$user_nick=$row5['nickname'];

function load() {
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$nomer=$_COOKIE['nomer'];
$nomer_write=$_COOKIE['nomer_write'];
$stmt31 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer = ?", [$nomer_write]);

$row31 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC);
$write_name=$row31['nickname'];
$stmt5 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer =?", [$nomer]);

$row5=sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
$user_nick=$row5['nickname'];
	$echo = "";
$stmt4 = sqlsrv_query( $conn,"SELECT nomer_send, message FROM messages WHERE (nomer_send = ? AND nomer_recv=?) OR (nomer_recv=? AND nomer_send=?) ORDER BY send_time", [$nomer_write,$nomer,$nomer_write,$nomer]);

while($row4 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC))
{
if($row4['nomer_send']==$nomer)
{					
$echo .= "<div class='chat__message chat__message_'><b>";
$echo .= $user_nick;
$echo .= ":</b> ";
$echo .= $row4['message'];
$echo .="</div>";
}
else{
$echo .= "<div class='chat__message chat__message_'><b>";
$echo .= $write_name;
$echo .= ":</b> ";
$echo .= $row4['message'];
$echo .="</div>";
} //Добавляем сообщения в переменную $echo
}		
$stmt11=sqlsrv_query( $conn,"UPDATE messages SET user_read=0 WHERE nomer_send = ? AND nomer_recv=?", [$nomer_write,$nomer]);
sqlsrv_fetch_array( $stmt11, SQLSRV_FETCH_ASSOC);			
	return $echo;//Возвращаем результат работы функции
}
function send($message) {
$nomer=$_COOKIE['nomer'];
$nomer_write=$_COOKIE['nomer_write'];
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
		$data=date("d.m.y H:i:s");
		$message = htmlspecialchars($message);//Заменяем символы ‘<’ и ‘>’на ASCII-код
		$message = trim($message); //Удаляем лишние пробелы
		$message = addslashes($message); //Экранируем запрещенные символы
		$stmt6 = sqlsrv_query( $conn,"INSERT INTO messages (message, nomer_send, nomer_recv, user_read, send_time) VALUES (?, ?, ?, 1, ?)", [$message,$nomer,$nomer_write,$data]);
sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_ASSOC);		
//Заносим сообщение в базу данных
	return load(); //Вызываем функцию загрузки сообщений
}
if(isset($_POST['act'])) {$act = $_POST['act'];}
if(isset($_POST['var1'])) {$var1 = $_POST['var1'];}
if(isset($_POST['var2'])) {$var2 = $_POST['var2'];}

switch($_POST['act']) {//В зависимости от значения act вызываем разные функции
	case 'load': 
		$echo = load(); //Загружаем сообщения
	break;
	
	case 'send': 
		if(isset($var1)) {
			$echo = send($var1); //Отправляем сообщение
		}
	break;
	
	}
echo $echo;//Выводим результат работы кода
?>