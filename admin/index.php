<?php
session_start();
$noNavbar = '';
$pageTitle = 'login';
if(isset($_SESSION['Username'])){
    header('location:dashboard.php');

}
include "init.php";



// chech if user coming from http post request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    echo $username;
    echo $password ;
    $hashedpass = sha1($password);
   
    // check if user exsit in database
    $stmt = $con->prepare(" SELECT 
                                UserID, Username , Password 
                            FROM          
                                 users
                            where
                                Username = ?
                            AND 
                                Password = ?
                            AND             
                                 GroupID = 1
                            LIMIT 1     ");
    $stmt->execute(array($username , $hashedpass ));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
   

    if($count > 0) {
       $_SESSION['Username'] = $username;
       $_SESSION['ID'] = $row['UserID'];
       header('location:dashboard.php');
       exit();
    
    }

}
?>

<form class="login" action = "<?php echo $_SERVER['PHP_SELF']  ?>" method = "POST">
    <h4>admin login </h4>
    <input class = "form-control"  type = "text" name = "user" placeholder = "Username" autucomplete = "off" />
    <input class = "form-control" type = "password" name = "pass" placeholder = "password" autucomplete = "new-password" />
    <input class = "btn btn-primary mm1" type = "submit" value = "login" />
</form>
<?php

include $tpl . "footer.php"
?>