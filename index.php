<?php
require "includes/helpers.php";
$connection_successful = checkConnection();
$messagecolor = 'green';
$actionscreen='';
$noresult='';
$searchresult='';
$insertresult='';
if(strpos( $connection_successful, 'fail' ) !== false){
  $messagecolor = 'red';
}

if(isset($_POST['search']) or isset($_POST['searchform']) or isset($_POST['rs'])){
    $searchresult='';
    $noresult='';
    if(isset($_POST['searchform'])){
        $searchresult=selectTupleBasedOnWord($_POST['searchword']);
        if($searchresult==""){
            $noresult='<br><br><p>
                    <font color="red">
                NO RESULT FOUND
                </font>
                </p>';
        }
    }
    $actionscreen='
    <form method="post" action="">
            <input type="submit" class=btn name="searchform" value="RunSearch" />
            <input type="submit" class=btn name="rs" value="Refresh" />
            <br><br>
                Enter search criteria and click on RunSEARCH button: <input type="text" name="searchword">
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
}elseif(isset($_POST['insert']) or isset($_POST['insertform'])){
    if(isset($_POST['insertform'])){
        if(empty($_POST['username']) or empty($_POST['website_name'])){
            $insertresult='<br><br><p>
                    <font color="red">
                Username and Website name must not be blank
                </font>
                </p>';
        }else{
            $insertresult=insertTupleBasedOnWords(
                $_POST['username'],
                $_POST['password'],
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['email'],
                $_POST['website_name'],
                $_POST['website_URL'],
                $_POST['comment']
            );
        }
    }
    $actionscreen='
    <form method="post" action="">
            <input type="submit" class=btn name="insertform" value="RunInsert" />

            <fieldset>
            <legend>Enter field values and click on RunINSERT button</legend>
            <div>
                <label for="username">Username: </label>
                <input type="text" id="username" name="username"/>
            </div>
            <div>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password"/>
            </div>
            <div>
                <label for="firstname">First Name: </label>
                <input type="text" id="firstname" name="firstname"/>
            </div>
            <div>
                <label for="lastname">Last Name: </label>
                <input type="text" id="lastname" name="lastname"/>
            </div>
            <div>
                <label for="email">Email: </label>
                <input type="text" id="email" name="email"/>
            </div>
            <div>
                <label for="website_name">Website Name: </label>
                <input type="text" id="website_name" name="website_name"/>
            </div>
            <div>
                <label for="website_URL">Website URL: </label>
                <input type="text" id="website_URL" name="website_URL"/>
            </div>
            <div>
                <label for="comment">Comment: </label>
                <textarea id="comment" rows="4" cols="40" name="comment"></textarea>
            </div>

           </fieldset>
          </form>
          '.$insertresult .'
          ';
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
