<?php
require "includes/helpers.php";
$connection_successful = checkConnection();
$messagecolor = 'green';
if(strpos( $connection_successful, 'fail' ) !== false){
  $messagecolor = 'red';
}

if(isset($_POST['search'])){
    header("location: search.php");
}
?>

<!DOCTYPE HTML>
<html lang = "en">
    <head>
      <meta charset="utf-8">
      <title>CS 365 - Assignment 3</title>
      <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <header>
            <h1>Jacob Tsekhansky - Assignment 3</h1>
            <h2>Main Page</h2>
            <p>
                <font color=
                "<?php echo $messagecolor ?>"
                >
            <?php echo $connection_successful ?></font>
            </p>
        </header>
        <p>
          <form method="POST" action=''>
          <input type="submit" class=btn name="insert" value="Insert" onclick="insert()" />
          <input type="submit" class=btn name="search" value="Search" />
          <input type="submit" class=btn name="update" value="Update" onclick="update()" />
          <input type="submit" class=btn name="delete" value="Delete" onclick="delete()" />
          </form>
        </p>
    </body>
</html>
