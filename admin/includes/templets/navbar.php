<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang('ADMIN')?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="categories.php"><?php echo lang('categories')  ?></a>
        </li>
        <li>
          <a class="nav-link active" aria-current="page" href="items.php"><?php echo lang('ITEMS')  ?></a>
        </li>
        <li>
          <a class="nav-link active" aria-current="page" href="members.php"><?php echo lang('MEMBERS')  ?></a>
        </li>
        <!-- <li>
          <a class="nav-link active" aria-current="page" href="#"><?php echo lang('STATISTICS')  ?></a>
        </li> -->
        <li>
          <a class="nav-link active" aria-current="page" href="comments.php"><?php echo lang('COMMENTS')  ?></a>
        </li>
        <!-- <li>
          <a class="nav-link active" aria-current="page" href="#"><?php echo lang('LOGS')  ?></a>
        </li> -->

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            anas
          </a>
          <ul class="dropdown-menu">
            <li><a href="../index.php" class="dropdown-item"  target = 'blank'>Visit Shop</a></li>
            <li><a class="dropdown-item" href="Members.php?do=Edit&userid= <?php echo $_SESSION['ID'] ?>">Edit profile</a></li>
            <li><a class="dropdown-item" href="#">Setting</a></li>
            
            <li><a class="dropdown-item" href="logout.php">log out</a></li>
          </ul>
        </li>
       
    
      </ul>
      
     
    </div>
  </div>
</nav>