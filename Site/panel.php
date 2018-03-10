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
	<div class="header-Center"> ПАНЕЛЬ УПРАВЛЕНИЯ </div>
</div>    
     
    
<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************-->   
    
<!-- ОБРАБОТКА ФОРМЫ ДЛЯ РЕГИСТРАЦИИ -->
<?php
    

// проверка на наличие авторизации пользователя
if( empty($_SESSION['login']) )
{
    // если пользователь не авторизирован, делаем переадресацию на страницу входа
    echo "<script type='text/javascript'>";
    echo "location.replace('login.php')";
    echo "</script>";
}
else
{
    echo "<div class='panel-Main'>"; 
    
    // жлементы меню
    echo "<form method='post'>";
        echo "<p><input class='panel-Main-Button' type='submit' name='add_ad_button' value='Добавить объявление'></p>";
        echo "<p><input class='panel-Main-Button' type='submit' name='del_ad_button' value='Удалить объявление'></p>";    
        echo "<p><input class='panel-Main-Button-Exit' type='submit' name='exit_button' value='Выход с аккаунта'></p>";
    echo "</form>";
        
    echo "</div>";
    
    
    // проверка нажатия кнопки выхода
    if( isset($_POST['exit_button']) )
    {
        // очистка данных старой сессии
        unset($_SESSION['login']);
        
        // сброс куков
        setcookie("login", "");
        setcookie("password", "");
        
        // переадресация на страницу входа
        echo "<script type='text/javascript'>";
        echo "location.replace('login.php')";
        echo "</script>";
    } 
    // проверка нажатия кнопки добавления объявления
    else if ( isset($_POST['add_ad_button']) )
    {
        // переадресация на страницу добавления объявления
        echo "<script type='text/javascript'>";
        echo "location.replace('add_ad.php')";
        echo "</script>";
    }
    // проверка нажатия кнопки удаления объявления
    else if ( isset($_POST['del_ad_button']) )
    {
        // переадресация на страницу удаления объявления
        echo "<script type='text/javascript'>";
        echo "location.replace('del_ad.php')";
        echo "</script>";
    }
}

?>


<!--**********************************-->


<!-- FOOTER СТРАНИЦЫ -->
<div class="footer">
    <div class="footer-Center"> Powered by ArtReeX </div>
</div>

<!--**********************************--> 

</html>		