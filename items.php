<?php
ob_start();
session_start();
$pageTitle ='Show Item' ;
include 'init.php' ;

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

$stmt = $con->prepare(" SELECT 
                                items.* , categories.Name AS category_name , users.Username
                        FROM
                                items 
                        INNER JOIN 
                                categories
                        ON
                                categories.ID = items.Cat_ID                 
                        INNER JOIN 
                                users
                        ON
                                users.UserID = items.Member_ID                 
                        WHERE 
                                Item_ID = ? 
                        AND
                                Approve = 1 ") ;

$stmt->execute(array($itemid)) ;

$count = $stmt->rowCount() ;

if($count > 0) {
    $item = $stmt->fetch() ; 

?>
<h1 class = "text-center"> <?php echo $item['Name']  ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
        <img class = "img-responsive center-block" src = "avatar.png" alt = ""/>
        </div>
        <div class="col-md-9 item-info">
            <h1><?php echo $item['Name'] ?></h1>
            <p><?php echo $item['Description'] ?></p>
            <ul class = "list-unstyled">
                <li>
                    <i class = "fa fa-calendar fa-fw"></i>    
                   <span>Added Date</span>: <?php echo $item['Add_Date'] ?>
                </li>
                <li>
                        <i class = "fa fa-money fa-fw"></i>  
                        <span> Price  </span>: <?php echo $item['Price'] ?>
                </li>
                <li>
                        <i class = "fa fa-building fa-fw"></i>    
                     <span> Made In </span>: <?php echo $item['Country'] ?>
                </li>
                <li>
                        <i class = "fa fa-tags fa-fw"></i>    
                    <span> Category  </span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?> </a>
                </li>
                <li>
                        <i class = "fa fa-user fa-fw"></i>  
                        <span> Added By  </span>: <a href="#"><?php echo $item['Username'] ?> </a>
                </li>
                <li>
                        <i class = "fa fa-user fa-fw"></i>  
                        <span> Tags  </span>: 
                        <?php
                        $alltags = explode(",", $item['tags']) ;
                        foreach($alltags as $tag){
                                $tag = str_replace(' ', '',$tag) ;
                                $tag = strtolower($tag);
                                if(!empty($tag)) {
                                echo "<a href = 'tags.php?name={$tag}'>". $tag . '</a>|' ;
                                }
                        }

                        ?>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    <!-- start add comment  -->
    <?php if(isset($_SESSION['user'])) { ?>
    <div class="row">
        <div class="col-md-3">
                
        </div>
        <div class="col-md-9">
                <div class="add-comment">
                   <h3>Add Your Comment</h3>
                   <form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid=' . $item['Item_ID'] ?>" method = "POST">
                        <textarea name="comment" id="" cols="30" rows="10" required></textarea>
                        <input type="submit" value = "Add Comment" class = "btn btn-primary">
                   </form>
                   <?php  if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING ) ;
                        $itemid = $item['Item_ID'] ;
                        $userid = $_SESSION['uid'] ;
                        

                        if(! empty($comment)) {
                                $stmt = $con->prepare("INSERT INTO
                                                                 comments(comment , `status` , comment_date , item_id , user_id )
                                                                 VALUES(:zcomment , 0 , now() , :zitemid , :zuserid)") ;

                                $stmt->execute(array(
                                        'zcomment'   => $comment ,
                                        'zitemid'   => $itemid ,
                                        'zuserid'   => $userid 
                                ))  ;
                                if($stmt){
                                        echo '<div class = "alert alert-success">Comment Added</div>';
                                }                               
                        }

                   } ?>
                </div>
        </div>
    </div>
    <!-- end add comment  -->
    <?php } else{
        echo '<a href = "login.php">Login </a>  Or <a href = "login.php"> Register </a> To Add Comment' ;
    }
    ?>
    <hr>
    <?php 
        
        $stmt = $con->prepare(" SELECT
                                    comments.* , users.Username AS Member
                                FROM 
                                    comments
                             
                                INNER JOIN 
                                    users
                                ON
                                    users.UserID = comments.user_id 
                                    WHERE
                                        item_id = ?
                                AND 
                                        `status` = 1        
                                ORDER BY 
                                    c_id DESC               
                                 ");
        $stmt->execute(array($item['Item_ID']));

        //assign to vairable 
        $comments = $stmt->fetchAll(); 

    

        
        ?>
    
   <?php 
    foreach($comments as $comment){  ?>
      <div class="comment-box">
      <div class="row">
        <div class = "col-md-2 text-center">
                <img class = "img-responsive img-thumbnail img-circle center-block" src = "avatar.png" alt = ""/>
                <?php echo $comment['Member'] ?>
         </div>
        <div class = "col-md-10">
                <p class="lead"> <?php echo $comment['comment'] ?></p>
                </div>
        </div>
      </div>
      <hr>

        <?php
}
   ?>
   
</div>


<?php

} else{
    echo '<div class = "alert alert-danger">There Is No Such Id Or This Item Waiting Approve </div> ' ;
}
include $tpl . "footer.php" ;
ob_end_flush();
