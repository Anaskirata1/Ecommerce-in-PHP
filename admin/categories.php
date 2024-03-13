<?php
session_start();
$pageTitle = 'Categoires' ;

if(isset($_SESSION['Username'])){
    // start code of categoires page
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage' ;
    // start main page of categories 
    if($do == 'Manage'){
        $sort = 'ASC' ;
        $sort_array = array('ASC' , 'DESC') ;
        if(isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array)){
            $sort = $_GET['sort'] ;
        }
        $stmt= $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY ordering $sort") ;

        $stmt->execute();

        $cats = $stmt->fetchAll(); 
        if(!empty($cats)) {
        
        ?>

        <h1 class = "text-center">Manage Category</h1> 
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                     <i class="fa fa-edit"></i> Maneg Category
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering: [
                        <a class ="<?php if($sort == 'ASC') {echo 'active'; } ?> "  href="?sort=ASC">Asc</a> |
                        <a class ="<?php if($sort == 'DESC') {echo 'active'; } ?> " href="?sort=DESC">Desc</a> ]
                        <i class="fa fa-eye"></i> View: [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span> ]

                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach($cats as $cat){
                        echo "<div class = 'cat'>" ;
                             echo "<div class = 'hidden-buttons'>" ;
                                echo "<a href ='categories.php?do=Edit&catid= " . $cat['ID'] . "' class = 'btn btn-xs btn-primary'><i class = 'fa fa-edit'></i> Edit </a>" ;
                                echo "<a href ='?do=Delete&catid=".$cat['ID']."' class = 'confirm btn btn-xs btn-danger'><i class = 'fa fa-close'></i> Delete</a>" ;
                                // echo "<a href ='categories.php?do=Edit&catid=" .$cat['ID'] . " 'class = 'btn btn-xs btn-primary' ><i class ='fa fa-edit'></i> Edit</a> ";
                                // echo "<a href ='categories.php?do=Edit&catid=" .$cat['ID'] . " 'class = 'btn btn-xs btn-danger' ><i class ='fa fa-close'></i> Delete</a> ";

                            echo "</div>" ; 
                        echo "<h3>" . $cat['Name'] . "</h3>";
                        echo "<div class = 'full-view'>" ;
                            echo "<p>";
                             if($cat['Description'] == ''){echo 'this category has no description' ;} else {
                               echo $cat['Description'] ;
                            } 
                            echo "</p>";
                            if($cat['Visibility'] ==1) {echo '<span class = "visibilty"><i class = "fa fa-eye"></i> Hidden</span>'; }
                            if($cat['Allow_Comment'] ==1) {echo '<span class = "commenting"><i class = "fa fa-close"></i>  Comment Disabled</span>'; }
                            if($cat['Allow_Ads'] ==1) {echo '<span class = "advertises"><i class = "fa fa-close"></i> Ads Disabled</span>'; }
                        echo "</div>" ;
                        // get child category
                        
                        $childCats = getAllFrom("*" , "categories" , "WHERE Parent = {$cat['ID']}" , "" , "ID", "ASC");
                        if(!empty($childCats)) {
                        echo "<h5 class = 'child-head'> Child Categories</h5>" ;
                         echo '<ul class = "list-unstyled child-cats">'  ; 
                        foreach($childCats as $c){
                           
                              echo "<li class = 'child-link'>
                                        <a href ='categories.php?do=Edit&catid= " . $c['ID'] . "'>" . $c['Name'] . "</a>
                                        <a href ='?do=Delete&catid=".$c['ID']."' class = 'confirm show-delete'>Delete</a>
                                        </li>" ;
                             
                              
                        } 
                        echo '</ul>'  ;
                    }
                        echo "</div>" ;
                      

                        
                    echo "<hr>" ;

                        
                    } ;
                    
                    
                    ?>
                </div>

            </div>
            <a href="?do=Add"  class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
        </div>

        <?php } else {
                            echo '<div class = "container">' ;
                            echo '<div class = "nice-message">There Is NO Record To Show</div>' ;
                            echo '<a href="?do=Add"  class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add New</a>' ;
                           
                            echo '</div>' ;
        }
         ?> 


      <?php 
    } elseif ($do == 'Add') { ?>
        <h1 class= "text-center"> Add new category</h1>
        <div class="container">
            <form class= "form-horizontal"  action = "?do=Insert" method = "POST">
                <!-- start categories name  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name = "name" class = "form-control" autocomplet ="off" required= "required" placeholder = "name of the category">

                    </div> 
                </div> <!-- end categories name --> 
                <!-- start description field  --> 
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Description </label>
                     <div class= "col-sm-10 col-md-6">    
                        <input type = "text" name = "description" class ="form-control" placeholder = "about the category"/>
                        
                    </div>
                </div>
                <!-- end description field  --> 
                <!-- start ordering field  --> 
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Ordering </label>
                     <div class= "col-sm-10 col-md-6">    
                        <input type = "text" name = "ordering" class ="form-control" placeholder = "Number to arrange the category"/>
                        
                    </div>
                </div>
                <!-- end ordering field  --> 

                <!-- start category type  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Parent </label>
                        <div class= "col-sm-10 col-md-6">  
                            <select name="parent">
                                <option value="0">None</option>
                                <?php 
                                    $allcats = getAllFrom("*" , "categories" , "WHERE Parent = 0" , "" , "ID") ;
                                    foreach($allcats as $cat){
                                        echo "<option value ='" .$cat['ID']. "'>" .$cat['Name']. "</option>" ;
                                    }
                                
                                ?>

                            </select>  
                       
                        </div>
                </div>
                <!-- end category type  -->

                <!-- start visibilty field  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Visible </label>
                     <div class= "col-sm-10 col-md-6">    
                         <div>
                             <input type="radio" id = "vis-yes" name="visibilty" value = "0" checked/>
                             <label for="vis-yes">yes</label>
                         </div>
                         <div>
                             <input type="radio" id = "vis-no" name="visibilty" value = "1"/>
                             <label for="vis-no">no</label>
                         </div>
                        
                    </div>
                </div>
                <!-- end visibilty field  -->
                <!-- start allow commenting field  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Allow Commenting </label>
                     <div class= "col-sm-10 col-md-6">    
                         <div>
                             <input type="radio" id = "com-yes" name="commenting" value = "0" checked/>
                             <label for="com-yes">yes</label>
                         </div>
                         <div>
                             <input type="radio" id = "com-no" name="commenting" value = "1"/>
                             <label for="com-no">no</label>
                         </div>
                        
                    </div>
                </div>
                <!-- end visibilty field  -->
                <!-- start ads field  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Allow Ads </label>
                     <div class= "col-sm-10 col-md-6">    
                         <div>
                             <input type="radio" id = "ads-yes" name="ads" value = "0" checked/>
                             <label for="ads-yes">yes</label>
                         </div>
                         <div>
                             <input type="radio" id = "ads-no" name="ads" value = "1"/>
                             <label for="ads-no">no</label>
                         </div>
                        
                    </div>
                </div>
                <!-- end ads field  -->
                 
                <div class= "form-group mt-2">
                        
                        <div class= "col-sm-10 col-md-4">
                        <input type = "submit" value = "add category" class ="btn btn-primary btn-block" />
                        </div>
                    </div> 
            </form>
        </div>
        <?php 
    } elseif ($do =='Insert') {
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo  "<h1 class= 'text-center' >Insert category </h1>" ; 
            echo "<div class = 'container'>" ;


            // get vairable from form 
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $parent  = $_POST['parent'];
            $order   = $_POST['ordering'];
            $visible = $_POST['visibilty'];
            $comment = $_POST['commenting'];
            $ads     = $_POST['ads'];

            // validate form 
            $check = checkItem('Name', 'categories', $name);
            
            if($check ==1){
                $theMsg = '<div class = "alert alert-danger">sorry this category is exist</div>' ;
                redirectHome($theMsg,"back",5 ) ;
            } else {
                // insert category into database
                $stmt = $con->prepare("INSERT INTO
                                                    categories(Name,Description,parent,Ordering,visibility,Allow_Comment,Allow_Ads)
                                        VALUE(:zname,:zdesc,:zparent,:zorder,:zvisible,:zcomment,:zads)") ;
                $stmt->execute(array(
                    'zname'      => $name ,
                    'zdesc'      => $desc ,
                    'zparent'    => $parent ,
                    'zorder'     => $order ,
                    'zvisible'   => $visible ,
                    'zcomment'   => $comment ,
                    'zads'       => $ads 
                )) ;
                $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updated1 </div>' ; 

                redirectHome($theMsg,"back" ) ;                                   
            }


            echo "<div>" ;
        } else {
            $theMsg = '<div = "alert alert-danger">sooru you cant Browse this page</div>' ;
            redirectHome($theMsg , "back");
        }
        
        
    } elseif ($do =='Edit') {
        // check if get request catid && is integer 
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;

        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ") ;

        $stmt->execute(array($catid)) ;

        $cat = $stmt->fetch() ; 

        $count = $stmt->rowCount() ;

        if($count > 0) { ?>
        
        <h1 class= "text-center"> Edit category</h1>
        <div class="container">
            <form class= "form-horizontal"  action = "?do=Update" method = "POST">
                <input type = "hidden" name = "catid" value= "<?php echo $catid ?>" >
                <!-- start categories name  -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"> Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name = "name" class = "form-control" required= "required" placeholder = "name of the category" value = "<?php echo $cat['Name']; ?>">

                    </div> 
                </div> <!-- end categories name --> 
                <!-- start description field  --> 
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Description </label>
                     <div class= "col-sm-10 col-md-6">    
                        <input type = "text" name = "description" class ="form-control" placeholder = "about the category" value = "<?php echo $cat['Description']; ?>"/>
                        
                    </div>
                </div>
                <!-- end description field  --> 

                <!-- start ordering field  --> 
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Ordering </label>
                     <div class= "col-sm-10 col-md-6">    
                        <input type = "text" name = "ordering" class ="form-control" placeholder = "Number to arrange the category" value = "<?php echo $cat['Ordering']; ?>"/>
                        
                    </div>
                </div>
                <!-- end ordering field  --> 
                 <!-- start category type  -->
                 <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Parent </label>
                        <div class= "col-sm-10 col-md-6">  
                            <select name="parent">
                                <option value="0">None</option>
                                <?php 
                                    $allcats = getAllFrom("*" , "categories" , "WHERE Parent = 0" , "" , "ID") ;
                                    foreach($allcats as $c){
                                        echo "<option value ='" .$c['ID']. "'";
                                        if($cat['Parent'] == $c['ID']) {echo 'Selected';}
                                        echo ">" .$c['Name']. "</option>" ;
                                    }
                                
                                ?>

                            </select>  
                       
                        </div>
                </div>
                <!-- end category type  -->
                <!-- start visibilty field  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Visible </label>
                     <div class= "col-sm-10 col-md-6">    
                         <div>
                             <input type="radio" id = "vis-yes" name="visibilty" value = "0" <?php if($cat['Visibility'] == 0) {echo 'checked' ;} ?> />
                             <label for="vis-yes">yes</label>
                         </div>
                         <div>
                             <input type="radio" id = "vis-no" name="visibilty" value = "1" <?php if($cat['Visibility'] == 1) {echo 'checked'; } ?>/>
                             <label for="vis-no">no</label>
                         </div>
                        
                    </div>
                </div>
                <!-- end visibilty field  -->
                <!-- start allow commenting field  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Allow Commenting </label>
                     <div class= "col-sm-10 col-md-6">    
                         <div>
                             <input type="radio" id = "com-yes" name="commenting" value = "0" <?php if($cat['Allow_Comment'] == 0) {echo 'checked'; } ?> />
                             <label for="com-yes">yes</label>
                         </div>
                         <div>
                             <input type="radio" id = "com-no" name="commenting" value = "1" <?php if($cat['Allow_Comment'] == 1) {echo 'checked'; } ?>/>
                             <label for="com-no">no</label>
                         </div>
                        
                    </div>
                </div>
                <!-- end visibilty field  -->
                <!-- start ads field  -->
                <div class= "form-group form-group-lg">
                     <label class="col-sm-2 control-label">Allow Ads </label>
                     <div class= "col-sm-10 col-md-6">    
                         <div>
                             <input type="radio" id = "ads-yes" name="ads" value = "0" <?php if($cat['Allow_Ads'] == 0) {echo 'checked'; } ?>/>
                             <label for="ads-yes">yes</label>
                         </div>
                         <div>
                             <input type="radio" id = "ads-no" name="ads" value = "1" <?php if($cat['Allow_Ads'] == 1) {echo 'checked'; } ?>/>
                             <label for="ads-no">no</label>
                         </div>
                        
                    </div>
                </div>
                <!-- end ads field  -->
                 
                <div class= "form-group mt-2">
                        
                        <div class= "col-sm-10 col-md-4">
                        <input type = "submit" value = "save category" class ="btn btn-primary btn-block" />
                        </div>
                    </div> 
            </form>
        </div>

            
            <?php
        } else {
            $errorMsg = 'there is no sush id' ;
            redirectHome($errorMsg,5);
        }

    } elseif ($do == 'Update') {
        echo  "<h1 class= 'text-center' >Update Page  </h1>" ; 
        echo "<div class = 'container'>" ;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id      = $_POST['catid'] ; 
            $name    = $_POST['name'] ; 
            $desc    = $_POST['description'] ; 
            $order   = $_POST['ordering'] ; 
            $parent  = $_POST['parent'] ; 
            $visible = $_POST['visibilty'] ; 
            $comment = $_POST['commenting'] ; 
            $ads = $_POST['ads'] ; 

            // update the database 
            $stmt = $con->prepare("UPDATE 
                                         categories 
                                    SET
                                        `Name` = ? ,
                                        `Description` = ? ,
                                        Ordering = ? ,
                                        Parent = ? ,
                                        Visibility = ? ,
                                        Allow_Comment = ? ,
                                        Allow_Ads = ? 
                                    WHERE
                                        ID = ?") ;
            $stmt->execute(array($name, $desc, $order,$parent, $visible, $comment, $ads, $id));
            
            
            $theMsg =   "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Updated </div>' ;
             redirectHome($theMsg,"back" ) ;


        } else {
             $theMsg =  'sorry you cant Browes this page' ;
            redirectHome($theMsg,"back" ) ;
        }


        echo "</div>" ;
    } elseif ($do == 'Delete') {
        echo  "<h1 class= 'text-center' >Delete Page  </h1>" ; 
        echo "<div class = 'container'>" ;

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;

        $check = checkItem('ID' , 'categories' , $catid) ;

        if($check > 0) {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid") ;

            $stmt->bindparam(":zid" , $catid) ; 

            $stmt->execute() ;

            $theMsg = "<div class ='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted </div>' ;
            redirectHome($theMsg , 'back') ;
        } else {
                    
            $theMsg =  ' <div class = "alert alert-danger">this id is not exist </div> '  ;
            redirectHome($theMsg) ;
            
            
        }


        echo "</div>" ;
    }

    include $tpl . 'footer.php';
} else {
    header('location:index.php');
    exit();
}