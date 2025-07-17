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


      <?php
        if (!empty($_POST["habit_nomer"]))
            {
            $nomer_habit=$_POST["habit_nomer"];
            }
$nomer=$_COOKIE['nomer'];
setcookie('nomer', $nomer);
 $serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$stmt31 = sqlsrv_query( $conn, "SELECT * FROM habits left join users on habits.user_nomer=users.nomer WHERE habit_nomer = ?", [$nomer_habit]);
$row_habit = sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);

?>
    <title>Привычка <?php echo $row_habit["nickname"]."   ".$row_habit["habit_name"]; ?> </title>
  </head>
<?php
$nomer=$_COOKIE['nomer'];
setcookie('nomer', $nomer);
 $stmt34 = sqlsrv_query( $conn, "UPDATE share_habits SET seen='yes' where habit_nomer=? AND viewer_nomer=? ", [$row_habit["habit_nomer"], $nomer]);
    $row34 = sqlsrv_fetch_array($stmt34, SQLSRV_FETCH_ASSOC);

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
<h2> ПРИВЫЧКА <?php if ($row_habit["user_nomer"]!=$nomer) {echo $row_habit["nickname"].'   ';}  echo '"'.trim($row_habit["habit_name"]).'"';  ?> </h2>
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
?>
<?php if ($nomer==$row_habit["user_nomer"])
{
    $date=new DateTime();
    if (!empty($_POST["add_day"]))
    {
        $stmt31 = sqlsrv_query( $conn, "SELECT MAX(day) as day FROM habit_days WHERE habit_days.habit_nomer=?", [$row_habit["habit_nomer"]]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
        $new_day=$row31["day"];
        $add_day=DateInterval::createFromDateString('1 day');
        $new_day=$new_day->add($add_day);
        $stmt31 = sqlsrv_query( $conn, "INSERT INTO habit_days (habit_nomer, day) VALUES (?,?)", [$row_habit["habit_nomer"], $new_day]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    }
    if (!empty($_POST["add_week"]))
    {
        $stmt31 = sqlsrv_query( $conn, "SELECT MAX(day) as day FROM habit_days WHERE habit_days.habit_nomer=?", [$row_habit["habit_nomer"]]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
        $new_day=$row31["day"];
        $add_day=DateInterval::createFromDateString('1 day');
        for ($i=0; $i<7; $i++){
        $new_day=$new_day->add($add_day);
        $stmt31 = sqlsrv_query( $conn, "INSERT INTO habit_days (habit_nomer, day) VALUES (?,?)", [$row_habit["habit_nomer"], $new_day]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);}
    }
    if (!empty($_POST["add_month"]))
    {
        $stmt31 = sqlsrv_query( $conn, "SELECT MAX(day) as day FROM habit_days WHERE habit_days.habit_nomer=?", [$row_habit["habit_nomer"]]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
        $new_day=$row31["day"];
        $add_day=DateInterval::createFromDateString('1 day');
        for ($i=0; $i<30; $i++){
        $new_day=$new_day->add($add_day);
        $stmt31 = sqlsrv_query( $conn, "INSERT INTO habit_days (habit_nomer, day) VALUES (?,?)", [$row_habit["habit_nomer"], $new_day]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);}
    }
    if (!empty($_POST["save"])){
    $stmt = sqlsrv_query( $conn, "SELECT day, do_or_do_not FROM habit_days WHERE habit_days.habit_nomer=? ORDER BY day", [$row_habit["habit_nomer"]]);
    
    while($row=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
    if (!empty($_POST["day_".$row["day"]->format('d_m_Y')]) && $_POST["day_".$row["day"]->format('d_m_Y')]=="on")
    {
        $stmt31 = sqlsrv_query( $conn, "UPDATE habit_days SET do_or_do_not='do' WHERE habit_days.habit_nomer=? and day=?", [$row_habit["habit_nomer"], $row["day"]]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    }
    else{
        $stmt31 = sqlsrv_query( $conn, "UPDATE habit_days SET do_or_do_not='do_not' WHERE habit_days.habit_nomer=? and day=?", [$row_habit["habit_nomer"], $row["day"]]);
        $row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC);
    }
    }
    }
    ?>
<form method="POST" action="" name="edit_habit">
<input type="text" name="edit_habit_name" value="<?php echo $row_habit["habit_name"]; ?>">
<input type="hidden" name="habit_nomer" value="<?php echo $row_habit["habit_nomer"]; ?>">
<input type="submit" name="add_day" id="add_day" value="Добавить день">
<input type="submit" name="add_week" id="add_week" value="Добавить неделю">
<input type="submit" name="add_month" id="add_month" value="Добавить месяц">
<br>
<br>
<input type="submit" name="save" id="save" value="Сохранить">
<br>
<br>
<table align="center" style="text-align: center; ">
    <?php 
    $first=0;
    $date=new DateTime();
    $stmt31 = sqlsrv_query( $conn, "SELECT day, do_or_do_not FROM habit_days  WHERE habit_days.habit_nomer=? ORDER BY day", [$row_habit["habit_nomer"]]);
    while($row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC))
    {
    if (($first%6)==0){  ?>
    <tr><?php } ?>
        <td style="border: 1px solid black; border-radius: 5px; width:30px;">
            <?php echo $row31["day"]->format('d.m.Y');
            echo "<br>";
            
            $day="";
            $day.="<input type='checkbox' name='day_".$row31["day"]->format('d_m_Y')."'";
                if ($row31["day"]>$date)
                {
                    $day.="disabled";
                }
                if (!empty($row31["do_or_do_not"]) && $row31["do_or_do_not"]=="do")
                { 
                    $day.="checked";
                }
            $day.=">";
            echo $day;
            ?>
        </td>
    <?php $first++; if (($first%6)==0){ ?>
    </tr>
    <?php } ?>
    <?php  
    }?>
</table>
</form>
<?php }else{ 
    ?>
    <table align="center" style="text-align: center;">
    <?php 
    $first=0;
    $stmt31 = sqlsrv_query( $conn, "SELECT day, do_or_do_not FROM habit_days  WHERE habit_days.habit_nomer=?", [$row_habit["habit_nomer"]]);
    while($row31=sqlsrv_fetch_array($stmt31, SQLSRV_FETCH_ASSOC))
    {
    if ($first%6==0){ ?>
    <tr><?php } ?>
        <td style="border: 1px solid black; border-radius: 5px; width:30px;">
            <?php echo $row31["day"]->format('d.m.Y');
            echo "<br>";
            $day="";
                $day.="<input type='checkbox' disabled name='day_".$row31["day"]->format('d.m.Y')."'";
                if (!empty($row31["do_or_do_not"]) && $row31["do_or_do_not"]=="do")
                { 
                    $day.="checked";
                }
            $day.=">";
            echo $day;
            ?>

        </td>
    <?php $first++; if ($first%6==0){ ?>
    </tr>
    <?php } ?>
    <?php  
    }?>
</table>
<?php } ?>
<br>
<br>
<?php if($nomer==$row_habit["user_nomer"]){?>
<a href="my_habits.php"  title = "return">К моим привычкам</a>
<?php }else{
    setcookie('person_nomer', $row_habit["user_nomer"]);
    ?>
<a href="person_habits.php"  title = "return">К привычкам пользователя</a>

    <?php  } ?>
<br>
<br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>
</body>