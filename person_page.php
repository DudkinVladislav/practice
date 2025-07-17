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
            color: #0000FF;
        }
.hidden{
	display:none;
	}
form {
margin-block-end:0em;
}
	  </style>
    <title>Страница пользователя</title>
  </head>
<?php
$nomer=$_COOKIE['nomer'];
if (!empty($_GET['person_page']))
{
  setcookie('person_page', $_GET['person_page']);
  $nomer_person=$_GET['person_page'];
}
if (!empty($_COOKIE['person_page']))
{
$nomer_person=$_COOKIE['person_page'];
setcookie('person_page', $_COOKIE['person_page']);
}
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
if(!empty($_POST['friend']))
{
$data=date("d.m.y H:i:s");
$stmt29 = sqlsrv_query( $conn,"INSERT INTO friends_request (nomer_send, nomer_recv, request_time, see) VALUES (?, ?, ?, 'no') ", [$nomer, $nomer_person, $data]);
sqlsrv_fetch_array( $stmt29, SQLSRV_FETCH_ASSOC);
}
else{
if(!empty($_POST['message']))
{
setcookie('nomer', $nomer);
setcookie('nomer_write', $_COOKIE['person_page']);

header('Location: conversation.php');
}
}
}
$request=0;
$stmt191=sqlsrv_query( $conn,"SELECT nomer_send, see, answer FROM friends_request WHERE nomer_send = ? AND nomer_recv=? UNION SELECT nomer_send, see, answer FROM friends_request WHERE nomer_send = ?  AND nomer_recv=?", [$nomer, $nomer_person, $nomer_person, $nomer]);

while($row191 = sqlsrv_fetch_array( $stmt191, SQLSRV_FETCH_ASSOC))
{
if($row191['nomer_send']==$nomer)
{
if($row191['see']=='no')
{
$request=1;
}
else{
if($row191['answer']=='no')
{
$request=2;
}
if($row191['answer']=='yes')
{
$request=3;
}
}
}

if($row191['nomer_send']==$nomer_person)
{
if($row191['see']=='no')
{
$request=4;
}
else{
if($row191['answer']='yes')
{
$request=3;
}
}
}
}

$stmt31 = sqlsrv_query( $conn,"SELECT * FROM users WHERE nomer = ?", [$nomer]);

$row31 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC);
$stmt4 = sqlsrv_query( $conn,"SELECT * FROM users WHERE nomer = ?", [$nomer_person] );

$row4 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_ASSOC);
$values = array();
$values['nickname']=$row4['nickname'];
$values['biography']=$row4['biography'];
$values['date']=$row4['date'];
$values['email']=$row4['email'];
?>
<body>
<header>
<h2> CТРАНИЦА ПОЛЬЗОВАТЕЛЯ<br><br>
  <div style="color: red"><?php print($values['nickname']); ?> </div></h2>
</header>
<body>
<br><h2>Информация:</h2><br>
<p>Электронная почта - <?php print($values['email']);?></p><br>
<p>Дата рождения - <?php print($values['date']->format('d.m.Y'));?></p><br>
<p>Биография - <?php print($values['biography']);?></p><br>
<?php
if($request==0)
{
 echo "<form action='' method='POST'>";
      echo "<input type='submit' class='btn1' name='friend' id='submit' value='Отправить предложение подружиться?'/>";
print('</form>');
}
if($request==1)
{
print('Предложение подружиться отпрaвлено.');
}
if($request==2)
{
print($values['nickname']);
print(' отклонил ваше предложение стать друзьями.');
 echo "<form action='' method='POST'>";
      echo "<input type='submit' class='btn1' name='friend' id='submit' value='Отправить ещё одно?'/>";
print('</form>');
}
if($request==3)
{
print('Это - ваш друг!');
}
if($request==4)
{
print('Пользователь отправил вам предложение подружиться! <a href="my_friends.php">Перейти к списку друзей?</a>');
}
?>
<br>
<br>
<?php echo "<form action='' method='POST'>";
      echo "<input type='submit' class='btn1' name='message' id='submit' value='Написать'/>";
print('</form>');
?>
<br>

<br>
<?php echo "<form action='person_habits.php' method='POST'>";
      echo "<input type='hidden' name='person_nomer' value='".$nomer_person."'>";
      echo "<input type='submit' class='btn1' name='person_habits' id='submit' value='Привычки пользователя'/>";
print('</form>');
?>
<br>
<br>
<a href="people.php">К списку пользователей</a><br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a><br>
</body>
