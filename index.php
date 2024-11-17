<?php
require "includes/helpers.php";
$connection_successful = checkConnection();
$messagecolor = 'green';
$actionscreen='';
$noresult='';
$searchresult='';
$insertresult='';
$deleteresult='';
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
}elseif(isset($_POST['delete']) or isset($_POST['deleteform'])){
    if(isset($_POST['deleteform'])){
        if(empty($_POST['attribute']) or empty($_POST['deleteword']) or empty($_POST['exact'])){
            $deleteresult='<br><br><p>
                    <font color="red">
                field selection and pattern must not be blank
                </font>
                </p>';
        }else{
            $deleteresult=deleteTuple(
                $_POST['attribute'], $_POST['deleteword'], $_POST['exact']
            );
        }


    }
    $actionscreen='
    <form method="post" action="">
            <input type="submit" class=btn name="deleteform" value="RunDelete" />
            <fieldset>
            <legend>Select field from list and enter pattern, then click on RunDELETE button</legend>
            <br>
            <div>
                <select name="attribute" id="attribute">
                    <option value="logins.user_name">Username</option>
                    <option value="logins.website_name">Website Name</option>
                    <option value="logins.password">Password</option>
                    <option value="logins.comment">Comment</option>
                    <option value="users.first_name">First Name</option>
                    <option value="users.last_name">Last Name</option>
                    <option value="users.email">Email</option>
                    <option value="websites.website_url">Website URL</option>
                </select>
            </div>
            <br><br>
            <div>
                <label for="deleteword">Enter search pattern for deletion: </label>
                <input type="text" id="deleteword" name="deleteword"/>
            </div>
            <br>
            <div>
                <p>Exact Match:</p>
                <input type="radio" id="yes" name="exact" value="yes">
                <label for="yes">yes</label>
                <br>
                <input type="radio" id="no" name="exact" value="no">
                <label for="no">no</label>
            </div>

            </fieldset>
    </form>
    '.$deleteresult.'
    ';
}
//else{}
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
          <input type="submit" class=btn name="insert" value="Insert" />
          <input type="submit" class=btn name="search" value="Search" />
          <input type="submit" class=btn name="update" value="Update" />
          <input type="submit" class=btn name="delete" value="Delete" />
          </form>
        </p>
        <p>
            <h3>Click on button to perform an operation</h3>
        </p>
        <?php echo $actionscreen ?>
    </body>
</html>
