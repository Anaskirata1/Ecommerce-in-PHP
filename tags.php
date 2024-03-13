<?php

include "init.php"; ?>
<div class="container">
 
    <div class="row">
        <?php
        if(isset($_GET['name'])) {
        echo "<h1 class = 'text-center'>" ; 
        echo " Show Items By " .$_GET['name'].  "Tag " ;
        echo "</h1>" ;
        $tag = $_GET['name'] ;
        $tagitems = getAllFrom1("*" , "items" , "WHERE tags LIKE '%$tag%'" , "AND Approve =1" ,"Item_ID" ) ;
        foreach($tagitems as $item){
            echo '<div class = "col-sm-6 col-md-3">' ;
                echo '<div class = "thumbnail item-box">' ;
                    echo '<span class = "price-tag">'.$item['Price'].'</span>' ;
                    echo '<img class = "img-responsive" src = "avatar.png" alt = ""/>';
                    echo '<div class = "caption">' ;
                        echo '<h3> <a href = "items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>' ;
                        echo '<span>'.$item['Description'].'</span>' ;
                        echo '<div class = "date">'.$item['Add_Date'].'</div>' ;
                    echo '</div>' ;

                echo '</div>' ;
            echo '</div>' ;
            }
        }
        ?>
    </div>
</div>

<?php
include $tpl . "footer.php" ;
