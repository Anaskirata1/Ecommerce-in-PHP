<?php
session_start() ;

$pageTitle = 'Items';

if(isset($_SESSION['Username'])) {
    include 'init.php' ;
    $do = isset($_GET['do']) ? $_GET['do'] :'Manage' ;

    if($do == 'Manage'){
            // inner join in mysql 
        $stmt = $con->prepare("SELECT
                                     items.* , 
                                     categories.Name AS Category_Name ,
                                     users.Username AS Member_Name 
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
                                ORDER BY
                                        Item_ID DESC");
            $stmt->execute();

            //assign to vairable 
            $items = $stmt->fetchAll();

            if(!empty($items)){

            
            ?> 
        <!-- manage members page  -->
         <h1 class= "text-center" > Manage Items  </h1>
        
        <div class = "container">
            <div class = "table-responsive"> 
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date </td>
                        <td>Category </td>
                        <td>Username </td>
                        <td>Control</td>
                    </tr>

                    <?php // للوصول الى عناصر الجدول وطبعه داخل الجدول 
                    foreach($items as $item){
                        echo "<tr>";
                            echo "<td>" . $item['Item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] ."</td>";
                            echo "<td>" . $item['Category_Name'] ."</td>";
                            echo "<td>" . $item['Member_Name'] ."</td>";
                            echo "<td>
                                    <a href = 'items.php?do=Edit&itemid=" .$item['Item_ID'] . " 'class = 'btn btn-success' > <i class ='fa fa-edit'></i>Edit </a> 
                                    <a href = 'items.php?do=Delete&itemid=" .$item['Item_ID'] . " 'class = 'btn btn-danger confirm' > <i class ='fa fa-close'></i>Delete </a> " ;
                                    // for activate items who has RegStatus = 0 
                                    if($item['Approve'] == 0) {
                                        echo "<a href ='items.php?do=Approve&itemid=" .$item['Item_ID'] . " 'class = 'btn btn-info' > <i class ='fa fa-check'></i>Approve </a> ";


                                    }

                               echo "</td>";
                        echo "</tr>";
                       
                    }
                    
                    ?>

                </table>

            </div>
             <a href = 'items.php?do=Add' class = "btn btn-primary "> <i class="fa fa-plus"></i> Add Item </a> 
             <a href = 'http://localhost/ecommerce/admin/dashboard.php' class = "btn btn-primary  "> <i class="fa fa-plus"></i> dashboard </a> 
        </div> 
        <?php } else {
             echo '<div class = "container">' ;
                 echo '<div class = "nice-message">There Is NO Record To Show</div>' ;
                 echo '<a href = "items.php?do=Add" class = "btn btn-primary "> <i class="fa fa-plus"></i> Add Item </a>' ;
             echo '</div>' ;
        } ?>
    <?php
    } elseif ($do == 'Add') { ?>
     <h1 class= "text-center"> Add new item</h1>
        <div class="container">
            <form class= "form-horizontal"  action = "?do=Insert" method = "POST">
                <!-- start item name  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "name" 
                               class = "form-control" 
                              
                               placeholder = "name of the Item">
                    </div> 
                </div> <!-- end item name --> 
                <!-- start item description  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Descrption</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "description" 
                               class = "form-control" 
                                
                               placeholder = "description of the Item">
                    </div> 
                </div> <!-- end item description --> 
                <!-- start item Price  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "price" 
                               class = "form-control" 
                            
                               placeholder = "price of the Item">
                    </div> 
                </div> <!-- end item price --> 
               
                <!-- start item country  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "country" 
                               class = "form-control" 
                              
                               placeholder = "country of made">
                    </div> 
                </div> <!-- end item country --> 
                <!-- start item status -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0"></option>
                            <option value="1">new</option>
                            <option value="2">like new</option>
                            <option value="3">used</option>
                            <option value="4">old</option>
                        </select>
                    </div> 
                </div> <!-- end item status --> 
                <!-- start item memper -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0"></option> 
                            <?php 
                            $allMembers = getAllFrom("*" , "users" , "" , "" , "UserID") ;
                            foreach($allMembers as $user) {
                                echo "<option value = '" .$user['UserID']. "'>" .$user['Username']. "</option>" ;
                            }
                            ?>
                            
                            
                        </select>
                    </div> 
                </div> <!-- end item member --> 
                <!-- start item category -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">gategory</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0"></option> 
                            <?php 
                            $allCats = getAllFrom("*" , "categories" , "WHERE Parent = 0" , "" , "ID") ;
                            foreach($allCats as $cat) {
                                echo "<option value = '" .$cat['ID']. "'>" .$cat['Name']. "</option>" ;
                                 $childCats = getAllFrom("*" , "categories" , "WHERE Parent = {$cat['ID']}" , "" , "ID") ;
                                 foreach($childCats as $child) {
                                    echo "<option value = '" .$child['ID']. "'>---" .$child['Name']. "</option>" ;
                                 }
                            }
                            ?>
                            
                            
                        </select>
                    </div> 
                </div> <!-- end item member --> 
                         <!-- start item tags  -->
                         <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "tags" 
                               class = "form-control" 
                              
                               placeholder = "Separate Tags With Comma (,)">
                    </div> 
                </div> <!-- end item tags --> 
                  
                <div class= "form-group mt-2">
                        
                        <div class= "col-sm-10 col-md-4">
                        <input type = "submit" value = "add Item" class ="btn btn-primary btn-block" />
                        </div>
                    </div> 
            </form>
        </div>
        <?php 
        
    } elseif ($do == 'Insert' ) {
        if($_SERVER['REQUEST_METHOD'] =='POST') {

            echo "<h1 class= 'text-center'>Insert Item</h1>" ;
            echo "<div class= 'container'>" ;


            //get vairablr from form 
            $name    = $_POST['name'] ;
            $desc    = $_POST['description'] ;
            $price   = $_POST['price'] ;
            $country = $_POST['country'] ;
            $status  = $_POST['status'] ;
            $member  = $_POST['member'] ;
            $cat     = $_POST['category'] ;
            $tags    = $_POST['tags'] ;

            // validate form 
            $formErrors = array() ;

            if(empty($name)) {

                $formErrors[] = 'name can\'t be <strong> embty </strong> ' ;
            } ; 

            if(empty($desc)) {
                
                $formErrors[] =   'description can\'t be <strong> embty </strong> ' ;
            }
            if(empty($price)) {

                $formErrors[] =   'price can\'t be <strong> embty </strong> ' ;
            }
            if(empty($country)) {

                $formErrors[] =   'country can\'t be <strong> embty </strong> ' ;
            }
            if($status == 0  ) {

                $formErrors[] =   'you must choose the <strong> status  </strong> ' ;
            }
            if($member == 0  ) {

                $formErrors[] =   'you must choose the <strong> member </strong> ' ;
            }
            if($cat == 0  ) {

                $formErrors[] =   'you must choose the <strong> category  </strong> ' ;
            }

            foreach($formErrors as $error) {
                echo '<div class = "alert alert-danger">' .$error . '</div>' ;
            }

            // if there is no error 
            if(empty($formErrors)) {
                // insert into database
                $stmt = $con->prepare("INSERT INTO
                items(Name , Description , Price , Country , Status , Add_Date , Cat_ID , Member_ID, tags)
                VALUES (:zname , :zdesc , :zprice , :zcountry , :zstatus , now() ,:zcat , :zmember , :ztags )")  ;

                $stmt->execute(array( 
                    'zname'     => $name ,
                    'zdesc'     => $desc ,
                    'zprice'    => $price ,
                    'zcountry'  => $country ,
                    'zstatus'   => $status  ,
                    'zcat'      => $cat  ,
                    'zmember'   => $member ,
                    'ztags'     => $tags 
                )) ;
                $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted </div>' ; 

                    redirectHome($theMsg,"back" ) ;

                }
        

         } else {
            echo "<div class = 'container'>" ;

            $theMsg = '<div = "alert alert-danger">sorru you cant Browse this page</div>' ;

            redirectHome($theMsg , "back");

            echo "</div>" ;
            
        }
     } elseif ($do == 'Edit') {
                     // chick if GET REQOUST itemid is numeric and Get the integer value of it
                     $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

                     $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? ") ;
             
                     $stmt->execute(array($itemid)) ;
             
                     $item = $stmt->fetch() ; 
             
                     $count = $stmt->rowCount() ;
             
                     if($count > 0) { ?>
                      <h1 class= "text-center"> Edit Item</h1>
                          <div class="container">

                <form class= "form-horizontal"  action = "?do=Update" method = "POST">
                <input type = "hidden" name = "itemid" value= "<?php echo $itemid ; ?>" >
                <!-- start item name  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "name" 
                               class = "form-control" 
                               require = "require"
                               placeholder = "name of the Item"
                               value = "<?php echo $item['Name'] ?>">
                    </div> 
                </div> <!-- end item name --> 
                <!-- start item description  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Descrption</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "description" 
                               class = "form-control" 
                                
                               placeholder = "description of the Item"
                               value = "<?php echo $item['Description'] ?>" >
                    </div> 
                </div> <!-- end item description --> 
                <!-- start item Price  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "price" 
                               class = "form-control" 
                            
                               placeholder = "price of the Item"
                               value = "<?php echo $item['Price'] ?>" >
                    </div> 
                </div> <!-- end item price --> 
               
                <!-- start item country  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "country" 
                               class = "form-control" 
                              
                               placeholder = "country of made"
                               value = "<?php echo $item['Country'] ?>">
                    </div> 
                </div> <!-- end item country --> 
                <!-- start item status -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0"></option>
                            <option value="1" <?php if($item['Status'] == 1){echo'selected';}  ?>>new</option>
                            <option value="2" <?php if($item['Status'] == 2){echo'selected';}  ?>>like new</option>
                            <option value="3" <?php if($item['Status'] == 3){echo'selected';}  ?>>used</option>
                            <option value="4" <?php if($item['Status'] == 4){echo'selected';}  ?>>old</option>
                        </select>
                    </div> 
                </div> <!-- end item status --> 
                <!-- start item memper -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0"></option> 
                            <?php 
                            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1") ;
                            $stmt->execute() ;
                            $users = $stmt->fetchAll();
                            foreach($users as $user) {
                                // echo "<option value = '" .$user['UserID']. "'>" .$user['Username']. "</option>" ;
                                echo "<option value = '" .$user['UserID']. "'";
                                 if($item['Member_ID'] == $user['UserID']){echo'selected';} 
                                 echo">" .$user['Username']. "</option>" ;
                            }
                            ?>
                            
                            
                        </select>
                    </div> 
                </div> <!-- end item member --> 
                <!-- start item category -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">gategory</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0"></option> 
                            <?php 
                            $stmt = $con->prepare("SELECT * FROM categories") ;
                            $stmt->execute() ;
                            $cats= $stmt->fetchAll();
                            foreach($cats as $cat) {
                                echo "<option value = '" .$cat['ID']. "'";
                                if($item['Member_ID'] == $user['UserID']){echo'selected';}
                               echo" >" .$cat['Name']. "</option>" ;
                            }
                            ?>
                            
                            
                        </select>
                    </div> 
                </div> <!-- end item gategory --> 
                                   <!-- start item tags  -->
                                   <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" 
                               name = "tags" 
                               class = "form-control" 
                              
                               placeholder = "Separate Tags With Comma (,)"
                               value = "<?php echo $item['tags'] ?>">
                               
                    </div> 
                </div> <!-- end item tags --> 
                  
                <div class= "form-group mt-2">
                        
                        <div class= "col-sm-10 col-md-4">
                        <input type = "submit" value = "Update" class ="btn btn-primary btn-block" />
                        </div>
                    </div> 
            </form>
            <?php

            $stmt = $con->prepare(" SELECT
                                    comments.*, users.Username AS Member
                                FROM 
                                    comments
                               
                                INNER JOIN 
                                    users
                                ON
                                    users.UserID = comments.user_id            
                                WHERE
                                    item_id = ? ");
        $stmt->execute(array($itemid));

        //assign to vairable 
        $rows = $stmt->fetchAll();
        if(!empty($rows)) {
        
        ?>
        


                 <h1 class= "text-center" > Manage [<?php echo $item['Name'] ; ?>] Comment  </h1>
                
                
                    <div class = "table-responsive"> 
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added date </td>
                                <td>Control</td>
                            </tr>

                            <?php // للوصول الى عناصر الجدول وطبعه داخل الجدول 
                            foreach($rows as $row){
                                echo "<tr>";
                                    echo "<td>" . $row['comment'] . "</td>";
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
                <?php } ?>
                    

         </div>
                                 

      <?php  
        } else {
            $errorMsg = 'there is no sush id' ;
            redirectHome($errorMsg,5);
        }
    } elseif ($do == 'Update') {
         echo "<h3 class = 'text-center'>Update Item</h3>" ;
         echo "<div class = 'container'>" ;
         if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id      = $_POST['itemid'] ;
            $name    = $_POST['name'] ;
            $desc    = $_POST['description'] ;
            $price   = $_POST['price'] ;
            $country = $_POST['country'] ;
            $status  = $_POST['status'] ;
            $member  = $_POST['member'] ;
            $cat     = $_POST['category'] ;
            $tags    = $_POST['tags'] ;

            $formErrors = array() ;

            if(empty($name)) {

                $formErrors[] = 'name can\'t be <strong> embty </strong> ' ;
            } ; 

            if(empty($desc)) {
                
                $formErrors[] =   'description can\'t be <strong> embty </strong> ' ;
            }
            if(empty($price)) {

                $formErrors[] =   'price can\'t be <strong> embty </strong> ' ;
            }
            if(empty($country)) {

                $formErrors[] =   'country can\'t be <strong> embty </strong> ' ;
            }
            if($status == 0  ) {

                $formErrors[] =   'you must choose the <strong> status  </strong> ' ;
            }
            if($member == 0  ) {

                $formErrors[] =   'you must choose the <strong> member </strong> ' ;
            }
            if($cat == 0  ) {

                $formErrors[] =   'you must choose the <strong> category  </strong> ' ;
            }

            foreach($formErrors as $error) {
                echo '<div class = "alert alert-danger">' .$error . '</div>' ;
            }

            $stmt = $con->prepare(" UPDATE
                                        items
                                    SET 
                                        `Name` = ? ,
                                        `Description` = ? ,
                                        Price = ? ,
                                        Country = ? ,
                                        `Status` = ? ,
                                        Cat_ID = ? ,
                                        Member_ID = ? ,
                                        tags  = ? 
                                    WHERE
                                         Item_ID = ?") ;
            $stmt->execute(array($name , $desc , $price , $country , $status , $cat , $member , $tags , $id))   ;  
            
            $theMsg =   "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>' ;
            redirectHome($theMsg,"back" ) ;


         } else {
            
                $theMsg =  'sorry you cant Browes this page' ;
               redirectHome($theMsg,"back" ) ;
         }


         echo "</div>" ;
    } elseif ($do == 'Delete') {
        echo  "<h1 class= 'text-center' >Delete Item </h1>" ; 
        echo "<div class = 'container'>" ;

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

        $check = checkItem('Item_ID' , 'items' , $itemid) ;

        if($check > 0) {
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid") ;

            $stmt->bindparam(":zid" , $itemid) ; 

            $stmt->execute() ;

            $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted </div>' ;
            redirectHome($theMsg , 'back') ;
        } else {
                    
            $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
            redirectHome($theMsg) ;
            
    }
    echo "</div>" ;
    
} elseif ($do == 'Approve') {
     echo "<h3 class = 'text-center'> Approve Item </h3>" ;
     echo "<div class = 'container'>" ;
     $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

     $check = checkItem('Item_ID' , 'items' ,$itemid) ;

     if($check > 0 ) {

        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?") ;

        $stmt->execute(array($itemid)) ;

        $theMsg = "<div class = 'alert alert-success'>". $stmt->rowCount() . "Record Updeted </div>" ;
        redirectHome($theMsg , 'back') ;


     } else {
        $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
        redirectHome($theMsg) ;
     }
     echo "</div>" ;

        
    }
    include $tpl . 'footer.php' ;

} else {
    header('location: index.php') ;
    
    exit() ;
}