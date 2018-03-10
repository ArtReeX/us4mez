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
	<div class="header-Center"> ПАНЕЛЬ УДАЛЕНИЯ ОБЪЯВЛЕНИЙ </div>
	<div class="header-Right"> <a href="panel.php"> <img src="/style/images/login.png" alt="" /> </a> </div>
</div>


<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************--> 

<div class="del-Ad-Main"> 
    
    
    <!-- ВЫВОД СПИСКА ОБЪЯВЛЕНИЙ ДЛЯ УДАЛЕНИЯ -->
    <?php
    
    $Reg_Connect = new ConnectDB();
    
    
    /* ОБЪЯВЛЕНИЯ */
    
    // получение ID пользователя
    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '{$_SESSION['login']}' LIMIT 1") ) )
    {
        $result = $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '{$_SESSION['login']}' LIMIT 1");

        $author = mysqli_fetch_array($result)[0];
    }
    
    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM ads WHERE ad_author = $author ORDER BY ad_time DESC") ) )
    	{
    		echo"<form method='post'>";
    		
    		$result = $Reg_Connect->Query("SELECT * FROM ads WHERE ad_author = $author ORDER BY ad_time DESC");
    		
    		while( $row = mysqli_fetch_array($result) ) 
    		{
    		  echo "<div class='del-Ad-Main-Row'><input type='checkbox' name='{$row['ad_id']}' value='{$row['ad_caption']}'> {$row['ad_caption']} <br></div>";
    		}  
    		
    		echo "<p><input class='del-Ad-Main-Button' type='submit' name='button' value='Удалить'></p> </div>";
    	}
    else
    {
        echo "<div class='del-Ad-Main-Info'> Не найдено объявлений для удаления. </div>";
    }
    
    $Reg_Connect->Close();
    
    ?>
    
    
    <!-- УДАЛЕНИЕ ОБЪЯВЛЕНИЙ -->
    <?php
    if( isset($_POST['button']) )
    {
    	$Reg_Connect = new ConnectDB();
    	
    	// проверка на отметку флажка
    	$result = $Reg_Connect->Query("SELECT * FROM ads");
    		
    		while( $row = mysqli_fetch_array($result) ) 
    		{
    		  if( isset($_POST[$row[ad_id]]) )
    		  {
    		      $Reg_Connect->Query("DELETE FROM ads WHERE ad_id='{$row[ad_id]}'");
    		  }
    		}  
        
        $Reg_Connect->Close();
        
        // перезагрузка страницы
        echo "<script type='text/javascript'>";
        echo "location.replace('del_ad.php')";
        echo "</script>";
    } 
    ?>

</div>

<!--**********************************-->


<!-- FOOTER СТРАНИЦЫ -->
<div class="footer">
    <div class="footer-Center"> Powered by ArtReeX </div>
</div>

<!--**********************************--> 

</html>		