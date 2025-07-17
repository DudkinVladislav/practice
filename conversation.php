<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script src="script.js" defer></script>

<style>
form, p, span {
    margin:0;
    padding:0; }
  
input { font:12px arial; }
  
a {
    color:#0000FF;
    text-decoration:none; }
  
    a:hover { text-decoration:underline; }
  
#wrapper, #loginform {
    margin:0 auto;
    padding-bottom:25px;
    background:#EBF4FB;
    width:504px;
    border:1px solid #ACD8F0; }
  
#loginform { padding-top:18px; }
  
    #loginform p { margin: 5px; }
  
#chatbox {
    text-align:left;
    margin:0 auto;
    margin-bottom:25px;
    padding:10px;
    background:#fff;
    height:370px;
    width:430px;
    border:1px solid #ACD8F0;
    overflow:auto; }
  
#usermsg {
    width:395px;
    border:1px solid #ACD8F0; }
  
#submit { width: 60px; }
  
.error { color: #ff0000; }
  
#menu { padding:12.5px 25px 12.5px 25px; }
  
.welcome { float:left; }
  
.logout { float:right; }
  
.msgln { margin:0 0 2px 0; }

body { 
margin:0;
display:flex;
flex-direction: column;
text-align: center;
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
	    border: 1px solid #ff9911;
            cursor: pointer;
            background-color: transparent;
            color: #FF00FF;
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
$values['nomer_write']=$_COOKIE['nomer_write'];
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$stmt31 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer = ?", [$values['nomer_write']]);

$row31 = sqlsrv_fetch_array( $stmt31, SQLSRV_FETCH_ASSOC);
$values['write_name']=$row31['nickname'];
$stmt5 = sqlsrv_query( $conn,"SELECT nickname FROM users WHERE nomer = ?", [$nomer] );

$row5=sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_ASSOC);
$values['user_nick']=$row5['nickname'];
?>
    <title>Переписка с <?php print(' '); print($values['write_name']); ?></title>
  </head>

<body>
<header> 
<h2> ПЕРЕПИСКА С <?php print(' '); print($values['write_name']); ?> </h2>
</header>
<input type="hidden" name="nomer" value="<?php echo $nomer; ?>">
<input type="hidden" name="nomer_send" value="<?php echo $values['nomer_write']; ?>">
<br>
<div id="chatbox">
     <div class='chat'>
	<div class='chat-messages' id='box'>
		<div class='chat-messages__content' id='messages'>
		</div>
	</div>
</div>
</div>
    <form method='post' id='chat-form'>
			<input type='text' name='message' id='message-text' class='chat-form__input' placeholder='Введите сообщение'> <input type='submit' class='chat-form__submit' value='Отправить'>
		</form>
</div>

<br><a href="my_messages.php">К моим перепискам</a><br>
<a href="my_page.php"  title = "return">Вернуться на свою страницу</a>

</body>