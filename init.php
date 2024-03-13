<?php
include 'admin/connect.php';

$sessionUser = '' ;

if(isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'] ;
}

$tpl = 'includes/templets/' ;
$css = 'layout/css/';
$js = 'layout/js/';
$lang = 'includes/languages/';
$func = 'includes/functions/';


include $func . 'function.php';
include $lang .'en.php';
include $tpl .'header.php';



// include $tpl .'footer.php';




