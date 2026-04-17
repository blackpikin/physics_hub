<?php 
include 'include/header.php';
if(!isset($_SESSION['usern']) || $_SESSION['usern'] == ''){
    header('Location: login.php');
    exit;
}
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff'){
    http_response_code(403);
    exit('Access denied');
}
$db = new Database();
 ?>
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <?php include "include/createSide.php"; ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
        
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
