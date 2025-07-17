<html lang="ru">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<style>
body { 
margin:0;
display:flex;
flex-direction:column;
text-align:center;
  background-image: url("kartinki-svetlii-fon.jpg");

}
header {
display:flex;
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
	    /*border: 1px solid #ff9911;*/
            cursor: pointer;
            background-color: transparent;
            color: #FF00FF;
        }
.btn2 {
	    font-size: 105%;
	    border: 1px solid #ff9911;
            cursor: pointer;
            background-color: transparent;
            color: #008000;
        }
.hidden{
	display:none;
	}
form {
margin-block-end:0em;
}
</style>
<?php
$nomer=$_COOKIE['nomer'];
$values = array();
$values['groups_nomer']=$_COOKIE['group'];
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$stmt31 = sqlsrv_query( $conn,"SELECT * FROM groups WHERE groups_nomer = ?", [$values['groups_nomer']]);

 if ($row31 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC)){
$values['group_name']=$row31['group_name'];
$values['creator_nomer']=$row31['creator_nomer'];
$values['access_mode']=$row31['access_mode'];
}
$stmt5 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer = ?", [$values['creator_nomer']]);
$row5=sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
$values['creator_nick']=$row5['nickname'];

$stmt37 = sqlsrv_query( $conn, "SELECT nickname, nomer FROM users WHERE nomer IN (SELECT nomer FROM user_group WHERE groups_nomer=? AND NOT nomer=?)", [$values['groups_nomer'], $nomer]);

$part_nomer=array();
$part_nick=array();
while($row37 = sqlsrv_fetch_array( $stmt37, SQLSRV_FETCH_ASSOC))
{
    array_push($part_nomer, strip_tags($row37['nomer']));
    array_push($part_nick, strip_tags($row37['nickname']));
}
$participants = array_combine($part_nomer, $part_nick);
?>
    <title>Группа <?php print(' '); print($values['group_name']); ?></title>
  </head>
<body>
<header> 
<h2> ГРУППА <?php print(' '); print($values['group_name']); ?> </h2>
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
if(!empty($_POST['person_page'])){
setcookie('person_page', $_POST['person_page']);
setcookie('nomer', $nomer);
header('Location:person_page.php');
exit();
}
if(!empty($_POST['group_request'])){
$data=date("d.m.y H:i:s");
$stmt123 = sqlsrv_query( $conn,"INSERT INTO group_request  (nomer, groups_nomer, group_request_time, answer)  VALUES (?, ?, ?, 'wait')", [$nomer, $values['groups_nomer'], $data]);
sqlsrv_fetch_array( $stmt123, SQLSRV_FETCH_ASSOC);
}
if(!empty($_POST['group_request1'])){
$data=$data=date("d.m.y H:i:s");
$stmt444 = sqlsrv_query( $conn,"DELETE FROM group_request WHERE nomer=? AND groups_nomer=?", [$nomer, $values['groups_nomer']]);
sqlsrv_fetch_array( $stmt444, SQLSRV_FETCH_ASSOC);
$stmt555 = sqlsrv_query( $conn,"INSERT INTO group_request (nomer, groups_nomer, group_request_time, answer) VALUES (?, ?, ?, 'wait')", [$nomer, $values['groups_nomer'], $data]);
sqlsrv_fetch_array( $stmt555, SQLSRV_FETCH_ASSOC);
}
if(!empty($_POST['post'])){
$data=date("d.m.y H:i:s");
$stmt789 = sqlsrv_query( $conn,"INSERT INTO posts (author_nomer, group_nomer, publication_date, post) VALUES (?, ?, ?, ?)", [$nomer, $values['groups_nomer'], $data, $_POST['post']]);
sqlsrv_fetch_array( $stmt789, SQLSRV_FETCH_ASSOC);
$stmt0000 = sqlsrv_query( $conn,"SELECT posts_nomer FROM posts WHERE author_nomer=? AND group_nomer=? AND publication_date=? AND post=?", [$nomer, $values['groups_nomer'], $data, $_POST['post']]);
$row0000= sqlsrv_fetch_array( $stmt0000, SQLSRV_FETCH_ASSOC);
$stmt000 =sqlsrv_query( $conn,"INSERT INTO who_saw_post  (nomer, posts_nomer) VALUES (?, ?)", [$nomer, $row0000['posts_nomer']]);
sqlsrv_fetch_array( $stmt000, SQLSRV_FETCH_ASSOC);
}
if(!empty($_POST['delete'])){
$stmt130 = sqlsrv_query( $conn,"DELETE FROM user_group WHERE groups_nomer=?", [$values['groups_nomer']]);
sqlsrv_fetch_array( $stmt130, SQLSRV_FETCH_ASSOC);
$stmt139 = sqlsrv_query( $conn,"DELETE FROM group_request WHERE groups_nomer=?", [$values['groups_nomer']]);
sqlsrv_fetch_array( $stmt139, SQLSRV_FETCH_ASSOC);
$stmt138 = sqlsrv_query( $conn,"DELETE FROM posts WHERE group_nomer=?", [$values['groups_nomer']]);
sqlsrv_fetch_array( $stmt138, SQLSRV_FETCH_ASSOC);
$stmt136 = sqlsrv_query( $conn,"DELETE FROM groups WHERE groups_nomer=?", [$values['groups_nomer']]);
sqlsrv_fetch_array( $stmt136, SQLSRV_FETCH_ASSOC);
header('Location:groups.php');
}
if(!empty($_POST['exit'])){
$stmt135 = sqlsrv_query( $conn,"DELETE FROM user_group WHERE nomer=? AND groups_nomer=?", [$nomer, $values['groups_nomer']]);
sqlsrv_fetch_array( $stmt135, SQLSRV_FETCH_ASSOC);
$stmt137 = sqlsrv_query( $conn,"DELETE FROM group_request WHERE nomer=? AND groups_nomer=?", [$nomer, $values['groups_nomer']]);
sqlsrv_fetch_array( $stmt137, SQLSRV_FETCH_ASSOC);
}
}
setcookie('person_page', '', 100000);
$access=0;
$stmt321 = sqlsrv_query( $conn,"SELECT answer FROM group_request where nomer=? AND groups_nomer=?", [$nomer, $values['groups_nomer']]);
$row321 = sqlsrv_fetch_array( $stmt321, SQLSRV_FETCH_ASSOC);
$answer_request=0;
if(!empty($row321))
{
if($row321['answer']=='wait')
{
$answer_request=3;
}
if($row321['answer']=='yes')
{
$access=1;
}
if($row321['answer']=='no')
{
$answer_request=2;
}
}
$open=1;
$creator_ac=0;
if($nomer==$values['creator_nomer'])
{$access=1;
$creator_ac=1;
}
if($values['access_mode']=='close')
{
$open=0;
if(($access==0)&&($answer_request==0))
{
print('<br>Это закрытая группа. Если хотите получить к ней доступ, то можете подать заявку на вступление.<br>');
echo "<form action='' method='POST'>
<br><input type='submit' class='btn1' name='group_request' id='submit' value='Подать заявку' />
</form><br>";
print('<a href="groups.php">К списку групп</a><br>');
print('<a href="my_groups.php">К моим группам</a><br>');
print('<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>');
exit();
}
if(($access==0)&&($answer_request==2))
{
print('<br>Вашу заявку отклонили. Вы можете подать её повторно.<br>');
echo "<form action='' method='POST'>
<br><input type='submit' class='btn1' name='group_request1' id='submit' value='Подать заявку' />
</form>";
print('<br><a href="groups.php">К списку групп</a><br>');
print('<a href="my_groups.php">К моим группам</a><br>');
print('<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>');
exit();
}
if(($access==0)&&($answer_request==3))
{
print('<br>Ваша заявка находится на рассмотрении у создателя группы.<br><br>');
print('<a href="groups.php">К списку групп</a><br>');
print('<a href="my_groups.php">К моим группам</a><br>');
print('<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>');
exit();
}
}
$stmt1 = sqlsrv_query( $conn,"SELECT count(posts_nomer) as count from posts WHERE group_nomer=?", [$values['groups_nomer']]);

$row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC);
$max_kolvo_posts=$row1['count'];
$offset=0;
$posts=array();
$author_nick=array();
$publ_date=array();
$posts_nomer=array();
$stmt2 = sqlsrv_query( $conn,"SELECT posts_nomer, nickname, post, publication_date FROM posts, users where group_nomer=? AND nomer=author_nomer ORDER BY publication_date DESC", [$values['groups_nomer']]);
while($row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
{
    array_push($posts_nomer, strip_tags($row2['posts_nomer']));
    array_push($posts, strip_tags($row2['post']));
    array_push($author_nick, strip_tags($row2['nickname']));
    array_push($publ_date, strip_tags($row2['publication_date']->format('d.m.Y')));
}
$a1=array_combine($posts_nomer, $posts);
$a2=array_combine($posts_nomer, $author_nick);
$a3=array_combine($posts_nomer, $publ_date);
$offset+=15;
foreach($posts_nomer as $item)
{
$stmt4 = sqlsrv_query( $conn,"INSERT INTO who_saw_post (posts_nomer, nomer) VALUES (?, ?)", [$item, $nomer]);
sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
}
?>
<br>
<div class="container-fluid">
<div class="row d-flex mx-sm-m3">
        <div class="col-md-6 order-2 px-sm-3">
<h3>Посты:</h3>
<?php
if($access!=0){
print('<p>Написать пост?</p>
<form action="" method="POST">
<input type="text" name="post" value=""/><br>
<input type="submit" class="btn" name="submit" id="submit" value= "Написать" />
</form>');
}
foreach($posts_nomer as $item)
{
print('Пост от <b>');
print($a2[$item]);
print('</b> в <b>');
print($a3[$item]);
print(':</b><br><div class="btn1">');
print($a1[$item]);
print('</div><br>');
}
?>
	</div>
	<div class="col-md-3 order-1 px-sm-3">
	<h3>Cоздатель:</h3>
<form action='' method='POST'>
	<?php
if($creator_ac==1)
{print('Вы - создатель группы.<br>');
print('<form action="" method="POST">
<input type="submit" class="btn1" name="delete" id="submit" value= "Распустить группу?" />
</form><br>');
}
else{
	echo "<input type='text' class='hidden' name='person_page' value='" .$values['creator_nomer']. "'/> <input type='submit' class='btn1' name='submit_person' id='submit' value=' " .$values['creator_nick']. " '/>";
print('</form><br>');
	if(($access==1) &&($creator_ac==0))
{print('Вы - участник группы.<br>');
print('<form action="" method="POST">
<input type="submit" class="btn1" name="exit" id="submit" value= "Выйти?" />
</form><br>');
}
}
print('<a href="groups.php">К списку групп</a><br>');
print('<a href="my_groups.php">К моим группам</a><br>');
print('<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>');
	?>
	
	</div>
	<div class="col-md-3 order-3 px-sm-3">	
<?php 
print('<h3>Участники:</h3>');
if(($access==0)&&($answer_request==3))
{
print('<br>Ваша заявка находится на рассмотрении у создателя группы.<br><br>');
}
else
{
if(($access==0)&&($answer_request==2))
{
print('<br>Вашу заявку отклонили. Вы можете подать её повторно.');
echo "<form action='' method='POST'>
<input type='submit' class='btn1' name='group_request1' id='submit' value='Подать заявку'/>
</form>";
print('<br>');
}
else
{
if($access==0)
{
echo "<form action='' method='POST'>
<br><input type='submit' class='btn1' name='group_request' id='submit' value='Подать заявку на вступление?' />
</form><br>";
}
}
}
$counter1=1;
print('<div class="btn2">');
foreach($part_nomer as $item)
{
echo "<form action='' method='POST'>";
print($counter1);
print('.');
      echo "<input type='text' class='hidden' name='person_page' value='" .$item. "'/> <input type='submit' class='btn2' name='submit_person' id='submit' value=' " . $participants[$item] . " '/>";
print('</form>');
$counter1++;
}
print('</div>');
?>
	</div>
</div>
</div>
</body>