<?php


function getAllFrom1($field , $table , $where = NULL, $and = NULL  , $orderfield , $ordering = 'DESC'){
    global $con ; 

    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering ") ;

    $getAll->execute();

    $all = $getAll->fetchAll() ;

    return $all ;
}

//get All items  from database 

function getAllFrom($tableName,$OrderBy,$where = NULL){

    global $con; 

    $sql = $where == NULL ? '' : $where ;

    $getAll = $con->prepare("SELECT * FROM $tableName $sql ORDER BY $OrderBy DESC") ;

    $getAll->execute() ;

   // fetchall use it for bring all data
   $all = $getAll->fetchAll() ;
   return $all;

}
 //get categories from database 

 function getCat(){

    global $con; 

    $getCat = $con->prepare("SELECT * FROM categories  ORDER BY ID ASC") ;

    $getCat->execute() ;

   // fetchall use it for bring all data
   $cats = $getCat->fetchAll() ;
   return $cats;

}


 //get Items from database 

 function getItems($where , $value, $approve = NULL){

    global $con; 
    
    if($approve == NULL){

        $sql ='AND Approve = 1' ;
    } else{ 

        $sql = NULL ;
    }

    $getItems = $con->prepare("SELECT * FROM items WHERE $where =? $sql  ORDER BY Item_ID DESC") ;

    $getItems->execute(array($value)) ;

   // fetchall use it for bring all data
   $Items = $getItems->fetchAll() ;
   return $Items;

}


// function to chick items in database v1.0

function checkItem($select, $from , $value) {

    global $con;

    $stmt2 = $con->prepare("SELECT $select FROM $from WHERE $select = ? ") ;

    $stmt2->execute(array($value));

    $count = $stmt2->rowCount();
    return $count ;
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

 // function if user status 0 or 1
 function checkUserStatus($user){
    global $con ;

     $stmtx = $con->prepare("SELECT Username ,RegStatus  FROM users WHERE Username = ? AND RegStatus = 0") ;

     $stmtx->execute(array($user)) ;

     $status = $stmtx->rowCount() ;

     return $status ;
 }
  
