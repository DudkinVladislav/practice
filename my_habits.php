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
	  </style>
    <title>Мои привычки</title>
  </head>
<?php
$nomer=$_COOKIE['nomer'];
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
<h2> МОИ ПРИВЫЧКИ </h2>
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
if($share_habit==0){
print('<h3><a href="my_shared_habits.php">!!!Друзья поделились привычками!!!</a></h3>');
}

$select_friends='<select name="friends"><option disabled>Выберите имя</option>';

    $friend_nomer=array();
    $friend_nick=array();
    $stmt33 = sqlsrv_query( $conn,"SELECT nomer, nickname from users where nomer IN (SELECT nomer_one from friends where nomer_two=? UNION SELECT nomer_two from friends where nomer_one=?)", [$nomer, $nomer]);
    while($row33 = sqlsrv_fetch_array( $stmt33, SQLSRV_FETCH_ASSOC))
        {
            array_push($friend_nomer, strip_tags($row33['nomer']));
            array_push($friend_nick, strip_tags($row33['nickname']));
        }
        for ($i=0; $i<count($friend_nomer); $i++) {
            $select_friends.= "<option value ='" . $friend_nomer[$i] . "'>" . $friend_nick[$i] . "</option>";
        }
    $select_friends.=" </select> ";
if($_SERVER['REQUEST_METHOD'] == 'POST')
{            
    if (!empty($_POST["habit_name"]))
    {
        if (!empty($_POST["public"]))
        {
            $public_mode=1;
        }
        else
        {
            $public_mode=0;
        }
        $date=new DateTime();
    $stmt31 = sqlsrv_query( $conn, " set nocount on;
    INSERT INTO habits (user_nomer, habit_name, start_date, public_habit) VALUES (?, ?, ?, ?); 
    set nocount off;
    SELECT SCOPE_IDENTITY() as nomer", [$nomer, $_POST["habit_name"], $date, $public_mode]);
    $row31 = sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    $stmt31 = sqlsrv_query( $conn, "INSERT INTO habit_days (habit_nomer, day) VALUES (?, ?)", [$row31["nomer"], $date]);
    $row31 = sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    }
    if (!empty($_POST["delete_habit"]))
    {
        $stmt31 = sqlsrv_query( $conn, 
        "DELETE FROM habits WHERE habit_nomer=?;
         DELETE FROM share_habits WHERE habit_nomer=?;
         DELETE FROM habit_days WHERE habit_nomer=?", [$_POST["habit_nomer"], $_POST["habit_nomer"], $_POST["habit_nomer"]]);
        $row31 = sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    }
    if (!empty($_POST["share_habit"]))
    {
        if (!empty($_POST["friends"]))
        {
            $friend=$_POST["friends"];
        }
        $stmt31 = sqlsrv_query( $conn, "INSERT INTO share_habits (habit_nomer, viewer_nomer, seen) VALUES (?, ?, 'no')", [$_POST["habit_nomer"], $friend]);
        $row31 = sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    }
}
?>

<table align="center" style="text-align: center; width:50%; border:1px solid black">
    <?php 
    $first=1;
    $stmt31 = sqlsrv_query( $conn, "SELECT * FROM habits WHERE user_nomer=?", [$nomer]);
    while($row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC))
    {
        $stmt3211 = sqlsrv_query( $conn, "WITH t1 as (SELECT count(day) as days_all FROM habit_days WHERE habit_nomer=?) SELECT CAST(CAST(count(day) AS FLOAT)/(SELECT t1.days_all FROM t1)*100 AS INT) as [percent] FROM habit_days WHERE habit_nomer=? AND do_or_do_not='do'", [$row31["habit_nomer"],$row31["habit_nomer"]]);
        $row3211=sqlsrv_fetch_array($stmt3211, SQLSRV_FETCH_ASSOC);
        ?>
    <tr>
        <td>
            <div style="color:orangered" id="habit_name" habit_nomer="<?php echo $row31["habit_nomer"]; ?>"> <?php echo $row31["habit_name"]; ?></div>
        </td>
        <td>
            <div><?php echo "Выполнено на ". $row3211["percent"]."%"; ?></div>
        </td>
        <td>
             <form action='habit.php' method='POST' id="edit_habit">
            <input type="hidden" name="habit_nomer" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="<?php echo $row31["habit_nomer"]; ?>">
            <input type="submit" name="edit_habit" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="Изменить">
            </form>
            <form action='' method='POST' id="delete_habit">
            <input type="hidden" name="habit_nomer" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="<?php echo $row31["habit_nomer"]; ?>">
            <input type="submit" name="delete_habit" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="Удалить">
            </form>
            <form action='' method='POST' id="share_habit">
            <input type="hidden" name="habit_nomer" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="<?php echo $row31["habit_nomer"]; ?>">
            <input type="submit" name="share_habit" habit_nomer="<?php echo $row31["habit_nomer"]; ?>" value="Поделиться c">
            <?php echo $select_friends; ?> 
            </form>
        </td>
        <?php if ($first==1){ ?>
        <td>
            если поделитесь,то<br>пользователь<br>увидит ваш прогресс
        </td>
        <?php $first=0; }?>
    </tr>
    
    <?php }?>
</table>
<br>
<br>
<form align="center" action='' method='POST' id="new_habit">
    <textarea type="text" name="habit_name" placeholder="Новая привычка"></textarea><br>
 Сделать привычку приватной?
    <input id="checkbox" type="checkbox" name="public">
    <input type="submit" name="add_habit" value="Добавить">
</form>
<br>
<br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>