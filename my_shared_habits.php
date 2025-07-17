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
	    font-size: 105%;
            cursor: pointer;
            border: 1px solid #ff9911;
            background-color: #ff9911;
            color: #000000;
        }
	  </style>
    <title>Друзья поделились привычками</title>
  </head>
<?php
$nomer=$_COOKIE['nomer'];
setcookie('nomer', $nomer);
 $serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$stmt31 = sqlsrv_query( $conn, "SELECT * FROM users WHERE nomer = ?", [$nomer]);
$row31 = sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);

$values = array();
$values['nickname']=$row31['nickname'];
$values['biography']=$row31['biography'];
$values['date']=$row31['date'];
$values['email']=$row31['email'];


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
  <body align="center">
<header align="center" style="text-align: center;">
<h2> ДРУЗЬЯ ПОДЕЛИЛИСЬ ПРИВЫЧКАМИ </h2>
</header>
<?php
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

?>
<table align="center" style="text-align: center; width:50%; border: 1px solid blue;">
    <?php 
    $first=1;
    $stmt31 = sqlsrv_query( $conn, "SELECT users.nomer, users.nickname, habits.habit_name, habits.habit_nomer FROM share_habits left join habits on habits.habit_nomer=share_habits.habit_nomer
    left join users on users.nomer=habits.user_nomer WHERE share_habits.viewer_nomer=? and seen='no'", [$nomer]);
    while($row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC))
    {?>
    <tr>
        <td style="margin-left:10px;">
            <a href="person_page.php?person_page=<?php echo $row31['nomer']; ?>"  title = "return"><?php echo $row31['nickname']; ?></a>
        </td>
        <td>
            <form style="margin: auto;" action='habit.php' method='POST' id="see_habit">
            <input type="hidden" name="habit_nomer" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="<?php echo $row31["habit_nomer"]; ?>">
            <input type="hidden" name="see_habit" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="shared">
            <?php echo $row31["habit_name"]; ?>
        </td>
        <td>
            <input type='submit' class='btn' name='shared' id='submit' value= "Посмотреть" />
            </form>
        </td>
    </tr>
    
    <?php }?>
</table>
<br>
<br>
<a href="my_habits.php"  title = "return">К моим привычкам</a>
<br>
<br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>