<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href = "<?php echo $css?>front.css">
    <link rel="stylesheet" href = "<?php echo $css?>bootstrap.min.css">
    <link rel="stylesheet" href = "<?php echo $css?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href = "<?php echo $css?>font-awesome.min.css">
    <link rel="stylesheet" href = "<?php echo $css?>jquery-ui.css">


    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"> -->
    <title><?php getTitle(); ?></title>
</head>
<body> 
  <div class="upper-bar">
    <div class="container">

        <?php
        if(isset($_SESSION['user'])) {
          ?>   
             <span class = "pull-left"> 
            
              <img class = "image" src="avatar.png" alt="">
               welcome-  <?php echo $_SESSION['user'];
                                  // echo checkUserStatus($_SESSION['user']) ; 
                                   ?> -
             
             
        </span>
        <a href="profile.php"> My Profile-</a>
        <a href ="newad.php">New Item</a>
        <a href ="profile.php#my-ads">My Ads</a>
        <a href="logout.php" class = "pull-right"> Logout</a>
             <span class = "pull-right"> 
               <?php 
               $userstatus = checkUserStatus($_SESSION['user']) ;
               if($userstatus == 1){
                 echo 'your membership need to activate by admin';
               } ?>

              </span>
             <?php
        } else{ 
          ?>
        <a href="login.php" class = "pull-right"> 
          <span >Login/signup</span>
        </a>
          <?php }  ?>
   
    
    
        
    </div>
  </div>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    
    <a class="navbar-brand" href="index.php">Home Page</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="nav navbar-nav navbar-tight me-auto mb-2 mb-lg-0">
     
               <?php 

            $categories = getAllFrom1("*" , "categories" , "WHERE Parent = 0" , "" , "ID");
              foreach($categories as $cat){
                  echo '<li class = "nav-item">
                    <a class="nav-link active" aria-current="page" href ="categories.php?pageid='.$cat['ID'].'">
                    ' . $cat['Name'] . '
                    </a>
                    </li>';
              }


          ?>
      
        
       
    
      </ul>
      
     
    </div>

  </div>
</nav>
    
    

<!-- <div btn-group>
            <span class="btn dropdown-toggle" data-toggle="dropdown">
            <?php // echo $_SESSION['user']; ?>
            <span class="caret"></span>
            </span>
            <ul class="dropdown-menu">
              <li><a href="profile.php">My Profile</a></li>
              <li><a href="newad.php">New Item </a></li>
              <li><a href="logout.php">Logout </a></li>
            </ul>
          </div> -->