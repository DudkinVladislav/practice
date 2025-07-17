<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta charset="utf-8"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
      <title>Авторизация</title>
    <style>
    .error {
	border: 2px solid red;
	}
body { margin:0;
	display:flex;
	flex-direction:column;
text-align:center;
background-image: url("kartinki-svetlii-fon.jpg");}
header {display:flex;
flex-direction: column;
text-align: center;
}
    </style>
    </head>
  <body style="margin-top:300px;">
<header>
<h1>Вход в учётную запись</h1>
</header>
<br><h2>Хотите войти?</h2>
<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации
// В суперглобальном массиве $_SERVER PHP сохраняет некоторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['nologin']))
    print("<div>Пользователя с таким логином не существует</div>");
  if (!empty($_GET['wrongpass']))
    print("<div>Неверный пароль!</div>");
?> 
<h1 >
    <form action="" method="POST">
      <input type="text" name="login" placeholder="логин"/>
      <input type="text" name="pass" placeholder="пароль"/>
      <input type="submit" name="submit" id="submit" value="Войти" />
    </form>
</h1>
    <?php
}
    else{
     $serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
  $stmt = sqlsrv_query( $conn, "SELECT nomer, password FROM login_password WHERE login = ?", [$_POST['login']]);
  $row  = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
  if (!$row) {
    header('Location: ?nologin=1');
    exit();
  }
  if($row['password'] != md5($_POST['pass'])) {
    header('Location: ?wrongpass=1');
    exit();
  }
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['nomer'] = $row['nomer'];
setcookie('nomer',$row['nomer']);
  // Делаем перенаправление.
  header('Location: my_page.php');
}

?>

</body>
</html>