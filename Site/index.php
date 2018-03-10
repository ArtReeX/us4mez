<?php 

// директивы

/* параметры отладки */
ini_set('display_errors','Off'); 

include ("mysql.php");

session_start();

/*
// продление авторизации при отсутствие сессии но наличия куков 
if ( empty($_SESSION['login']) )
{
    // проверяем правильность логина и пароля
    $Reg_Connect = new ConnectDB();
    
    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_login = '$login' AND author_password = '$password'") ) )
    {
        // удаление старой сессии
        unset($_SESSION['login']);

        // запись новых данных в сессию
        $_SESSION['login'] = $login;

        // продление куки
        setcookie("login", $login, time()+3600);
        setcookie("password", $password, time()+3600);

    }
    else
    {
        // сброс куков
        setcookie("login", "");
        setcookie("password", "");
    }

    $Reg_Connect->Close();
}*/
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


<!----- ПЕРВАЯ РАБОТА С БД ----->
<?php

$Reg_Connect = new ConnectDB();


/********** СОЗДАНИЕ ТАБЛИЦ *********/

/* авторы */
$Reg_Connect->AddTable( "authors", "author_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, author_login VARCHAR(100) NOT NULL, author_password VARCHAR(100) NOT NULL, author_name VARCHAR(100) NOT NULL, author_city VARCHAR(100) NOT NULL, author_phone VARCHAR(100) NOT NULL, author_mail VARCHAR(100) NOT NULL" );

/* типы объявлений */
$Reg_Connect->AddTable( "types", "type_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, type_name VARCHAR(100) NOT NULL" );

/* категории объявлений */
$Reg_Connect->AddTable( "categories", "category_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, category_name VARCHAR(100) NOT NULL, category_info TEXT NOT NULL" );

/* объявления */
$Reg_Connect->AddTable( "ads", "ad_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, ad_caption VARCHAR(100) NOT NULL, ad_text TEXT NOT NULL, ad_photo VARCHAR(100), ad_type INT NOT NULL, ad_category INT NOT NULL, ad_time timestamp DEFAULT CURRENT_TIMESTAMP, ad_author INT NOT NULL, FOREIGN KEY (ad_author) REFERENCES authors(author_id), FOREIGN KEY (ad_type) REFERENCES types(type_id), FOREIGN KEY (ad_category) REFERENCES categories(category_id)" );

    
$Reg_Connect->Close();

?>
<!--**********************************-->


<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************--> 


<!-- HEADER САЙТА -->
<div class="header">
	<a href="index.php"><div class="header-Left"> US4MEZ </div></a>
	<div class="header-Center"> ДОСКА ОБЪЯВЛЕНИЙ </div>
	<div class="header-Right"> <a href="panel.php"> <img src="/style/images/login.png" alt="" /> </a> </div>
</div>


<!-- MAIN СТРАНИЦЫ -->
<div class="main">

    <!-- БЛОК КАТЕГОРИЙ -->
    <div class="categories">
    	<div class="categories-Caption"> КАТЕГОРИИ </div>
    	

    	<?php echo "<div class='categories-Name'><a href='index.php'> Все категории </a></div>"; ?>
    
    	<!----- загрузка категорий ----->
    	<?php
    	$Reg_Connect = new ConnectDB();
    
    	if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM categories") ) )
    	{
    		$result = $Reg_Connect->Query("SELECT * FROM categories");
    		
    		while( $row = mysqli_fetch_array($result) ) 
    		{
    		  echo "<div class='categories-Name'>";
    		  
        		  echo "<a href='index.php?category_id={$row['category_id']}'>";
        		  
            		  echo "<span class='tooltip'>";
            		  
            		  echo ( $row['category_name'] );
            
            		  echo "<em>";
            
                      /* подсказка */
            
            		  echo ( $row['category_info'] );
            		  
            		  echo "<i></i></em></span>";
            	
            	  echo "</a>";
            	
    		  echo "</div>";
    		}  
    	}
    	else
    	{
    		echo "<div class='categories-Name'> У вас нет добавленных категорий. </div>";
    	}
    
    	$Reg_Connect->Close();
    	?>
    
    </div>




    <!-- БЛОК ОБЪЯВЛЕНИЙ -->
    <div class="ads">
        

        <!----- загрузка обьявлений ----->
    	<?php
    	$Reg_Connect = new ConnectDB();
    

    		
        /* ОПРЕДЕЛЕНИЕ НАЛИЧИЯ ПАРАМЕТРОВ ОБЪЯВЛЕНИЙ */
        if( !isset($_GET['category_id']) )
        {
            /* если параметры не заданы */
            $result = $Reg_Connect->Query("SELECT * FROM ads ORDER BY ad_time DESC");
            
            if( !mysqli_num_rows( $result ) )
            {
                echo "<div class='ads-Row'> Нет объявлений. </div>";
            }
        }  
        else
        {
            /* если параметры заданы */
            $result = $Reg_Connect->Query("SELECT * FROM ads WHERE ad_category={$_GET['category_id']} ORDER BY ad_time DESC");
            
            if( !mysqli_num_rows( $result ) )
            {
                echo "<div class='ads-Row'> Нет объявлений. </div>";
            }
        }
        

    		
		while( $row = mysqli_fetch_array($result) ) 
		{
		  echo "<div class='ads-Row'>";

            /* идентификатор объявления */
            $ad_id = $row['ad_id'];
            
            echo "<a href='ad.php?ad_id={$ad_id}'>";
            
                /* информационная (заголовочная) часть */
                /* -----------------------------------------------------*/
                echo "<div class='ads-Row-Header'>";
    		  
                    /* название обьявления */
                    $caption = $row['ad_caption'];
        		    
        		    /* создание ссылки на полную статью */
        		    echo "<div class='ads-Row-Header-Caption'>$caption</div>"; 
        		    
        		    
        		    /* тип объявления */
                    $type_id = $row['ad_type'];
        
                    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM types WHERE type_id = $type_id") ) )
                    { 
                        $select = $Reg_Connect->Query("SELECT * FROM types WHERE type_id = $type_id");
    
                        $type = mysqli_fetch_array($select)['type_name']; 
                        
                        echo "<div class='ads-Row-Header-Type'>{$type}</div>"; 
                    }
        		    
        		    
                    /* категория объявления */
                    $category_id = $row['ad_category'];

                    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM categories WHERE category_id = $category_id") ) )
                    { 
                        $select = $Reg_Connect->Query("SELECT * FROM categories WHERE category_id = $category_id");

                        $category = mysqli_fetch_array($select)['category_name']; 
                        echo "<div class='ads-Row-Header-Category'>{$category}</div>";   
                    }


        		    /* время размещения обьявления */
                    $time = $row['ad_time'];
        		    echo "<div class='ads-Row-Header-Time'>{$time}</div>";


                    /* автор объявления */
                    $author_id = $row['ad_author'];

                    if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_id = $author_id") ) )
                    { 
                        $select = $Reg_Connect->Query("SELECT * FROM authors WHERE author_id = $author_id");

                        $author = mysqli_fetch_array($select)['author_login']; 
                        
                        echo "<div class='ads-Row-Subheader'>{$author}</div>"; 
                    }

                echo "</div>";
                /* -----------------------------------------------------*/


                /* основная часть */
                /* -----------------------------------------------------*/
                echo "<div class='ads-Row-Main'>";   		  
                    
                    /* фото обьявления */
                    $photo = $row['ad_photo'];
                    if( $photo != NULL && file_exists("files/ads_photos/" . $photo) )
                    {
                        echo "<div class='ads-Row-Main-Photo'><img src='files/ads_photos/{$photo}'></div>";    
                    }
                    else
                    {
                        echo "<div class='ads-Row-Main-Photo'><img src='style/images/nophoto.jpg'></div>"; 
                    }

                    /* текст обьявления */
                    $text = $row['ad_text'];
                    
                    $default_size = 150;
                    
                    if( strlen($text) > $default_size )
                    {
                        /* дополнение строки */
                        $text = substr( $row['ad_text'], 0, $default_size ) . "...";

                        echo "<div class='ads-Row-Main-Text'>{$text}</div>";
                    }
                    else 
                    {
                        echo "<div class='ads-Row-Main-Text'>{$text}</div>";
                    }
                
                echo "</a>";


            echo "</div>";
            /* -----------------------------------------------------*/
		    
		  
		  echo "</div>";
		}  
    	
    
    	$Reg_Connect->Close();
    	?>
    
    </div>
    
    <!-- БЛОК АВТОРОВ -->
    <div class="authors">
    	<div class="authors-Caption"> АВТОРЫ </div>
    	
    	<!----- загрузка авторов ----->
    	<?php
    	$Reg_Connect = new ConnectDB();
    
    	if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors") ) )
    	{
    		$result = $Reg_Connect->Query("SELECT * FROM authors");
    		
    		while( $row = mysqli_fetch_array($result) ) 
    		{
    		  echo "<div class='authors-Login'>";
    		  
    		  echo ( $row['author_login'] );
    		  
    		  echo "</div>";
    		}  
    	}
    	else
    	{
    		echo "<div class='authors-Login'> У вас нет добавленных авторов. </div>";
    	}
    
    	$Reg_Connect->Close();
    	?>
    
    </div>

</div>


<!-- FOOTER САЙТА -->
<div class="footer">
    <div class="footer-Center"> Powered by ArtReeX </div>
</div>

<!--**********************************--> 

</html>		