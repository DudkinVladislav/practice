<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].


?>

    <body>
        <h1 style="text-align: center; margin-top:20px">Добро пожаловать!<br> Это наша соцсеть, где есть трекер привычек! <br>
        Для её использования Вам следует зарегистрироваться ниже. Если Вы уже зарегистрированы, то можете <a href="login.php">войти</a><br><br>
<?php
        for ($i=0; $i<count($messages); $i++)
{
    echo $messages[$i];
}?>
    </h1><br><br><br>
    <h1>
    <form method='POST' form action='index.php' style="text-align: center; margin-top:20px">
       <label style="text-align: center; margin-top:20px; "> Имя пользователя: <input type="text" name="name"></label><br><br>
       <label style="text-align: center; margin-top:20px; "> Дата Рождения: <input  type="date" name="date"></label><br><br>
       <label style="text-align: center; margin-top:20px; "> Адрес электронной почты: <input type="email" name="email"></label><br><br>
       <label style="text-align: center; margin-top:20px; "> Расскажите о себе (заполнять не обязательно):<br> <textarea name="biography" rows="10" style="width:300px"></textarea></label><br>
        <input type='submit' class='btn1' name='submit_page' id='submit' value='Отправить'/>
    </form>
    </h1>
    </body>
