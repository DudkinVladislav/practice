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
            color: #8B008B;
        }
.hidden{
	display:none;
	}
form {
margin-block-end:0em;
}
</style>
    <title>Люди</title>
  </head>
<body>
<header> 
<h2>Люди</h2>
</header>
<?php
$search=0;
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
$people_nomer=array();
$people_nick=array();
$stmt987 = sqlsrv_query( $conn,"SELECT nomer, nickname FROM users where NOT nomer=?", [$nomer]);

while($row987 = sqlsrv_fetch_array( $stmt987, SQLSRV_FETCH_ASSOC))
{
    array_push($people_nomer, strip_tags($row987['nomer']));
    array_push($people_nick, strip_tags($row987['nickname']));
}
$c = array_combine($people_nomer, $people_nick);
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
if(!empty($_POST['person_page'])){
setcookie('person_page', $_POST['person_page']);
setcookie('nomer', $nomer);
header('Location:person_page.php');
exit();
}
if(!empty($_POST['person_search'])){
$person_search=$_POST['person_search'];
$stmt123 = sqlsrv_query( $conn,"SELECT nomer, nickname FROM users WHERE nickname=? AND NOT nomer=?", [$person_search, $nomer]);

$people_nomer1=array();
$people_nick1=array();
while($row123 = sqlsrv_fetch_array( $stmt123, SQLSRV_FETCH_ASSOC))
{
    array_push($people_nomer1, strip_tags($row123['nomer']));
    array_push($people_nick1, strip_tags($row123['nickname']));
}
$c1 = array_combine($people_nomer1, $people_nick1);
$search=2;
if(!empty($c1)){
$search=1;
}
}
}
?>
<br>
<h3> Поиск пользователей по имени:</h3>
<form action='' method='POST'>
<input type='text' name='person_search' value=""/>
<input type='submit' class='btn' name='submit' id='submit' value= "Найти" />
</form>
<br>
<?php
if(!empty($search))
{
if($search==1)
{
echo "<h3> Найденные пользователи:</h3>";
$counter1=1;
print('<div class="btn1">');
foreach($people_nomer1 as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='person_page' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_person' id='submit' value=' " . $c1[$item] . " '/>";
print('</form>');
$counter1++;
}
print('</div>');
print('<br><a href="people.php">Назад</a>');
}
if($search==2)
{
print('<br>Пользователей с таким именем не найдено.<br>');
print('<br><a href="people.php">Назад</a>');
}
}
else
{
echo "<h3> Пользователи нашей социальной сети:</h3>";
$counter1=1;
print('<div class="btn1">');
foreach($people_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='person_page' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_group' id='submit' value=' " . $c[$item] . " '/>";
print('</form>');
$counter1++;
}
print('</div>');
}
?>
<br>
<br><br>
<a href="my_friends.php">Мои друзья</a><br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>