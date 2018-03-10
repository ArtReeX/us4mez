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
	<div class="header-Center"> ПАНЕЛЬ ДОБАВЛЕНИЯ ОБЪЯВЛЕНИЙ </div>
	<div class="header-Right"> <a href="panel.php"> <img src="/style/images/login.png" alt="" /> </a> </div>
</div>


<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************--> 

<div class="add-Ad-Main"> 

    <form action="add_ad.php" form enctype="multipart/form-data" method="post">
    
    <div class="add-Ad-Main-Row"> <p>Название объявления: <br> <input type="text" name="caption" /></p>  </div>
    
    <div class="add-Ad-Main-Row"> <p>Текст объявления: <br> </p><textarea name="text" cols="40" rows="10"></textarea>  </div>
    
    <div class="add-Ad-Main-Row"> <p>Фото: <br> <input type="hidden" name="20000" value="30000" /><input name="userfile" type="file" /></p> </div>
    
    
    <!-- ВЫВОД ВЫПАДАЮЩИХ СПИСКОВ -->
    <?php
    
    $Reg_Connect = new ConnectDB();
    
    
    /* ТИП ОБЪЯВЛЕНИЯ */
    echo "<div class='add-Ad-Main-Row'> <p>Тип объявления: </br></p>";
    echo "<select name='type'>";
    
    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM types") ) )
    	{
    		$result = $Reg_Connect->Query("SELECT * FROM types");
    		
    		while( $row = mysqli_fetch_array($result) ) 
    		{
    		  echo "<option value={$row['type_id']}> {$row['type_name']} </option>";
    		}  
    	}
	
    echo "</select> </div>";
    
    
    /* КАТЕГОРИЯ ОБЪЯВЛЕНИЯ */
    echo "<div class='add-Ad-Main-Row'> <p>Категория объявления: </br></p>";
    echo "<select name='category'>";
    
    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM categories") ) )
    	{
    		$result = $Reg_Connect->Query("SELECT * FROM categories");
    		
    		while( $row = mysqli_fetch_array($result) ) 
    		{
    		  echo "<option value={$row['category_id']}> {$row['category_name']} </option>";
    		}  
    	}
	
    echo "</select> </div>";
    
    $Reg_Connect->Close();
    
    ?>
    
    <p><input class="add-Ad-Main-Button" type="submit" name="button" value="Добавить"></p>
    </form>
    
    
    <?php
    if( isset($_POST['button']) )
    {
    	$Reg_Connect = new ConnectDB();
    	
    	$caption = $Reg_Connect->ProtectString($_POST['caption']);
    	$text =  $Reg_Connect->ProtectString($_POST['text']);
    	$type =  $Reg_Connect->ProtectString($_POST['type']);
    	$category =  $Reg_Connect->ProtectString($_POST['category']);
    	
        // получение ID пользователя
        if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '{$_SESSION['login']}' LIMIT 1") ) )
    	{
    		$result = $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '{$_SESSION['login']}' LIMIT 1");
    		
    		$author = mysqli_fetch_array($result)[0];
    	}
        
    
        if( !empty($caption) && !empty($text) && !empty($type) && !empty($category) && !empty($author) )
        {
        
            /* загрузка фото на сервер */
            if( isset($_FILES["userfile"]["name"]) )
        	{
        		/* директория загрузки изображений */
        		$directory = "files/ads_photos/";
        		/* максимальный размер изображения */
        		$max_photo_size = 20;
        
        		$allowedExts = array("jpg", "jpeg", "gif", "png"); 
        		$extension = end( explode(".", $_FILES["userfile"]["name"]) ); 
        		
        		if ((($_FILES["userfile"]["type"] == "image/gif") 
        		    || ($_FILES["userfile"]["type"] == "image/jpeg") 
        		    || ($_FILES["userfile"]["type"] == "image/png") 
        		    || ($_FILES["userfile"]["type"] == "image/pjpeg")) 
        		    && ($_FILES["userfile"]["size"] < $max_photo_size * 1024 * 1024) 
        		    && in_array($extension, $allowedExts)) 
        		{ 
        		    if ($_FILES["file"]["error"] > 0) 
        		    { 
        		        echo "<div class='add-Ad-Main-Info'> Не удалось прикрепить фото. </div></a>"; 
        		    } 
        		    else 
        		    { 
        	            /* создание уникального имени файла при загрузке на сервер */
        	            $photo = md5( microtime() . rand(0, 9999) ) . '.' . $extension;
        
        	            move_uploaded_file( $_FILES["userfile"]["tmp_name"], $directory . $photo ); 
        		    } 
        		} 
            	else 
            	{ 
            	    echo "<div class='add-Ad-Main-Info'> Не удалось прикрепить фото. </div></a>"; 
            	} 
        	}
            
            
            /* добавление объявления */
            if( $Reg_Connect->WriteToTable("ads","ad_caption,ad_text,ad_photo,ad_type,ad_category,ad_author", "'{$caption}', '{$text}', '{$photo}', '{$type}', '{$category}', '{$author}'") )
            {
                echo "<div class='add-Ad-Main-Info'> Объявление успешно добавлено. </div></a>";    
            }
            else
            {
                echo "<div class='add-Ad-Main-Info'> Не удалось добавить объявление. </div></a>";    
            }
        }
        else
        {
            echo "<div class='add-Ad-Main-Info'> Заполнены не все поля. </div></a>";      
        }
        
        $Reg_Connect->Close();
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