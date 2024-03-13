<?php
session_start();
if(isset($_SESSION['Username'])){
    $pageTitle = 'Dashboard';
include 'init.php';
$numUser = 6 ;

$latestUsers = getlatest('*', 'users','UserID' , $numUser) ;

$numItems = 5 ; 

$latestItem = getlatest('*', 'items','Item_ID' , $numItems) ;

$numComments = 4 ; // number of comments
?>
<!-- dashboard design  -->
    <div class="container home-stats text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
            <a href="members.php">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                    Total Members
                    <span><?php echo countItems('UserID','users')?></span>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
               <div class="info">
               pending Members
                    <span><a href="members.php?do=Manage&page=Pending">
                        <?php
                         echo checkItem("RegStatus", "users" , 0) ;              
                        ?>
                    </a></span>
               </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-item">
                    <i class="fa fa-tag"></i>
                  <div class="info">
                  Total Items
                   <span>
                         <a href="items.php">
                                <?php
                            echo countItems('Item_ID','items') ;
                                ?>
                         </a>       
                   </span>
                  </div>  
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class= "fa fa-comments"></i>
                   <div class="info">
                   Total Comments
                    <span><a href="comments.php">
                                <?php
                            echo countItems('c_id','comments') ;
                                ?>
                         </a>     </span>
                   </div>
                </div>
            </div>
        </div>

        
    </div>


    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <i class="fa fa-users"></i> Latest <?php echo $numUser; ?> Register Users
                        <span class = "toggle-info pull-right">
                            <i class = "fa fa-plus fa-lg" ></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled latest-user">
                        <?php
                        if(!empty($latestUsers)) {
                        foreach($latestUsers as $user){
                            echo '<li>'. $user['Username'] ;
                            echo '<a href="members.php?do=Edit&userid='. $user['UserID'] .' "> ';
                              echo '<span class = "btn btn-success pull-right">' ;
                                   echo  '<i class= "fa fa-edit"></i>Edit' ;
                                echo '</span>' ;
                                echo '</a>' ;
                                echo '</li>' ;
                        }
                    } else {
                              echo 'there is no record to show' ;
                    }
                        ?>
                        </ul>

                    </div>

                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                        <span class = "toggle-info pull-right">
                            <i class = "fa fa-plus fa-lg" ></i>
                        </span>
                    </div>
                    <div class="panel-body">
                    <ul class="list-unstyled latest-user">
                        <?php
                        if(! empty($latestItem)){
                            foreach($latestItem as $item){
                                echo '<li>'. $item['Name'] ;
                                if($item['Approve'] == 0){
                                echo '<a href="items.php?do=Approve&itemid='. $item['Item_ID'] .' "> ';
                                echo '<span class = "btn btn-info btn-sm pull-right">' ;
                                    echo  '<i class= "fa fa-check"></i>Approve' ;
                                    echo '</span>' ;
                                    echo '</a>' ; }
                                    echo '</li>' ;
                            }
                        } else {
                            echo 'there is no record to show' ;
                        }
                        ?>
                        </ul>

                    </div>

                </div>
            </div>
        </div>
        <!-- start row comment  -->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    
                    <div class="panel-heading">
                        <i class="fa fa-comment-o"></i> Latest <?php echo $numComments; ?> Comments
                        <span class = "toggle-info pull-right">
                            <i class = "fa fa-plus fa-lg" ></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <?php
                             $stmt = $con->prepare(" SELECT
                                                             comments.*, users.Username 
                                                     AS
                                                             Member
                                                     FROM 
                                                             comments
                        
                                                     INNER JOIN 
                                                             users
                                                     ON
                                                              users.UserID = comments.user_id 
                                                     ORDER BY 
                                                              c_id DESC                     
                                                     LIMIT  $numComments
                                                     ");
                            $stmt->execute();

                            //assign to vairable 
                            $comments = $stmt->fetchAll();

                            if (!empty($comments)) {

                                foreach($comments as $comment){
                                    echo '<div class = "comment-box">' ;
                                    echo '<span class = "member-n">' . $comment['Member'] . '</span>' ;
                                    echo '<p class = "member-c">' . $comment['comment'] . '</p>' ;
                                    echo '</div>' ;
                                }
                            } else {
                                echo 'there is no comments to show' ;
                            }
                        ?>
                      

                    </div>

                </div>
            </div>
        
        </div>
    </div>






<?php
include $tpl .'footer.php';


} else {
    include 'init.php';
    echo "<div class = 'container'>" ;
    $theMsg =  '<div class= "alert alert-danger"> you are not allow to view this page</div>';
    redirectHome($theMsg);
    echo "</div>";

    // header('location:index.php');
}


