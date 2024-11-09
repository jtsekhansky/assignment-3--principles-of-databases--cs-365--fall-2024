<?php
require "includes/helpers.php";
$connection_successful = checkConnection();
?>

<!DOCTYPE HTML>
<html lang = "en">
    <head>

    </head>
    <body>
        <header>
            <h1>Jacob Tsekhansky - Assignment 3</h1>
            <p><?php echo $connection_successful ?></p>
        </header>
    </body>
</html>
