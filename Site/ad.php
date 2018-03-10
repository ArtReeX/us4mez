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


<!-- СОДЕРЖИМОЕ СТРАНИЦЫ -->
<!--**********************************--> 


<!-- HEADER СТРАНИЦЫ -->
<div class="header">
	<a href="index.php"><div class="header-Left"> US4MEZ </div></a>
	<div class="header-Center"> ДОСКА ОБЪЯВЛЕНИЙ </div>
	<div class="header-Right"> <a href="panel.php"> <img src="/style/images/login.png" alt="" /> </a> </div>
</div>


<!-- MAIN СТРАНИЦЫ -->
<div class='ad-Page'>

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


    <!----- загрузка статьи ----->
    <?php
     
    $Reg_Connect = new ConnectDB();
    
    echo "<div class='ad-Main'>";
    
        /* идентификатор объявления */
        $ad_id = $_GET['ad_id'];
        
        /* проверка на правильность данных */
        if( !empty($ad_id) )
        {
            /* проверка на существование статьи */
            if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM ads WHERE ad_id = $ad_id") ) )
            {
            
                $result = $Reg_Connect->Query("SELECT * FROM ads WHERE ad_id = $ad_id");
        		
        		while( $row = mysqli_fetch_array($result) ) 
        		{
    
                    /* информационная (заголовочная) часть */
                    /* -----------------------------------------------------*/
                    
        		  
                        /* название обьявления */
                        $caption = $row['ad_caption'];
            		    
            		    echo "<div class='ad-Main-Header'>$caption</div>"; 
        		    
                        /* категория объявления */
                        $category_id = $row['ad_category'];
        
                        if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM categories WHERE category_id = $category_id") ) )
                        { 
                            $select = $Reg_Connect->Query("SELECT * FROM categories WHERE category_id = $category_id");
        
                            $category = mysqli_fetch_array($select)['category_name']; 
                            echo "<div class='ad-Main-Subheader-Category'>{$category}</div>";   
                        }
        
                        
                        /* тип объявления */
                        $type_id = $row['ad_type'];
        
                        if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM types WHERE type_id = $type_id") ) )
                        { 
                            $select = $Reg_Connect->Query("SELECT * FROM types WHERE type_id = $type_id");
        
                            $type = mysqli_fetch_array($select)['type_name']; 
                            
                            echo "<div class='ad-Main-Subheader-Type'>{$type}</div>"; 
                        }
                        
                        
                        /* автор объявления */
                        $author_id = $row['ad_author'];
        
                        if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_id = $author_id") ) )
                        { 
                            $select = $Reg_Connect->Query("SELECT * FROM authors WHERE author_id = $author_id");
        
                            $author = mysqli_fetch_array($select)['author_login']; 
                            
                            echo "<div class='ad-Main-Subheader-Author'>{$author}</div>"; 
                        }
                        

                        
                         /* время размещения обьявления */
                        $time = $row['ad_time'];
            		    echo "<div class='ad-Main-Subheader-Time'>{$time}</div>";
                    
                    /* -----------------------------------------------------*/
        
        
                    /* основная часть */
                    /* -----------------------------------------------------*/
                    echo "<div class='ad-Main-Content'>";   		  
                        
                        /* текст обьявления */
                        $text = $row['ad_text'];
                        
                        echo "<div class='ad-Main-Content-Text'>{$text}</div>";
                        
                        
                        /* информация об авторе */
                        $author_id = $row['ad_author'];
        
                        if( mysqli_num_rows( $Reg_Connect->Query("SELECT * FROM authors WHERE author_id = $author_id") ) )
                        { 
                            $select = $Reg_Connect->Query("SELECT * FROM authors WHERE author_id = $author_id");
        
                            echo "<div class='ad-Main-Content-Info'>";
                            
                                $data = mysqli_fetch_array($select);
                                
                                /* почта */
                                $mail = $data['author_mail']; 
                                echo "<div class='ad-Main-Content-Info-Mail'>{$mail}</div>";
                                
                                /* телефон */
                                $phone = $data['author_phone']; 
                                echo "<div class='ad-Main-Content-Info-Phone'>{$phone}</div>";
                                
                                /* почта */
                                $name = $data['author_name']; 
                                echo "<div class='ad-Main-Content-Info-Name'>{$name}</div>";
                            
                            echo "</div>";
                        }
                        
                        
                        /* фото обьявления */
                        $photo = $row['ad_photo'];
                        
                        if( $photo != NULL && file_exists("files/ads_photos/" . $photo) )
                        {
                            echo "<div class='ad-Main-Content-Photo'><img src='files/ads_photos/{$photo}'></div>";    
                        }
                     
                        
                    echo "</div>";
                    /* -----------------------------------------------------*/
        		} 
            }
            else
            {
                echo "<div class='ad-Main-Header'> Такого объявления не существует. </div>";   
            }
        }
        else
        {
            echo "<div class='ad-Main-Header'> Такой ссылки не существует. Похоже, вы попали сюда случайно. </div>";
        }
        
        
        echo "</div>";
    
    
    $Reg_Connect->Close();
        
    ?>
    
</div>


<!-- FOOTER СТРАНИЦЫ -->
<div class="footer">
    <div class="footer-Center"> Powered by ArtReeX </div>
</div>

<!--**********************************--> 

</html>		