<?php 

// директивы

/* параметры отладки */
ini_set('display_errors','Off'); 

include ("mysql.php");

session_start();
?>

<!DOCTYPE HTML>

<html>

<!--**********************************-->
<head>

<!-- ПОДКЛЮЧЕНИЕ СТИЛЕЙ / КОНФИГУРАЦИЯ -->
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="/style/main.css">

<!-- ИМЯ САЙТА -->
<title> US4MEZ.com </title>

</head> 
<!--**********************************-->    


<!-- HEADER СТРАНИЦЫ -->
<div class="header">
	<a href="index.php"><div class="header-Left"> US4MEZ </div></a>
	<div class="header-Center"> РЕГИСТРАЦИЯ </div>
    <div class="header-Right"> <a href="panel.php"> <img src="/style/images/login.png" alt="" /> </a> </div>
</div>    
     
    
<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************--> 

<div class="reg-Main">  
    <form method="post">
    
	<div class="reg-Main-Row"> Логин *: <br> <p><input class="reg-Main-Input" name="login" type="text"> </div>
    <div class="reg-Main-Row"> Пароль *: <br> <p><input class="reg-Main-Input" name="password" type="password"> </div>
    <div class="reg-Main-Row"> Имя *: <br> <p><input class="reg-Main-Input" name="name" type="text"> </div>
    <div class="reg-Main-Row"> Город *: <br> <p><input class="reg-Main-Input" name="city" type="text"> </div>
    <div class="reg-Main-Row"> Телефон: <br> <p><input class="reg-Main-Input" name="phone" type="number"> </div>
    <div class="reg-Main-Row"> E-Mail *: <br> <p><input class="reg-Main-Input" name="mail" type="email"> </div>
    <div class="reg-Main-Row"><img src="captcha.php"/><br /></div>
    <div class="reg-Main-Row">Введите капчу: <br> <input type="text" name="capcha" /></div>
    <p><input class="reg-Main-Button" type="submit" name="button_reg" value="Продолжить"></p>
        
    </form>
</div>  
    
<!-- ОБРАБОТКА ФОРМЫ ДЛЯ РЕГИСТРАЦИИ -->
<?php

// проверка на нажатие кнопки
if( isset($_POST['button_reg']) )
{
  
    $Reg_Connect = new ConnectDB();
    
    // получение данных с формы
    $login = $Reg_Connect->ProtectString($_POST['login']);
    $password = $Reg_Connect->ProtectString($_POST['password']);
    $name = $Reg_Connect->ProtectString($_POST['name']);
    $city = $Reg_Connect->ProtectString($_POST['city']);
    $phone = $Reg_Connect->ProtectString($_POST['phone']);
    $mail = $Reg_Connect->ProtectString($_POST['mail']);
    
    // проверка на полноту данных
    if( !empty($login) && !empty($password) && !empty($name) && !empty($city) && !empty($mail) )
    {
        
        // проверка существования пользователя
        if( !mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '$login' OR author_mail = '$mail'") ) )
    	{
    		// проверяем, если капча была введена 
                if ( isset($_POST['capcha']) ) {
                    
                    $code = $_POST['capcha'];

                    // сравниваем введенную капчу с сохраненной в переменной в сессии 
                    if(isset($_SESSION['capcha']) && strtoupper($_SESSION['capcha']) == strtoupper($code)) {
                        
                        if( $Reg_Connect->WriteToTable("authors", "author_login,author_password,author_name,author_city,author_phone,author_mail", "'{$login}', '{$password}', '{$name}', '{$city}', '{$phone}', '{$mail}'") )
                        {       

                            // формирования уведомления об успешной регистрации
                                    $to  = "Arthur.Lazarenko@zoho.com" ; 
                                    $subject = "Регистрация нового пользователя"; 
                                    $message = " 
                                                <html> 
                                                    <head> 
                                                        <title>Уведомление о регистрации нового пользователя</title> 
                                                    </head> 
                                                    <body> 
                                                        <p>Никнейм: $login</p>
                                                        <p>Имя: $name</p>
                                                        <p>Город: $city</p>
                                                        <p>Дата регистрации: " . date('l dS of F Y h:i:s A') . "</p>
                                                    </body> 
                                                </html>
                                                "; 
                                    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
                                    $headers .= "From: Доска объявлений \r\n"; 

                                    // отправка сообщения на почту
                                    mail($to, $subject, $message, $headers);

                                    // удаление старой сессии
                                    unset($_SESSION['login']);

                                    // запись новых данных в сессию
                                    $_SESSION['login'] = $login;

                                    // обновление куки
                                    setcookie("login", $login);
                                    setcookie("password", $password);

                                    // переадресация на панель управления
                                    echo "<script type='text/javascript'>";
                                    echo "location.replace('panel.php')";
                                    echo "</script>";

                                    echo "<div class='reg-Main-Info'> Вы успешно зарегистрированы. Можете перейти в личный кабинет. </div></a>";

                        }
                        else
                        {
                            echo "<div class='reg-Main-Info'> Не удалось добавить пользователя. </div></a>";    
                        }
                        
                    } else {
                        
                        echo "<div class='reg-Main-Info'> Неверно введена капча. </div></a>";
                        
                    }

                    // удаляем капчу из сессии 
                    unset($_SESSION['capcha']);
                }
    	}
        else
        {
            echo "<div class='reg-Main-Info'> Такой пользователь уже существует. </div>";
        }
        
    }
    else
    {
        echo "<div class='reg-Main-Info'> Заполнены не все поля. </div>";
    }
    
    $Reg_Connect->Close();
} 

?>

<!--**********************************-->


<!-- FOOTER СТРАНИЦЫ -->
<div class="footer">
    <div class="footer-Center"> Powered by ArtReeX </div>
</div>

<!--**********************************--> 

</html>		