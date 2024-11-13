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

function selectTupleBasedOnWord($word){
    $return = "";
    try{
      include_once "config.php";

      $connection = new PDO ("mysql:host=" .DBHOST."; dbname=" .DBNAME, DBUSER, DBPASS);

      $SQLstatement="
      SELECT DISTINCT logins.user_name FROM logins WHERE
      user_name LIKE '%".$word."%' OR
      website_name LIKE '%".$word."%' OR
      comment LIKE '%".$word."%'
      UNION
      SELECT DISTINCT users.user_name FROM users WHERE
      first_name LIKE '%".$word."%' OR
      last_name LIKE '%".$word."%' OR
      email LIKE '%".$word."%'
      UNION
      SELECT DISTINCT logins.user_name FROM logins, websites WHERE
      logins.website_name = websites.website_name AND
      websites.website_url LIKE '%".$word."%'
      ";

     $statement = $connection -> prepare($SQLstatement);
     $statement -> execute();

     $found = false;
     while ($row = $statement -> fetch()){
        if($field_value = $row["user_name"]){
            $found = true;
            $return = $return . $field_value . "\n";
        }
     }
     if($found = false){
        $return = "no results found";
     }
    }
    catch(PDOException $e){
      $return = "connection failed:".$e -> getMessage();
    }
    return $return;
}

