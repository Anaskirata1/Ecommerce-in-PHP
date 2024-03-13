<?php
include 'connect.php';

$tpl = 'includes/templets/' ;
$css = 'layout/css/';
$js = 'layout/js/';
$lang = 'includes/languages/';
$func = 'includes/functions/';


include $func . 'function.php';
include $lang .'en.php';
include $tpl .'header.php';


if(!isset($noNavbar)){
    include $tpl .'navbar.php';
};
include $tpl .'footer.php';




