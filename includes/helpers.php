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
      SELECT logins.user_name AS username,
      logins.website_name AS websitename,
      logins.comment AS comment,
      websites.website_url AS url,
      users.first_name AS firstname,
      users.last_name AS lastname,
      users.email AS email
      FROM logins, users, websites
      WHERE
      logins.website_name = websites.website_name AND
      logins.user_name = users.user_name AND
      logins.user_name IN
      (SELECT DISTINCT logins.user_name FROM logins WHERE
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
      websites.website_url LIKE '%".$word."%')
      ";

     $statement = $connection -> prepare($SQLstatement);
     $statement -> execute();

     if(count($statement -> fetchAll()) != 0){
        foreach ($connection -> query($SQLstatement) as $r){
            $return = $return .
              "<tr>".
              "<td>". $r['username'] . "</td>".
              "<td>". $r['firstname'] . "</td>".
              "<td>". $r['lastname'] . "</td>".
              "<td>". $r['email'] . "</td>".
              "<td>". $r['websitename'] . "</td>".
              "<td>". $r['url'] . "</td>".
              "<td>". $r['comment'] . "</td>".
              "</tr>\n";
        }
    }
     }

    catch(PDOException $e){
      $return = "connection failed:".$e -> getMessage();
    }
    return $return;
}

