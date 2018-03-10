<?php

/* параметры отладки */
ini_set('display_errors','Off'); 

class ConnectDB
{
    

    /* CONFIG CONNECTION */
    var $host_db = 'localhost';
    var $user_db = 'root';
    var $password_db = '';
    var $name_db = 'us4mez';


    /* DEBUG */
    var $debug = false;
    
    /* GLOBAL VARIABLES */
    var $mysqli;


    /*----- DATABASE CONNECTION -----*/
    function ConnectDB( )
    {
        /* in the name of the database check */
        if( !empty($this->name_db) )
            $this->mysqli = new mysqli( $this->host_db, $this->user_db, $this->password_db, $this->name_db );
        else
            $this->mysqli = new mysqli( $this->host_db, $this->user_db, $this->password_db );

        if( $this->debug && $this->mysqli->connect_error )
            echo ( $this->mysqli->connect_error );
    }
    
    
    /*----- PROTECT STRING -----*/
    function ProtectString( $string )
    {
        $search  = ["<", ">", "/", "\\", "\"", "'"];
        $replace = [" ", " ", " ", " ", " ", " "];
        
        $string = str_replace($search, $replace, $string);
        
        return $string;
    }


    /*----- ADD DATABASE -----*/
    function AddDatabase( $name )
    {
        $result = $this->mysqli->query( "CREATE DATABASE IF NOT EXISTS $name" ); 

        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error );  
            
        return $result;
    }


    /*----- SELECT DATABASE -----*/
    function SelectDatabase( $name )
    {
        $result = $this->mysqli->select_db( $name ); 

        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error );
            
        return $result;   
    } 


    /*----- DELETE DATABASE -----*/
    function DeleteDatabase( $name )
    {
        $result = $this->mysqli->query( "DROP DATABASE IF EXISTS $name" );

        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error );
            
        return $result;           
    } 

    
    /*----- ADD TABLE -----*/
    function AddTable( $name, $param )
    {
        $result = $this->mysqli->query( "CREATE TABLE IF NOT EXISTS $name ( $param )" );

        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error );
            
        return $result;  
    } 


    /*----- DELETE TABLE -----*/
    function DeleteTable( $name )
    {
        $result = $this->mysqli->query( "DROP TABLE IF EXISTS $name" ); 

        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error );
            
        return $result;  
    }


    /*----- WRITE TO TABLE -----*/
    function WriteToTable( $table, $fields, $values )
    {
        $result = $this->mysqli->query( "INSERT INTO $table ($fields) VALUES ($values)" ); 
        
        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error );
            
        return $result;  
    }


    /*----- QUERY -----*/
    function Query( $query )
    {
        $result = $this->mysqli->query( $query );
        
        if( $this->debug && $this->mysqli->error )
            echo ( $this->mysqli->error ); 
            
        return $result;  
    } 


    /*----- CLOSE CONNECTION -----*/
    function Close( )
    {
        mysqli_close( $this->mysqli );
    } 
    
}
																				