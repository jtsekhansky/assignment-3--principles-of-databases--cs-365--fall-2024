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

function insertTupleBasedOnWords($username, $password, $firstname, $lastname, $email, $website_name, $website_URL, $comment){
    $return = "";
    try{
      include_once "config.php";

      $connection = new PDO ("mysql:host=" .DBHOST."; dbname=" .DBNAME, DBUSER, DBPASS);

      $SQLstatement="set block_encryption_mode = 'aes-256-cbc'";
      $connection -> exec($SQLstatement);

      $SQLstatement='
      SELECT logins.user_name AS username
      FROM logins
      WHERE
      logins.user_name = "'.$username.'"';

     $statement = $connection -> prepare($SQLstatement);
     $statement -> execute();

     if(count($statement -> fetchAll()) != 0){
        return "name $username already exists";
     }

     $SQLstatement='
      SELECT logins.website_name AS websitename
      FROM logins
      WHERE
      logins.website_name = "'.$website_name.'"';

     $statement = $connection -> prepare($SQLstatement);
     $statement -> execute();

     if(count($statement -> fetchAll()) != 0){
        return "Website $website_name already exists";
     }

     $SQLstatement='
      INSERT INTO websites
      (website_name, website_url)
      VALUES
      ("'.$website_name.'", "'.$website_URL.'")
      ';

      $connection -> query($SQLstatement);

     $SQLstatement='
      INSERT INTO users
      (user_name, first_name, last_name, email)
      VALUES
      ("'.$username.'", "'.$firstname.'", "'.$lastname.'", "'.$email.'")
      ';

     $connection -> query($SQLstatement);

     $SQLstatement='
      INSERT INTO logins
      (user_name, website_name, password, comment, update_time)
      VALUES
      ("'.$username.'", "'.$website_name.'", aes_encrypt('.
        "'".$password."'".',UNHEX(SHA2("'.KEY_STR.'", 256)), '
        .INIT_VECTOR
        .'),"'
        .$comment.'",  CURRENT_TIMESTAMP())
      ';

     $connection -> query($SQLstatement);
     return "Insertion Successfull";
    }
    catch(PDOException $e){
      $return = "connection failed:".$e -> getMessage();
    }
    return $return;
}

function deleteTuple($attr, $dltword, $exact){
    $return = "";
    try{
      include_once "config.php";

      $connection = new PDO ("mysql:host=" .DBHOST."; dbname=" .DBNAME, DBUSER, DBPASS);
      $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      $SQLstatement='SET SQL_SAFE_UPDATES=0';

      $connection -> exec($SQLstatement);

      $SQLstatement="set block_encryption_mode = 'aes-256-cbc'";
      $connection -> exec($SQLstatement);

      $SQLstatement='DELETE logins, users, websites
        FROM logins, users, websites
        WHERE users.user_name=logins.user_name
        AND logins.website_name=websites.website_name
        AND ';
      if($attr!="logins.password"){
        if($exact=="no"){
            $SQLstatement=$SQLstatement.$attr.' LIKE "%'.$dltword.'%" ';
        }else{
            $SQLstatement=$SQLstatement.$attr.'="'.$dltword.'" ';
        }
      }else{
        if($exact=="no"){
            $SQLstatement=$SQLstatement.
            'CAST(AES_DECRYPT(logins.password, '.
            'UNHEX(SHA2('."'".KEY_STR."'".', 256))'
            .", ".INIT_VECTOR.') AS CHAR)'
            .' LIKE "%'.$dltword.'%" ';
        }else{
            $SQLstatement=$SQLstatement.
            'CAST(AES_DECRYPT(logins.password, '.
            'UNHEX(SHA2('."'".KEY_STR."'".', 256))'
            .", ".INIT_VECTOR.') AS CHAR)'
            .'="'.$dltword.'" ';
        }
      }
     $connection -> exec($SQLstatement);

     $return = "Delete is done";
    }
    catch(PDOException $e){
      $return = $e -> getMessage();
    }
    return $return;
}

function updateTuple($attr, $ptnword, $exact, $rattr, $repword){
    $return = "";

    try{
        include_once "config.php";

        $connection = new PDO ("mysql:host=" .DBHOST."; dbname=" .DBNAME, DBUSER, DBPASS);
        $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $SQLstatement='SET SQL_SAFE_UPDATES=0';

        $connection -> exec($SQLstatement);

        $SQLstatement="set block_encryption_mode = 'aes-256-cbc'";
        $connection -> exec($SQLstatement);

        $SQLstatement='UPDATE logins, users, websites
        SET
        '.$rattr.'=';
        if($rattr!="logins.password"){
            $SQLstatement=$SQLstatement.'"'.$repword.'" ';
            if($rattr=="logins.user_name"){
                $SQLstatement=$SQLstatement.', users.user_name='.'"'.$repword.'" ';
            }
            if($rattr=="logins.website_name"){
                $SQLstatement=$SQLstatement.', websites.website_name='.'"'.$repword.'" ';
            }
        }else{
            $SQLstatement=$SQLstatement.
            'AES_ENCRYPT('."'".$repword."'".', '.
            'UNHEX(SHA2('."'".KEY_STR."'".', 256))'
            .", ".INIT_VECTOR.') ';
        }
        $SQLstatement=$SQLstatement.' WHERE
        users.user_name=logins.user_name
        AND logins.website_name=websites.website_name
        AND ';
        if($attr!="logins.password"){
            if($exact=="no"){
                $SQLstatement=$SQLstatement.$attr.' LIKE "%'.$ptnword.'%" ';
            }else{
                $SQLstatement=$SQLstatement.$attr.'="'.$ptnword.'" ';
            }
          }else{
            if($exact=="no"){
                $SQLstatement=$SQLstatement.
                'CAST(AES_DECRYPT(logins.password, '.
                'UNHEX(SHA2('."'".KEY_STR."'".', 256))'
                .", ".INIT_VECTOR.') AS CHAR)'
                .' LIKE "%'.$ptnword.'%" ';
            }else{
                $SQLstatement=$SQLstatement.
                'CAST(AES_DECRYPT(logins.password, '.
                'UNHEX(SHA2('."'".KEY_STR."'".', 256))'
                .", ".INIT_VECTOR.') AS CHAR)'
                .'="'.$ptnword.'" ';
            }
          }
         $connection -> exec($SQLstatement);
         //$return=$SQLstatement;

    }
    catch(PDOException $e){
        $return = $e -> getMessage();
      }

    return $return;
}

