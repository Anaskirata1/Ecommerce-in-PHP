<?php
    session_start();
    $pageTitle = "log in" ;

    if(isset($_SESSION['user'])){
        header('location: index.php');
    }

include 'init.php' ;

// cchrck if user comming from http psst request

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['login'])) {
    $user = $_POST['username'] ;
    $pass = $_POST['password'] ;
    $hashedPass = sha1($pass) ;

    $stmt = $con->prepare("SELECT UserID , Username , `Password` FROM users WhERE Username = ? AND `Password` = ?") ;

    $stmt->execute(array($user , $hashedPass)) ;

    $get = $stmt->fetch() ;

    $count = $stmt->rowCount() ;

    if($count > 0) {
        $_SESSION['user'] = $user ;
        
        $_SESSION['uid'] = $get['UserID'] ; // register userid
       

         header('location: index.php') ;
         exit();
     }
    } else{

        $formErrors = array();

        $username  = $_POST['username'];
        $password  = $_POST['password'];
        $password2 = $_POST['password2'];
        $email     = $_POST['email'];

        if(isset($username)){
            $filterdUser = filter_var($username ,FILTER_SANITIZE_STRING) ;
            if(strlen($filterdUser) < 4){
                $formErrors[] = 'User Name Must Be Larger Than 4 characters ' ;
            }
        }

        if(isset($password) && isset($password2) ){
            if(empty($password)){
                $formErrors[] = "Sorry Password Cant Be Empty" ;

            }
        

          if(sha1($password) !== sha1($password2)) {
              $formErrors[] = "Sorry Password Is Not Match" ;
          }
         
        }

        if(isset($email)){
            $filterdEmail = filter_var($email ,FILTER_SANITIZE_EMAIL) ;
            
            if(filter_var($filterdEmail , FILTER_VALIDATE_EMAIL) != true){

                $formErrors[] = 'This Email Is NOt Valid ' ;

            }
        }

        // check if there is no error procrd the user add

        if(empty($formErrors)) {
            $check = checkItem('Username' ,'users' ,$username) ;

            if($check == 1) {
                $formErrors[] = 'Sorry This User Is Exist ' ;
            } else{

                $stmt = $con->prepare("INSERT INTO 
                                                users(Username , `Password` , Email , RegStatus , `Date`)
                                                VALUES(:zuser , :zpass , :zmail ,0 , now()) ") ;

                $stmt->execute(array(
                    'zuser' => $username ,
                    'zpass' => sha1($password) ,
                    'zmail' => $email 
                )) ;
                $succesMsg = 'Congrats You Are Now Registerd User' ;
            }
        }
        
    }
}




?>

<div class="container login-page">
        <h1 class = "text-center">
            <span class = "selected" data-class = "login">Login</span> | <span data-class = "signup">Signup</span>
        </h1>
    <form class = "login" action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
       
       <div class="input-container">  <input class = "form-control" type="text" name = "username" autocomplete = "off" placeholder = "type your username" required > </div>
       <div class="input-container"> <input class = "form-control" type="password" name = "password" autocomplete = "nwe-password" placeholder = "type your password" required > </div>
        <input class = "btn btn-primary form-control" name = 'login' type="submit" value = "Log in" >

    </form>

    <form class = "signup"  action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
      <div class="input-container">  <input pattern =".{4,}" title = "Username Must Be More Than 4 Characters" class = "form-control" type="text" name = "username" autocomplete = "off" placeholder = "type your username" required > </div>

       <div class="input-container"> <input minlength = "4" class = "form-control" type="password" name = "password" autocomplete = "nwe-password" placeholder = "type your password" required > </div>

       <div class="input-container">   <input minlength = "4" class = "form-control" type="password" name = "password2" autocomplete = "nwe-password" placeholder = "repet your password" required   >  </div>

       <div class="input-container">  <input class = "form-control" type="email" name = "email"  placeholder = "type a valide email" required >   </div>

        <input class = "btn btn-primary form-control" name = 'signup' type="submit" value = "Sign Up" >

    </form>
    <div class="the-errors text-center">  
      <?php if(! empty($formErrors)) {
          foreach($formErrors as $error){
              echo $error .'<br>' ;
          }
      }
      if(isset($succesMsg)) {
          echo '<div class = "msg success">' .$succesMsg . '</div>' ;
      }
      ?>
    </div>


</div>







<?php include $tpl . 'footer.php' ; ?>