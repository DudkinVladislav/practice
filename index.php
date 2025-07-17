<!DOCTYPE html>
<html lang="ru">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Соцсеть с трекером привычек</title>
<style>
    .error {
	border: 2px solid red;
	}
body { margin:0;
	display:flex;
	flex-direction:column;
text-align:center}
header {display:flex;
flex-direction: column;
text-align: center;
}
#footer {
    position: fixed; 
    left: 0; bottom: 0; 
    padding: 10px; 
    width: 100%; 
   }
body {
  background-image: url("kartinki-svetlii-fon.jpg");
}
    </style>
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    
</head>
<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
   // Массив для временного хранения сообщений пользователю.
  $messages = array();
if((!empty($_GET['quit']))&&($_GET['quit']=1))
{
setcookie('nomer', '', 100000);
}
  if (!empty($_COOKIE['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    
    $messages[] = 'Спасибо за регистрацию.<br> ';
      // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <u>%s</u>
        и паролем <u>%s</u><br> (Сохраните логин и пароль. Oни необходимы для входа).',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
  }
// Складываем признак ошибок в массив.
 $errors = array();
 $errors['name'] = !empty($_COOKIE['name_error']);
 $errors['email'] = !empty($_COOKIE['email_error']);
 $errors['date'] = !empty($_COOKIE['date_error']);
// Выдаем сообщения об ошибках.
 if ($errors['name']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('name_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error"> Неверный ввод имени.</div>';
  }
 if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Неправельный ввод email.</div>';
  }
 if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages[] = '<div class="error">Выберите дату.</div>';
  }
// Складываем предыдущие значения полей в массив, если есть.
  $values = array();
   // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
if(!empty($_GET['quit']))
{
include('form_start.php');
exit();
}

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
include('form_start.php');
}
else{
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['name'])) {
 setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('name_value', $_POST['name'], time() + 12 * 31 * 24 * 60 * 60);
  }
if (empty($_POST['email'])) {
  setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 12 * 31 * 24 * 60 * 60);
  }
if (empty($_POST['date'])) {
  setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('date_value', $_POST['date'], time() + 12 * 31 * 24 * 60 * 60);
  }
    setcookie('biography_value', $_POST['biography'], time() + 12 * 31 * 24 * 60 * 60);
if ($errors) {
  // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
  header('Location: index.php');
    exit();
}
else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('date_error', '', 100000);
    setcookie('biography_error', '', 100000);
  }
  
$name=$_POST['name'];
$email=$_POST['email'];
$date=$_POST['date'];
$bio=$_POST['biography'];

    //Создаём уникальный логин и пароль
   $st=uniqid();
    $fir=md5($st);
    $login=substr($st,10,20);
    $pass2=md5($fir);
     setcookie('login', $login);
    setcookie('pass', $pass2);
  // Сохранение в базу данных.
$serverName = "HP-ENVY-BUK"; 
$connectionInfo = array( "Database"=>"TaskBase", "UID"=>"sa", "PWD"=>"Vlad2002", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn==false ) {
     echo "Соединение не установлено.<br />";
     die( print_r( sqlsrv_errors(), true));
}
else
{var_dump($conn);}

$data=new DateTime();
$date = new DateTime($_POST['date']);
$stmt = sqlsrv_query( $conn, "INSERT INTO users (nickname, email, date, biography, registration_date, message_mode) VALUES (?, ?, ?, ?, ?, ?)" ,array($_POST['name'], $_POST['email'], $date, $_POST['biography'],$data,1));
// Подготовленный запрос. Не именованные метки.
  $res = sqlsrv_query( $conn, "SELECT max(nomer) as max_nomer FROM users", []);
    $row  = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC);
    $count = (int) $row['max_nomer'];
  $stmt = sqlsrv_query( $conn, "INSERT INTO login_password  (nomer, login, password) VALUES (?, ?, ?)", [$count, $login, md5($pass2)]);
    
$stmt = sqlsrv_query( $conn, "INSERT INTO users_recv  (nomer, login) VALUES (?, ?)", [$count, $_POST['name']]);

 // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
?>
</html>