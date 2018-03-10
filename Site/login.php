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
	<div class="header-Center"> ВХОД </div>
    <div class="header-Right"> <a href="panel.php"> <img src="/style/images/login.png" alt="" /> </a> </div>
</div>    
     
    
<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************--> 

<div class="login-Main">  
    <form method="post">
    
	<div class="login-Main-Row"> Логин: <br> <p><input class="login-Main-Input" name="login" type="text"> </div>
    <div class="login-Main-Row"> Пароль: <br> <p><input class="login-Main-Input" name="password" type="password"> </div>

    <p><input class="login-Main-Button" type="submit" name="button_login" value="Продолжить"></p>
        
    <p><input class="login-Main-Button-Reg" type="submit" name="button_reg" value="Регистрация"></p>
        
    </form>
</div>  
    
    
<!-- ОБРАБОТКА ФОРМЫ ДЛЯ РЕГИСТРАЦИИ -->
<?php

// проверка на нажатие кнопки входа
if( isset($_POST['button_login']) )
{
  
    $Reg_Connect = new ConnectDB();
    
    // получение данных с формы
    $login = $Reg_Connect->ProtectString($_POST['login']);
    $password = $Reg_Connect->ProtectString($_POST['password']);
    
    // проверка на полноту данных
    if( !empty($login) && !empty($password) )
    {
        
        // проверка существования пользователя
        if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '$login' AND author_password = '$password'") ) )
    	{
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

            echo "<div class='login-Main-Info'> Вы успешно вошли. Можете перейти в личный кабинет. </div></a>";
    	}
        else
        {
            echo "<div class='login-Main-Info'> Такого пользователя не существует. </div>";
        }
        
    }
    else
    {
        echo "<div class='login-Main-Info'> Заполнены не все поля. </div>";
    }
    
    $Reg_Connect->Close();
} 
// проверка на нажатие кнопки регистрации
else if ( isset($_POST['button_reg']) )
{
    // переадресация на страницу регистрации
    echo "<script type='text/javascript'>";
    echo "location.replace('reg.php')";
    echo "</script>";
}

?>

<!--**********************************-->


<!-- FOOTER СТРАНИЦЫ -->
<div class="footer">
    <div class="footer-Center"> Powered by ArtReeX </div>
</div>

<!--**********************************--> 

</html>		