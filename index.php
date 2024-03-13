<?php
session_start();

$pageTitle = 'Home Page' ;

include "init.php";?>

<div class="container">
    <h1 class = "text-center">Show category</h1>
    <div class="row">
        <?php
        $allItems = getAllFrom('items' , 'Item_ID' , 'where Approve =1');
        foreach($allItems as $item){
            echo '<div class = "col-sm-6 col-md-3">' ;
                echo '<div class = "thumbnail item-box">' ;
                    echo '<span class = "price-tag">$'.$item['Price'].'</span>' ;
                    echo '<img class = "img-responsive" src = "avatar.png" alt = ""/>';
                    echo '<div class = "caption">' ;
                        echo '<h3> <a href = "items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>' ;
                        echo '<span>'.$item['Description'].'</span>' ;
                        echo '<div class = "date">'.$item['Add_Date'].'</div>' ;


                    echo '</div>' ;

                echo '</div>' ;



            echo '</div>' ;
            }
        ?>
    </div>
</div>


<?php

include $tpl . "footer.php" ;
