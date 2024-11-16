<?php
require "includes/helpers.php";
$connection_successful = checkConnection();
$messagecolor = 'green';
$actionscreen='';
$noresult='';
$searchresult='';
if(strpos( $connection_successful, 'fail' ) !== false){
  $messagecolor = 'red';
}

if(isset($_POST['search']) or isset($_POST['searchform']) or isset($_POST['rs'])){
    if(isset($_POST['searchform'])){
        $searchresult=selectTupleBasedOnWord($_POST['searchword']);
        if($searchresult==""){
            $noresult='<br><br><p>
                    <font color="red">
                NO RESULT FOUND
                </font>
                </p>';
        }
    }else if(isset($_POST['rs'])){
        $searchresult='';
        $noresult='';
    }
    $actionscreen='
    <form method="post" action="">
            <input type="submit" class=btn name="searchform" value="RunSearch" />
            <input type="submit" class=btn name="rs" value="Refresh" />
            <br><br>
                Enter search criteria and click on SEARCH button: <input type="text" name="searchword">
                '.$noresult.'
          </form>
          <br><br>
          <h3>Search Results</h3>
          <br>
          <table class = "searchtbl">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Website Name</th>
                    <th>Website URL</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
            '.$searchresult.'
            </tbody>
          </table>
          ';
}elseif(isset($_POST['insert'])){
    header("location: includes/insert.php");
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
        <p>
            <h3>Click on button to perform an operation</h3>
        </p>
        <?php echo $actionscreen ?>
    </body>
</html>
