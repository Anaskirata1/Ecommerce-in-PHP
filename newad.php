<?php
session_start();

$pageTitle = 'Create New Item' ;

include "init.php";

if(isset($_SESSION['user'])) {
    

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErroes =array() ;

        // make filter for all input 

        $name        =filter_var($_POST['name'], FILTER_SANITIZE_STRING) ;
        $desc        =filter_var($_POST['description'] ,FILTER_SANITIZE_STRING) ;
        $price       =filter_var($_POST['price'] ,FILTER_SANITIZE_NUMBER_INT) ;
        $country     =filter_var($_POST['country'] , FILTER_SANITIZE_STRING) ;
        $status      =filter_var($_POST['status'] ,FILTER_SANITIZE_NUMBER_INT) ;
        $category    =filter_var($_POST['category'] , FILTER_SANITIZE_NUMBER_INT) ;
        $tags        =filter_var($_POST['tags'] , FILTER_SANITIZE_STRING) ;
        

        // validate all input after filter

        if(strlen($name)< 3) {
            $formErroes[] = 'Item Title Must Be At Lest 3 Character' ;
        }
        if(strlen($desc)< 10) {
            $formErroes[] = 'Item Description Must Be At Lest 10 Character' ;
        }
        if(strlen($country)< 3) {
            $formErroes[] = 'Item Country Must Be At Lest 3 Character' ;
        }
        if(empty($price)) {
            $formErroes[] = 'Item Price Must Be Not Empty' ;
        }
        if(empty($status)) {
            $formErroes[] = 'Item Status Must Be Not Empty' ;
        }
        
        if(empty($category)) {
            $formErroes[] = 'Item  Category Must Be Not Empty' ;
        }

        // insert item into database

        if(empty($formErroes)) {

            $stmt = $con->prepare("INSERT INTO
                                          items(`Name` , `Description` , Price, Country , `Status` , Add_Date ,Cat_ID , Member_ID , tags)
                                          VALUES (:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztags)") ;
            $stmt->execute(array(
                'zname'     => $name ,
                'zdesc'     => $desc ,
                'zprice'    => $price ,
                'zcountry'  => $country ,
                'zstatus'   => $status ,
                'zcat'      => $category ,
                'zmember'   => $_SESSION['uid'] ,
                'ztags'     => $tags 
            ))     ;
            if($stmt){
                $succesMsg = 'Item Has Been Added' ;
            }                         

        }
    }
      
    
?>
<h1 class="text-center"> <?php echo $pageTitle ; ?> </h1>
<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
                <div class="panel-heading">
                        <?php echo $pageTitle ; ?>
                </div> 
                <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        
        
                         <form class= "form-horizontal"  action = "<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST">
                            <!-- start item name  -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label"> Name</label>
                                <div class="col-sm-10 col-md-9">
                                <div class="input-container">  
                                    <input type="text" 
                                           pattern = "{4,}"
                                           title = "This Faild Require At Lest 4 Characters"
                                           name = "name" 
                                           class = "form-control live" 
                                           required = "required"
                                           placeholder = "name of the Item"
                                           data-class = ".live-title">
                                </div>
                                </div> 
                            </div> <!-- end item name --> 
                                <!-- start item description  -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Descrption</label>
                                    <div class="col-sm-10 col-md-9">
                                        <div class="input-container">
                                        <input type="text" 
                                               pattern = "{10,}"
                                               title = "This Faild Require At Lest 10 Characters"    
                                               name = "description" 
                                               class = "form-control live" 
                                               required = "required"
                                               placeholder = "description of the Item"
                                               data-class = ".live-desc">
                                        </div>
                                    </div> 
                                </div> <!-- end item description --> 
                                <!-- start item Price  -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                       <div class="input-container">
                                       <input type="text" 
                                               name = "price" 
                                               class = "form-control live" 
                                               required = "required" 
                                               placeholder = "price of the Item"
                                               data-class = ".live-price">
                                       </div>
                                    </div> 
                                </div> <!-- end item price --> 
                            
                                <!-- start item country  -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                       <div class="input-container">
                                       <input type="text"
                                               pattern = "{4,}"
                                               title = "This Faild Require At Lest 4 Characters" 
                                               name = "country" 
                                               class = "form-control" 
                                               required = "required" 
                                               placeholder = "country of made">
                                       </div>
                                    </div> 
                                </div> <!-- end item country --> 
                                <!-- start item status -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status">
                                            <option value="0"></option>
                                            <option value="1">new</option>
                                            <option value="2">like new</option>
                                            <option value="3">used</option>
                                            <option value="4">old</option>
                                        </select>
                                    </div> 
                                </div> <!-- end item status --> 
                                <!-- start item category -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">gategory</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="category">
                                            <option value="0"></option> 
                                            <?php 
                                            $cats = getAllFrom('categories','ID');
                                            foreach($cats as $cat) {
                                                echo "<option value = '" .$cat['ID']. "'>" .$cat['Name']. "</option>" ;
                                            }
                                            ?>
                                            
                                            
                                        </select>
                                    </div> 
                                </div> <!-- end item gategory --> 
                                <!-- start item tags  -->
                                <div class="form-group form-group-lg">
                                     <label class="col-sm-3 control-label">Tags</label>
                                     <div class="col-sm-10 col-md-9">
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
                        <div class="col-md-4">
                             <div class = "thumbnail item-box live-preview">
                                 <span class = "price-tag">
                                     $<span class = "live-price">0</span>
                                 </span>
                                 <img class = "img-responsive" src = "avatar.png" alt = ""/>
                                <div class = "caption">
                                    <h3 class = "live-title">title</h3>
                                    <p class = "live-desc">description</p>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- start looping through erroes  -->
                <?php 
                if(! empty($formErroes)) {
                    foreach($formErroes as $error) {
                        echo '<div class = "alert alert-danger">' . $error . '</div>' ;
                    }
                } 
                if(isset($succesMsg)) {
                    echo '<div class = "alert alert-success">' .$succesMsg . '</div>' ;
                }

                    ?>

                <!-- end looping through erroes  -->
         

            </div>

        </div>
    </div>
</div>

<?php } else{
    header('location: login.php') ;

    exit() ;
}

include $tpl . "footer.php" ;
