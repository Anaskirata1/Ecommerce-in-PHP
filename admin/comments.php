<?php
session_start();
$pageTitle = 'Comments';

if(isset($_SESSION['Username'])) {

    include 'init.php' ;

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 

    if($do == 'Manage'){

        $stmt = $con->prepare(" SELECT
                                    comments.* , items.Name AS ItemName, users.Username AS Member
                                FROM 
                                    comments
                                INNER JOIN
                                    items
                                ON
                                    items.Item_ID = comments.item_id
                                INNER JOIN 
                                    users
                                ON
                                    users.UserID = comments.user_id 
                                ORDER BY 
                                    c_id DESC               
                                 ");
        $stmt->execute();

        //assign to vairable 
        $rows = $stmt->fetchAll(); 

        if(!empty($rows)) {

        ?>


                 <h1 class= "text-center" > Manage Momment  </h1>
                
                <div class = "container">
                    <div class = "table-responsive"> 
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Comment</td>
                                <td>Item Name</td>
                                <td>User Name</td>
                                <td>Added date </td>
                                <td>Control</td>
                            </tr>

                            <?php // للوصول الى عناصر الجدول وطبعه داخل الجدول 
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" . $row['c_id'] . "</td>";
                                    echo "<td>" . $row['comment'] . "</td>";
                                    echo "<td>" . $row['ItemName'] . "</td>";
                                    echo "<td>" . $row['Member'] . "</td>";
                                    echo "<td>" . $row['comment_date'] ."</td>";
                                    echo "<td>
                                            <a href = 'comments.php?do=Edit&comid=" .$row['c_id'] . " 'class = 'btn btn-success' > <i class ='fa fa-edit'></i>Edit </a> 
                                            <a href = 'comments.php?do=Delete&comid=" .$row['c_id'] . " 'class = 'btn btn-danger confirm' > <i class ='fa fa-close'></i>Delete </a> " ;
                                            // for activate member who has RegStatus = 0 
                                            if($row['status'] == 0) {
                                                echo "<a href ='comments.php?do=Approve&comid=" .$row['c_id'] . " 'class = 'btn btn-info' > <i class ='fa fa-close'></i>Approve </a> ";


                                            }

                                    echo "</td>";
                                echo "</tr>";
                            
                            }
                            
                            ?>

                        </table>

                    </div>
                    
                </div>   
                <?php } else{
                           echo '<div class = "container">' ;
                           echo '<div class = "nice-message">There Is NO Record To Show</div>' ;
                           echo '</div>' ;
                             }
                
                ?>  
           
      <?php

    } elseif ($do == 'Edit') {
        
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?") ;

        $stmt->execute(array($comid)) ;

        $row = $stmt->fetch() ;

        $count = $stmt->rowCount() ;

        if($count > 0) { ?>

                <h1 class= "text-center" > Edit Comment </h1>
                <div class = "container">
                    <form class = "form-horizontal" action = "?do=Update" method = "POST">
                        <input type = "hidden" name = "comid" value= "<?php echo $comid ?>" >

                        <div class= "form-group form-group-lg">
                            <label class="col-sm-2 control-label">Comment</label>
                            <div class= "col-sm-10 col-md-4">
                            <textarea class = "form-control" name="comment" id="" cols="30" rows="10"><?php echo $row['comment'] ; ?></textarea>
                            </div>
                        </div>
                        
                        <div class= "form-group mt-2">
                            <div class= "col-sm-10 col-md-4">
                            <input type = "submit" value = "save" class ="btn btn-primary btn-block" />
                            </div>
                        </div>

                    </form>    


                </div>

<?php }else {  
                echo "<div class = 'container'>" ;

                $errorMsg = 'there is no sush id' ;

                redirectHome($errorMsg,5);

                echo "</div>" ;
            
        }

        
    } elseif ($do == 'Update') {

        echo "<h3 class = 'text-center'>Update Comment </h3>" ;

        echo "<div class = 'container'>" ;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $comid = $_POST['comid'] ;

            $comment = $_POST['comment'] ; 

            $stmt = $con->prepare(" UPDATE
                                            comments
                                        SET
                                            comment = ?
                                        WHERE 
                                            c_id = ?") ;

            $stmt->execute(array($comment , $comid)) ;

            $theMsg =   "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>' ;

            redirectHome($theMsg,"back" ) ;

        } else {
             $theMsg =  'sorry you cant Browes this page' ;

            redirectHome($theMsg,"back" ) ;
        }
        echo "</div>" ;
    } elseif ($do == 'Delete') {

        echo "<h3 class = 'text-center'>Delete Comment </h3>" ;
        echo "<div class = 'container'>" ;
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;
        $check = checkItem('c_id' , 'comments' , $comid) ;
        if($check > 0) {
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid") ;
            $stmt->bindParam(":zid" , $comid) ; 
            $stmt->execute() ;
            $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted </div>' ;
                    redirectHome($theMsg,'back') ;

        } else {
            $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
            redirectHome($theMsg) ;
        }
        echo "</div>" ;
        
    } elseif ($do == 'Approve') {
        echo "<h3 class = 'text-center'>Approve Comment </h3>" ;

        echo "<div class = 'container'>" ;

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

        $check = checkItem('c_id' , 'comments' , $comid) ;

        if($check > 0) {

            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?") ;

            $stmt->execute(array($comid)) ;

            
            $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updeated</div>' ;
                    redirectHome($theMsg,'back') ;

        } else {
            $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
            redirectHome($theMsg) ;
        }
        echo "</div>" ;
    }
    include $tpl . 'footer.php' ;
} else {
    header('location : index.php') ;
    exit();
}