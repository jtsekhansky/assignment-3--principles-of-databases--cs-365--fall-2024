<?php

function checkConnection(){
    $sucess = "connection to database successfull";
    try{
      include_once "config.php";

      $connection = new PDO ("mysql:host=" .DBHOST."; dbname=" .DBNAME, DBUSER, DBPASS);

      $success = 1;

    }
    catch(PDOException $e){
      $sucess = "connection failed:".$e -> getMessage();
    }
    return $sucess;
}
