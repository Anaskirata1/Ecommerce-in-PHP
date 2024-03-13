<?php

if(isset($_GET['do'])){
    $do =  $_GET['do'];
} else {
    $do = 'Manage';
};

if($do == 'Manage') {

    echo 'welcome you are in Manage category page' .'<br>';
    echo '<a href = "Page.php?do=Add"> Add New Category</a>';
    echo '<br>';
    echo '<a href = "Page.php?do=Insert"> Insert category</a>';

} elseif ($do == 'Insert') {

    echo 'welcome you are in Insert category page';

} elseif($do == 'Add') {

    echo 'welcome you are in Add category page';

} else {

     echo  'there is no page'; 

}