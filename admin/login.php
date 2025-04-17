<?php
 session_start(); 
include 'include/database.php';
$db = new Database();
$un=$pw='';
$err = 0;
$result ='';
$res = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $un = $db->FilterInput($_POST['username']);
    $pw = $db->FilterInput($_POST['pass']);
    if($err < 5){
        $err = $err + 1;
        $res = $db->FetchAllWithCriteria('admins', ['username'=>$un, 'pass'=>$db->HashPassword($pw)]);
        if(empty($res)){
            $result = 'Invalid username or password';
        }else{
            $_SESSION['int_phy_username'] = $res[0]['username'];
            $_SESSION['int_phy_fullname'] = $res[0]['fullname'];
            $_SESSION['int_phy_phone'] = $res[0]['phone'];
            $_SESSION['int_phy_email'] = $res[0]['email'];
            $_SESSION['int_phy_has_changed'] = $res[0]['has_changed'];
            echo '<script>window.location.href="index.php"</script>';
        }
    }else{
        $result = 'Too many failed attempts to login';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css">
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <title>Interactive Physics Hub</title>
</head>
<body>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h6 class="main-large-head">The <sup>i</sup>nteractive Physics Hub</h6>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
       
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <br>
    <h2 class="page-header">Log in</h2>
    <br>
    <span style="font-weight:bold; color:red"><?= $result ?></span>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <form action="" method="post">
        <br>
        <label class="page-label">Username</label>
        <input value="<?= $un ?>" type="text" class="form-control" name="username" required >
        <br>
        <label class="page-label">Password</label>
        <input value="<?= $pw ?>" type="password" class="form-control" name="pass" required >
        <br>
        <button name="submit" type="submit" class="btn btn-success">Login</button>
        </form>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
</div>