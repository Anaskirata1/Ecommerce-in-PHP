<?php
session_start();

$pageTitle = 'Profile' ;

include "init.php";

if(isset($_SESSION['user'])) {

    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?") ;

    $getUser->execute(array($sessionUser)) ;

    $info = $getUser->fetch() ;

    $userid = $info['UserID'] ;
    
    
    


?>
<h1 class="text-center"><?php 
                                        echo 'Welcome ' .$sessionUser ;
                                           
 ; ?> </h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My information
            </div>
            <div class="panel-body">
                <ul class = "list-unstyled">
                        <li>
                            <i class = "fa fa-unlock fa-fw"></i>
                            <span> Name </span> : <?php echo $info['Username'] ; ?>
                        </li> 
                        <li> 
                        <i class = "fa fa-envelope-o fa-fw"></i>
                            <span> Email </span> : <?php echo $info['Email']  ; ?> 
                        </li> 
                        <li>
                        <i class = "fa fa-user fa-fw"></i>
                            <span> Full Name </span> : <?php echo $info['FullName']  ; ?>
                        </li> 
                        <li>
                        <i class = "fa fa-calendar fa-fw"></i> 
                            <span> Regester Date </span> : <?php echo $info['Date']  ; ?>
                        </li> 
                        <li>
                        <i class = "fa fa-tags fa-fw"></i> 
                            <span>Favoret Category</span> : 
                        </li> 
                </ul>
                <a href = "#" class = "btn btn-default">edit Information</a>
         

            </div>

        </div>
    </div>
</div>
<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My ads
            </div>
            <div class="panel-body">
            
        <?php
            if(!empty(getItems('Member_ID' , $info['UserID']))) {
                echo '<div class = "row">' ;
        foreach(getItems('Member_ID' , $info['UserID'],1) as $item){
            echo '<div class = "col-sm-6 col-md-3">' ;
                echo '<div class = "thumbnail item-box">' ;
                if($item['Approve'] == 0) { 
                    echo'<span class = "approve-status">Not Approve</span>'; 
                    } ;
                    echo '<span class = "price-tag">$'.$item['Price'].'</span>' ;
                    echo '<img class = "img-responsive" src = "avatar.png" alt = ""/>';
                    echo '<div class = "caption">' ;
                        echo '<h3><a href = "items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>' ;
                        echo '<p>'.$item['Description'].'</p>' ;
                        echo '<div class = "date">'.$item['Add_Date'].'</div>' ;


                    echo '</div>' ;

                echo '</div>' ;



            echo '</div>' ;
            }
            echo '</div>' ;
        } else {
            echo ' Sorry There Is No ads To Show, Create <a href ="newad.php">New Ad</a>' ;
        }
        ?>
    

            </div>

        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
             latest comments
            </div>
            <div class="panel-body">
               
            <?php
            $stmt = $con->prepare(" SELECT 
                                         comment
                                         
                                    FROM 
                                         comments
                                  
                                    WHERE 
                                         user_id = ? ") ;

            $stmt->execute(array($info['UserID'])) ;
            
            $comments =$stmt->fetchAll() ;

            if (! empty($comments)) {
                foreach($comments as $comment) {
                    echo '<p>'. $comment['comment'] . '</p>' ;
                }

            } else {
                echo 'there is no comments to show ' ;
            }



?>
            </div>

        </div>
    </div>
</div>
<?php } else{
    header('location: login.php') ;

    exit() ;
}

include $tpl . "footer.php" ;
