<?php

// 1) manege members page 
// 2) you can edit | ADD | delete members from here 

session_start();
$pageTitle = 'Members';



if(isset($_SESSION['Username'])){
    include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // start manage page 
        if($do == 'Manage'){
            $query = " ";
            if(isset($_GET['page']) && $_GET['page'] = 'Pending'){
                $query = 'AND RegStatus = 0 ' ;

            }

         

            // select all users expect admin

            $stmt = $con->prepare("SELECT * FROM users where GroupID != 1 $query ORDER BY UserID DESC");
            $stmt->execute();

            //assign to vairable 
            $rows = $stmt->fetchAll();
            if(! empty($rows)){

            
            ?> 
        <!-- manage members page  -->
         <h1 class= "text-center" > Manage Members  </h1>
        
        <div class = "container">
            <div class = "table-responsive"> 
                <table class="main-table manage-member text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>#Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registerd date </td>
                        <td>Control</td>
                    </tr>

                    <?php // للوصول الى عناصر الجدول وطبعه داخل الجدول 
                    foreach($rows as $row){
                        echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>" ;
                            if(empty($row['avatar'])){
                                echo 'no image' ; 
                            } else {
                             echo "<img src = 'uplods/avatars/" . $row['avatar'] . "'alt = ''/>";}
                            echo "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] ."</td>";
                            echo "<td>
                                    <a href = 'members.php?do=Edit&userid=" .$row['UserID'] . " 'class = 'btn btn-success' > <i class ='fa fa-edit'></i>Edit </a> 
                                    <a href = 'members.php?do=Delete&userid=" .$row['UserID'] . " 'class = 'btn btn-danger confirm' > <i class ='fa fa-close'></i>Delete </a> " ;
                                    // for activate member who has RegStatus = 0 
                                    if($row['RegStatus'] == 0) {
                                        echo "<a href ='members.php?do=Activate&userid=" .$row['UserID'] . " 'class = 'btn btn-info' > <i class ='fa fa-close'></i>Activate </a> ";


                                    }

                               echo "</td>";
                        echo "</tr>";
                       
                    }
                    
                    ?>

                </table>

            </div>
             <a href = 'members.php?do=Add' class = "btn btn-primary "> <i class="fa fa-plus"></i> add member </a> 
             <a href = 'http://localhost/ecommerce/admin/dashboard.php' class = "btn btn-primary  "> <i class="fa fa-plus"></i> dashboard </a> 
        </div>     
        <?php } else {
            echo '<div class = "container">' ;
                echo '<div class = "nice-message">There Is NO Record To Show</div>' ;
                echo '<a href = "members.php?do=Add" class = "btn btn-primary "> <i class="fa fa-plus"></i> add member </a> ';
            echo '</div>' ;
        } ?>
           
      <?php  } elseif($do == 'Add') { ?>

            <!-- add member page -->

            <h1 class= "text-center" > add page </h1>
                <div class = "container">
                    <form class = "form-horizontal" action = "?do=Insert" method = "POST" enctype="multipart/form-data">
                        


                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Username </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "text" name = "username" class ="form-control"  autocomplete= "off" required = "required" placeholder = "username to login into shop" />
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Password </label>
                            <div class= "col-sm-10 col-md-4 rel">
                            
                            <input type = "Password" class= "pass" name = "password" class ="form-control" autocomplete= "new-password" placeholder = "password must be complex" required = "required"/>
                            <i class= "show-pass fa fa-eye"></i>
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Email </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "email" name = "email" class ="form-control"  required = "required" placeholder = "email must be valid" />
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Full Name </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "text" name = "full" class ="form-control"  required = "required" placeholder = "your profile name"/>
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> User Image </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "file" name = "avatar" class ="form-control"  required = "required"/>
                            </div>
                        </div>
                        <div class= "form-group mt-2">
                        
                            <div class= "col-sm-10 col-md-4">
                            <input type = "submit" value = "add member" class ="btn btn-primary btn-block" />
                            </div>
                        </div>

                    </form>    


                </div>


      <?php 
    } elseif($do == 'Insert') {
        // insert members page
      

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo  "<h1 class= 'text-center' >Insert Member </h1>" ; 
            echo "<div class = 'container'>" ;

            $avatarName = $_FILES['avatar']['name'] ;
            $avatarSize = $_FILES['avatar']['size'] ;
            $avatarTmp  = $_FILES['avatar']['tmp_name'] ;
            $avatarType = $_FILES['avatar']['type'] ;

            $avatarAllowedExtention = array("jpeg" , "jpg" , "png" , "gif") ;
            $avatarExtention = strtolower(end(explode('.', $avatarName)));

          
            

            

            $user   = $_POST['username'] ;
            $pass   = sha1($_POST['password'] ) ;
            $email  = $_POST['email'] ;
            $name   = $_POST['full'] ;
            

            $formErrors = array();
            if(strlen($user) < 3 ) {
                $formErrors[] = 'user name cant be less than <strong>2 charecters</strong>';
            }
            if(strlen($user) > 20) {
                $formErrors[] = ' Username cant be more than<srrong> 20 charecaters</strong>  ';

            }
            if(empty($user)){
                $formErrors[] = 'Username cant be <strong> empty </strong>  ';
                
            } ; 
            if(empty($pass)){
                $formErrors[] = 'Password cant be <strong> empty </strong>  ';
                
            } ; 
            if(empty($name )){
                $formErrors[] = '  Fullnameame cant be <strong> empty </strong> ';
               
            } ; 
            if(empty($email)){
                $formErrors[] = 'Email cant be <strong> empty </strong>';
         
            } ; 
            foreach($formErrors as $error){
                echo '<div class = "alert alert-danger" >' . $error . '</div>';
            }
            if(! empty($avatarName) && ! in_array($avatarExtention , $avatarAllowedExtention )) {
                $formErrors[] = 'Good This Extention Is  <strong> Allowed </strong>';
            } ;
            if(empty($avatarName)) {
                $formErrors[] = 'Avata Is  <strong> Required </strong>';
            }

            if(empty($formErrors)) {

                $avatar = rand(0,100000) . '_' . $avatarName ;

                move_uploaded_file($avatarTmp , "uplods\avatars\\".$avatar) ;

               $check = checkItem("Username", "users", $user) ;
               if($check == 1){
                   $theMsg = '<div class = "alert alert-danger">sorry this user is exist</div>' ;
                   redirectHome($theMsg,"back",5 ) ;

               } else {

                    $stmt = $con->prepare("INSERT INTO 
                                            users(Username , Password , Email , FullName,RegStatus,Date, avatar)
                                            VALUES(:zuser , :zpass , :zmail , :zname,0, now(),:zavatar)") ;

                    $stmt->execute(array(
                        'zuser' => $user  ,
                        'zpass' => $pass ,
                        'zmail' => $email  ,
                        'zname' => $name  ,
                        'zavatar' => $avatar  

                    ));       
                    $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updated1 </div>' ; 

                    redirectHome($theMsg,"back" ) ;
                }                

            } 
               
        }  else {
            echo "<div class = 'container'">

            $theMsg = '<div = "alert alert-danger">sorru you cant Browse this page</div>' ;
            redirectHome($theMsg , "back");
            
        }

        echo "</div>";
 }
    elseif ($do == 'Edit') {     // edit page 
        
        // chick if GET REQOUST userid is numeric and Get the integer value of it
        $userid =   isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0 ;
        // echo $userid ;

        $stmt = $con->prepare(" SELECT  * FROM users where UserID = ? LIMIT 1 ");

        $stmt->execute(array($userid ));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($stmt->rowCount() > 0 ) {  ?> 

                <h1 class= "text-center" > Edit Members </h1>
                <div class = "container">
                    <form class = "form-horizontal" action = "?do=Update" method = "POST">
                        <input type = "hidden" name = "userid" value= "<?php echo $userid ?>" >


                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Username </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "text" name = "username" class ="form-control" value = "<?php echo $row['Username']?>"  autocomplete= "off" required = "required" />
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Password </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "hidden" name = "oldpassword" value = "<?php echo $row['Password'] ?>"/>
                            <input type = "Password" name = "newpassword" class ="form-control" autocomplete= "new-password" placeholder = "leave blank if you dont want to change"/>
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Email </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "email" name = "email" class ="form-control"  value = "<?php echo $row['Email']?>" required = "required" />
                            </div>
                        </div>
                        <div class= "form-group">
                            <label class="col-sm-2 control-label"> Full Name </label>
                            <div class= "col-sm-10 col-md-4">
                            <input type = "text" name = "full" class ="form-control"  value = "<?php echo $row['FullName']?>"  required = "required"/>
                            </div>
                        </div>
                        <div class= "form-group mt-2">
                        
                            <div class= "col-sm-10 col-md-4">
                            <input type = "submit" value = "save" class ="btn btn-primary btn-block" />
                            </div>
                        </div>

                    </form>    


                </div>

 <?php  
        } else {
            $errorMsg = 'there is no sush id' ;
            redirectHome($errorMsg,5);
        }
      } elseif($do == 'Update') {
        echo  "<h1 class= 'text-center' >Update Page  </h1>" ; 
        echo "<div class = 'container'>" ;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // get variables from the form
            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];

            // password trick
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;

            // validate

            $formErrors = array();
            if(strlen($user) < 3) {
                $formErrors[] = 'Username cant be less than <srrong> 4 charecaters</strong> ';

            }
            if(strlen($user) > 20) {
                $formErrors[] = ' Username cant be more than<srrong> 20 charecaters</strong>';

            }
            if(empty($user)){
                $formErrors[] = ' Username cant be <strong> empty </strong>';
                
            } ; 
            if(empty($name )){
                $formErrors[] = 'Fullnameame cant be <strong> empty </strong>  ';
               
            } ; 
            if(empty($email)){
                $formErrors[] = ' Email cant be <strong> empty </strong> ';
         
            } ; 
            
            // loop into errors array and eho it 

            foreach($formErrors as $error) {
                echo '<div class = "alert alert-danger" > ' . $error . '</div>';
            }

            // check if there is no error proceed the update operation 


            if( empty($formErrors)) {
                    // stmt2  من اجل القدره على تعديل الاسم نفسو
                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ? ") ;

                $stmt2->execute(array($user,$id));

                $count = $stmt2->rowCount();

                if($count == 1){
                    $theMsg =   "<div class ='alert alert-danger'>sorry this user is exist </div>" ;
        
                    redirectHome($theMsg,"back" ) ;
                } else {
                    $stmt = $con->prepare(" UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user , $email , $name ,$pass , $id ));
                   
                    $theMsg =   "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>' ;
                    redirectHome($theMsg,"back" ) ;
                   
                }

          
            // UPDATE database with this info 
            
             }

        } else {
            $theMsg =  'sorry you cant Browes this page' ;
            redirectHome($theMsg,"back" ) ;
        }

        echo "</div>";

        /// delete page
      } elseif($do == 'Delete'){
        echo  "<h1 class= 'text-center' >Delete Members Page  </h1>" ; 
        echo "<div class = 'container'>" ;
                $userid =   isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0 ;
                // echo $userid ;
                $check = checkItem('userid', 'users', $userid) ;
                echo $check ;

                if($check > 0 ) {
                    // echo 'Good this id is exist' ;
                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

                    // to connect between ":zuser" & $userid
                    $stmt->bindparam(":zuser", $userid);
                    $stmt->execute();
                    $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted </div>' ;
                    redirectHome($theMsg , 'back') ;

                } else {
                    
                    $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
                    redirectHome($theMsg) ;
                    
                    
                }
        echo "</div>";
                // for activate users 
      } elseif($do ='Activate') {

          echo '<h1 class = "text-center">Aciivate Page</h1>' ; 

          echo '<div class ="container"> ';

          $userid =   isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0 ;
          
          // check if this user exsist in datapase 
          $check = checkItem('userid ', 'users' , $userid) ;

          if ($check > 0 ){

              $stmt = $con->prepare(" UPDATE users SET RegStatus = 1 WHERE UserID = ?") ;
              $stmt->execute(array($userid));
              // echo masseg and redirect 
              $theMsg = "<div class = 'alert alert-success'>". $stmt->rowCount() . "Record Updeted </div>" ;
              redirectHome($theMsg) ;


          } else {
            $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
            redirectHome($theMsg) ;
        }
          
         echo '</div>' ;

      }

    include $tpl . 'footer.php';
} else {
    header('location: index.php');
    exit();
        }
