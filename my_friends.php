<html lang="ru">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<style>body { margin:0;
	display:flex;
	flex-direction:column;
text-align:center;
background-image: url("kartinki-svetlii-fon.jpg");}
header {display:flex;
flex-direction: column;
text-align: center;
}
	  /* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
	border: 2px solid red;
	}
.btn {
            cursor: pointer;
            /*border: 1px solid #ff9911;*/
            background-color: transparent;
            color: #000000;
        }
.btn1 {
	    font-size: 105%;
	    border: 1px solid #ff9911;
            cursor: pointer;
            background-color: transparent;
            color: #0000FF;
        }
.hidden{
	display:none;
	}
form {
margin-block-end:0em;
}
</style>
    <title>Мои друзья</title>
    <?php
    setcookie('person_page', '', 100000);
$nomer=$_COOKIE['nomer'];
    $serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
    $stmt32 = sqlsrv_query( $conn, "SELECT request_nomer FROM friends_request WHERE nomer_recv = ? AND  see='no'", [$nomer]);
$row = sqlsrv_fetch_array($stmt32, SQLSRV_FETCH_ASSOC);
if(!$row){
$requests=1;
}
else{
$requests=0;
}
$stmt33 = sqlsrv_query( $conn,"SELECT nomer_send FROM messages WHERE nomer_recv = ? AND  user_read=1", [$nomer]);

$row33 = sqlsrv_fetch_array($stmt33, SQLSRV_FETCH_ASSOC);
if(!$row33){
$messages=1;
}
else{
$messages=0;
}
$stmt34 = sqlsrv_query( $conn, "SELECT DISTINCT posts_nomer from posts where posts_nomer IN  (SELECT posts_nomer from posts where group_nomer IN (SELECT groups_nomer from user_group where nomer=?)) AND NOT posts_nomer IN (SELECT posts_nomer from who_saw_post where nomer=?)", [$nomer, $nomer]);

$row34 = sqlsrv_fetch_array($stmt34, SQLSRV_FETCH_ASSOC);
if(!$row34){
$posts=1;
}
else{
$posts=0;
}
$stmt35 = sqlsrv_query( $conn, "SELECT users.nomer,nickname, groups_nomer from users, group_request where groups_nomer IN ( SELECT groups_nomer from groups  where creator_nomer=?) AND users.nomer=group_request.nomer AND answer='wait'", [$nomer]);
$row35 = sqlsrv_fetch_array($stmt35, SQLSRV_FETCH_ASSOC);
if(!$row35){
$grequest=1;
}
else{
$grequest=0;
}
    ?>
  </head>
<body>
<header> 
<h2> МОИ ДРУЗЬЯ</h2>
</header>
<?php

if($messages==0){
print('<h3><a href="my_messages.php">!!!Есть непрочитанные сообщения!!!</a></h3>');
}
if($posts==0){
print('<h3><a href="my_groups.php">!!!Новые посты в группах!!!</a></h3>');
}
if($grequest==0){
print('<h3><a href="my_groups.php">!!!Новые заявки на вступление в ваши группы!!!</a></h3>');
}
$stmt111 = sqlsrv_query( $conn, "SELECT habit_nomer from share_habits where viewer_nomer=? AND seen='no'", [$nomer]);
$row111 = sqlsrv_fetch_array($stmt111, SQLSRV_FETCH_ASSOC);
if(!$row111){
$share_habit=1;
}
else{
$share_habit=0;
}
if($share_habit==0){
print('<h3><a href="my_shared_habits.php">!!!Друзья поделились привычками!!!</a></h3>');
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

if(!empty($_POST['friend_page'])){
setcookie('person_page', $_POST['friend_page']);
setcookie('nomer', $nomer);
header('Location:person_page.php');
exit();
}
if((!empty($_POST['friend_dobav'])) AND (!empty($_POST['submit_yes'])))
{
$new_request_yes=$_POST['friend_dobav'];
$date=date("d.m.y H:i:s");
$stmt322 = sqlsrv_query( $conn,"SELECT nomer_send from friends_request where request_nomer = ?", [$new_request_yes]);
$row322 = sqlsrv_fetch_array( $stmt322, SQLSRV_FETCH_ASSOC);
$stmt222 = sqlsrv_query( $conn,"INSERT INTO friends (request_nomer, become_time, nomer_one, nomer_two) VALUES (?, ?, ?, ?)", [$new_request_yes,$date,$row322['nomer_send'],$nomer]);
sqlsrv_fetch_array( $stmt222, SQLSRV_FETCH_ASSOC);
$stmt123 = sqlsrv_query( $conn,"UPDATE friends_request SET see = ?, answer=? WHERE request_nomer =?", ['yes','yes',$new_request_yes]);
sqlsrv_fetch_array( $stmt123, SQLSRV_FETCH_ASSOC);
}
else{
if((!empty($_POST['friend_dobav'])) AND (!empty($_POST['submit_no'])))
{
$new_request_no=$_POST['friend_dobav'];
$stmt123 = sqlsrv_query( $conn, "UPDATE friends_request SET see = ?, answer=? WHERE request_nomer =?", ['yes','no',$new_request_no]);
sqlsrv_fetch_array( $stmt123, SQLSRV_FETCH_ASSOC);
}
else {
if(!empty($_POST['friend_write'])){
$friend_write=$_POST['friend_write'];
setcookie('nomer_write', $friend_write);
setcookie('nomer', $nomer);
header('Location:conversation.php');
exit();
}
else{
if(!empty($_POST['friend_delete']))
{
$friend_delete=$_POST['friend_delete'];
$stmt111 = sqlsrv_query( $conn,"DELETE FROM friends_request where (nomer_send=? AND nomer_recv=?) OR (nomer_recv=? AND nomer_send=?)", [$nomer, $friend_delete, $nomer, $friend_delete]);
sqlsrv_fetch_array( $stmt111, SQLSRV_FETCH_ASSOC);
}
}
}
}
}
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);
$stmt32 = sqlsrv_query( $conn, "SELECT nomer, nickname, request_nomer from users LEFT JOIN friends_request on friends_request.nomer_send=users.nomer where friends_request.nomer_recv = ? AND  friends_request.see='no'", [$nomer]);
$request_nomer = array();
$friend_nick = array();
$friend_nomer=array();
$request_est=0;
while($row32 = sqlsrv_fetch_array( $stmt32, SQLSRV_FETCH_ASSOC))
{
if(!empty($row32)){
$request_est++;
}
    array_push($friend_nomer, strip_tags($row32['nomer']));
    array_push($friend_nick, strip_tags($row32['nickname']));
    array_push($request_nomer, strip_tags($row32['request_nomer']));
}
$c = array_combine($friend_nomer, $friend_nick);
$d=array_combine($request_nomer, $friend_nick);
$stmt33 = sqlsrv_query( $conn,"SELECT nomer, nickname from users where nomer IN (SELECT nomer_one from friends where nomer_two=? UNION SELECT nomer_two from friends where nomer_one=?)", [$nomer, $nomer]);
$friend_w_nomer = array();
$friend_w_nick = array();
$friends_est=0;
while($row33 = sqlsrv_fetch_array( $stmt33, SQLSRV_FETCH_ASSOC))
{
if(!empty($row33)){
$friends_est++;
}
    array_push($friend_w_nomer, strip_tags($row33['nomer']));
    array_push($friend_w_nick, strip_tags($row33['nickname']));
}
$c1 = array_combine($friend_w_nomer, $friend_w_nick);
print('<br>');
if($request_est!=0){
print('<h3> Запросы в друзья от:</h3>');
$counter1=1;
print('<div class="btn1">');
foreach($friend_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='friend_page' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $c[$item] . " '/>";
print('</form>');
$counter1++;
}
print('</div>');


print('<br>
<form action="" method="POST">');

echo "<select name='friend_dobav'><option disabled>Выберите имя</option>";
    foreach($request_nomer as $item) {
   echo "<option value ='" . $item . "'>" . $d[$item] . "</option>";
    }
   echo"</select>";
    print('<input type="submit" name="submit_yes" class="btn" id="submit" value="Принять" />
    <input type="submit" name="submit_no" class="btn" id="submit" value="Отклонить" />
</form>');
}
if($friends_est!=0){
print('<h3>Открыть диалог с другом:</h3>
<form action="" method="POST">');

echo "<select name='friend_write'><option disabled>Выберите имя</option>";
    foreach($friend_w_nomer as $item) {
   echo "<option value ='" . $item . "'>" . $c1[$item] . "</option>";
    }
   echo"</select>";

    print('<input type="submit" class="btn" name="submit" id="submit" value="Открыть" />
</form>');
}
?>
<h3>Cписок друзей:</h3>
<?php
if($friends_est!=0){
$counter2=1;
print('<div class="btn1">');
foreach($friend_w_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter2);
print('.');
      echo "<input type='text' class='hidden' name='friend_page' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $c1[$item] . " '/>";
print('</form>');

$counter2++;
}
print('</div>');
}
else {
print('Ваш список друзей сейчас пуст.');
}
if($friends_est!=0){
print('<h3>Удалить из друзей:</h3>
<form action="" method="POST">');
echo "<select name='friend_delete'><option disabled>Выберите имя</option>";
    foreach($friend_w_nomer as $item) {
   echo "<option value ='" . $item . "'>" . $c1[$item] . "</option>";
    }
   echo"</select>";
   print ('<input type="submit" class="btn" name="submit" id="submit" value="Удалить" />
</form>');
}
?>
<br><br>
<a href="people.php">Найти новых друзей</a>
<br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>