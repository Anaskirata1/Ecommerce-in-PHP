<?php

// get all 

function getAllFrom($field , $table , $where = NULL, $and = NULL  , $orderfield , $ordering = 'DESC'){
    global $con ; 

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering ") ;

    $getAll->execute();

    $all = $getAll->fetchAll() ;

    return $all ;
}

 // function for title page v1.0
function getTitle(){
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;

    }else {
         echo 'Default';
    }

} 
// ===========================

// redirect function to home page v2.0

function redirectHome($theMsg, $url = null ,   $seconds = 3 ) {

    if($url === null ){

        $url = 'index.php' ;
        $link = 'Homepage' ;

    } else {
        if(isset( $_SERVER['HTTP_REFERER']) &&  $_SERVER['HTTP_REFERER'] !== ''){
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previoud page' ;

        } else {
            $url = 'index.php';
            $link = 'Homepage' ;
           
        }
    }
       echo  $theMsg ;

    echo "<div class ='alert alert-info'> you will be redirected to $link after $seconds seconds  </div>";

    header("refresh:$seconds ; url=$url") ;
    exit();
} 

// ==========================


// function to chick items in database v1.0

function checkItem($select, $from , $value) {

    global $con;

    $stmt2 = $con->prepare("SELECT $select FROM $from WHERE $select = ? ") ;

    $stmt2->execute(array($value));

    $count = $stmt2->rowCount();
    return $count ;
}


// =========================

// function to get the numpers of users 

function countItems($item,$table){
    global $con ;

$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
$stmt2->execute() ;
return $stmt2->fetchColumn();

}

// ==========================


 //get latest items 

 function getlatest($select , $table, $order , $limit = 5){

     global $con; 

     $stmt2 = $con->prepare("SELECT $select FROM $table  ORDER BY $order DESC LIMIT $limit ") ;

     $stmt2->execute() ;

    // fetchall use it for bring all data
    $rows = $stmt2->fetchAll() ;
    return $rows;

 }
 
  
