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
            border: 1px solid #ff9911;
            background-color: transparent;
            color: #000000;
        }
.btn1 {
	    font-size: 105%;
	    border: 1px solid #ff9911;
            cursor: pointer;
            background-color: transparent;
            color: #006400;
        }
.hidden{
	display:none;
	}
form {
margin-block-end:0em;
}
</style>
    <title>Мои переписки</title>
  </head>
<body>
<header> 
<h2> МОИ ПЕРЕПИСКИ</h2>
</header>
<?php
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
setcookie('person_page', '', 100000);
$nomer=$_COOKIE['nomer'];
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
if($requests==0){
print('<h3><a href="my_friends.php">!!!Есть новые заявки в друзья!!!</a></h3>');
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

if(!empty($_POST['friend_talk']))
{
setcookie('nomer_write', $_POST['friend_talk']);
setcookie('nomer', $nomer);
header('Location:conversation.php');
exit();
}
else 
{
if(!empty($_POST['friend_write']))
{
$friend_write=$_POST['friend_write'];
setcookie('nomer_write', $friend_write);
setcookie('nomer', $nomer);
header('Location:conversation.php');
exit();
}
}
}
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$stmt32 = sqlsrv_query( $conn,"SELECT DISTINCT nomer, nickname from users where nomer IN (SELECT nomer_send from messages where nomer_recv=? AND user_read=1)", [$nomer]);
$new_est=0;
$write_nomer = array();
$write_nick = array();
while($row32 = sqlsrv_fetch_array( $stmt32, SQLSRV_FETCH_ASSOC))
{
if(!empty($row32)){
$new_est++;
}
    array_push($write_nomer, strip_tags($row32['nomer']));
    array_push($write_nick, strip_tags($row32['nickname']));
}
$c = array_combine($write_nomer, $write_nick);

$stmt33 = sqlsrv_query( $conn,"SELECT nomer, nickname from users where nomer IN (SELECT nomer_one from friends where nomer_two=? UNION SELECT nomer_two from friends where nomer_one=?)", [$nomer, $nomer]);

$friends_est=0;
$friend_w_nomer = array();
$friend_w_nick = array();
while($row33 = sqlsrv_fetch_array( $stmt33, SQLSRV_FETCH_ASSOC))
{
if(!empty($row33)){
$friends_est++;
}
    array_push($friend_w_nomer, strip_tags($row33['nomer']));
    array_push($friend_w_nick, strip_tags($row33['nickname']));
}
$c1 = array_combine($friend_w_nomer, $friend_w_nick);

$stmt35 = sqlsrv_query( $conn,"SELECT nomer, nickname from users where NOT nomer IN(SELECT nomer_send from messages where nomer_recv=? AND user_read=1) AND nomer IN (SELECT nomer_recv from messages where nomer_send=? UNION SELECT nomer_send from messages where nomer_recv=?)", [$nomer, $nomer, $nomer]);

$activ_est=0;
$friend_nomer = array();
$friend_nick = array();
while($row35 = sqlsrv_fetch_array( $stmt35, SQLSRV_FETCH_ASSOC))
{
if(!empty($row35)){
$activ_est++;
}
    array_push($friend_nomer, strip_tags($row35['nomer']));
    array_push($friend_nick, strip_tags($row35['nickname']));
}
$d = array_combine($friend_nomer, $friend_nick);

?>
<br>
<?php
$counter1=1;
if($new_est!=0){
print('<h3> Новые сообщения от:</h3>');
print('<div class="btn1">');
foreach($write_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='friend_talk' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $c[$item] . " '/>";
print('</form>');
$counter1++;
}
print('</div><br>');
}
if($friends_est!=0){
print('<h3>Написать друзьям:</h3>
<form action="" method="POST">');
echo "<select name='friend_write'><option disabled>Выберите имя</option>";
    foreach($friend_w_nomer as $item) {
   echo "<option value ='" . $item . "'>" . $c1[$item] . "</option>";
    }
   echo"</select>";

    print('<input type="submit" class="btn" name="submit" id="submit" value="Написать" />
</form>');
}
?>
<h3>Активные переписки:</h3>
<?php
if($activ_est!=0){
print('<div class="btn1">');
foreach($friend_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='friend_talk' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $d[$item] . " '/>";
print('</form>');

$counter1++;
}
print('</div>');
}
else{
print('У вас сейчас нет активных переписок.');
}
?>
<br>
<br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>