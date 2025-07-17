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
            border: 1px solid #ff9911;
            background-color: transparent;
            color: #000000;
        }
.btn2 {
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
            color: #8B4513;
        }
.hidden{
	display:none;
	}
form {
margin-block-end:0em;
}
</style>
    <title>Мои группы</title>
  </head>
<body>
<header> 
<h2> МОИ ГРУППЫ</h2>
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
if($messages==0){
print('<h3><a href="my_messages.php">!!!Есть непрочитанные сообщения!!!</a></h3>');
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
$per=0;
if(!empty($_POST['submit_yes'])){
$per=1;
$stmt09 = sqlsrv_query( $conn,"UPDATE group_request set answer='yes' where nomer=? AND groups_nomer=?", [$_POST['person_page'],$_POST['group_request_nomer']]);
sqlsrv_fetch_array( $stmt09, SQLSRV_FETCH_ASSOC);
$stmt07 = sqlsrv_query( $conn,"INSERT INTO user_group (nomer, groups_nomer) VALUES (?, ?)", [$_POST['person_page'],$_POST['group_request_nomer']]);
sqlsrv_fetch_array( $stmt07, SQLSRV_FETCH_ASSOC);
}
if(!empty($_POST['submit_no'])){
$per=1;
$stmt08 = sqlsrv_query( $conn,"UPDATE group_request set answer='no' where nomer=? AND groups_nomer=?", [$_POST['person_page'],$_POST['group_request_nomer']]);
sqlsrv_fetch_array( $stmt08, SQLSRV_FETCH_ASSOC);
}
if(!empty($_POST['nomer_group'])){
setcookie('group', $_POST['nomer_group']);
setcookie('nomer', $nomer);
header('Location: group.php');
exit();
}
if($per==0){
if(!empty($_POST['person_page'])){
setcookie ('person_page', $_POST['person_page']);
setcookie('nomer', $nomer);
header('Location:person_page.php');
exit();
}
}
}
$stmt32 = sqlsrv_query( $conn,"SELECT group_name, groups_nomer from groups where groups_nomer IN(SELECT DISTINCT group_nomer from posts where posts_nomer IN  (SELECT posts_nomer from posts where group_nomer IN (SELECT groups_nomer from user_group where nomer=?)) AND NOT posts_nomer IN (SELECT posts_nomer from who_saw_post where nomer=?))", [$nomer,$nomer]);
$posts_est=0;
$groups_nomer = array();
$groups_name = array();
while($row32 = sqlsrv_fetch_array( $stmt32, SQLSRV_FETCH_ASSOC))
{
if(!empty($row32))
{
$posts_est++;
}
    array_push($groups_nomer, strip_tags($row32['groups_nomer']));
    array_push($groups_name, strip_tags($row32['group_name']));
}
$c = array_combine($groups_nomer, $groups_name);
$stmt33 = sqlsrv_query( $conn, "SELECT groups_nomer, group_name from groups where groups_nomer IN (SELECT groups_nomer from user_group where nomer=?)", [$nomer]);
$group_est=0;
$group_u_nomer = array();
$group_u_name = array();
while($row33 = sqlsrv_fetch_array( $stmt33, SQLSRV_FETCH_ASSOC))
{
if(!empty($row33)){
$group_est++;
}
    array_push($group_u_nomer, strip_tags($row33['groups_nomer']));
    array_push($group_u_name, strip_tags($row33['group_name']));
}
$c1 = array_combine($group_u_nomer, $group_u_name);
?>
<br>
<?php
if($posts_est!=0){
print('<h3 id="posts"> Новые посты в группах:</h3>');
$counter1=1;
print('<div class="btn1">');
foreach($groups_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='nomer_group' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $c[$item] . " '/>";
print('</form>');
$counter1++;
}
print('</div>');
}?>
<h3 id="groups">Cписок групп:</h3>
<?php
if($group_est!=0){
$counter2=1;
print('<div class="btn1">');
foreach($group_u_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter2);
print('.');
      echo "<input type='text' class='hidden' name='nomer_group' value='" .$item. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $c1[$item] . " '/>";
print('</form>');

$counter2++;
}
print('</div>');
}
else{
print('Ваш список групп сейчас пуст');
}
$stmt32 = sqlsrv_query( $conn,"SELECT users.nomer, nickname, group_name, groups.groups_nomer from users LEFT JOIN  group_request on group_request.nomer=users.nomer 
LEFT JOIN groups on groups.groups_nomer=group_request.groups_nomer where creator_nomer=? AND answer='wait'", [$nomer]);
$requests = array();
$requests_est=0;
while($row32 = sqlsrv_fetch_array( $stmt32, SQLSRV_FETCH_ASSOC))
{
if(!empty($row32))
{
$requests_est++;
}
$request = array();
$request[0]=$row32['nomer'];
$request[1]=$row32['nickname'];
$request[2]=$row32['group_name'];
$request[3]=$row32['groups_nomer'];
array_push($requests, $request);
}
if($requests_est!=0){
print('<h3 id="own_groups">Заявки на вступление в созданные вами группы:</h3>');


$counter3=1;
print('<div class="btn">');
foreach($requests as $item)
{
echo "<form action='' method='POST'>";
print($counter3);
print('.');
      echo "<input type='text' class='hidden' name='person_page' value='" .$item[0]. "'/> <input type='submit' class='btn1' name='submit_page' id='submit' value=' " . $item[1] . " '/>";
print(' хочет вступить в <b>');
print($item[2]);
$counter3++;
print('</b>'); echo "<input type='text' class='hidden' name='group_request_nomer' value='" .$item[3]. "'/>";
print(' ');
print('<input type="submit" name="submit_yes" class="btn2" id="submit" value="Принять"/>
    <input type="submit" name="submit_no" class="btn2" id="submit" value="Отклонить"/> </form>');
}
print('</div>');
}
?>
<br><br>
<a href="groups.php"  title = "groups">Найти новые группы</a>
<br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>