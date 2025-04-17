<?php 
include './include/admHeader.php';
$curr = $new = $cnew ='';
$err = 0; $result = '';
$db = new Database();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $curr = $db->FilterInput($_POST['curr']);
    $new = $db->FilterInput($_POST['new']);
    $cnew = $db->FilterInput($_POST['cnew']);
    if(empty($curr) || empty($new) || empty($cnew)){
        $err =1;
        echo "<script>alert('All fields are required')</script>";
    }

    if($new != $cnew){
        $err = 1;
        echo "<script>alert('The new password and the confirmation do not match')</script>";
    }

    $cpw = $db->FetchAllWithCriteria('admins', ['username'=>$_SESSION['int_phy_username']]);
    if($cpw[0]['pass'] != $db->HashPassword($curr)){
        $err=1;
        echo "<script>alert('Invalid current password')</script>";
    }

    if($err == 0){
        $data = ['pass'=>$db->HashPassword($new)];
        $result = $db->Update('admins', $data, ['username'=>$_SESSION['int_phy_username']]);
        if($result == 'Successful'){
            echo "<script>alert('Password changed. You will be logged out so you can log in with the new password')</script>";
            echo '<script>window.location.href="logout.php"</script>';
        }else{
            echo "<script>alert('$result')</script>";
        }
    }
}
?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <h4 class="control-panel">Control panel</h4>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <br>
    <h2 class="page-header">Change your password</h2>
    <br>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <label class="page-label"> User:</label>
        <?php 
            echo $_SESSION['int_phy_username'];
        ?>
         <a href="logout.php" class="logout">Logout</a>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <?php include "./include/admSidebar.php"; ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
        <form action="" method="post">
        <br>
        <label class="page-label">Current password</label>
        <input type="password" class="form-control" name="curr" required >
        <br>
        <label class="page-label">New password</label>
        <input type="password" class="form-control" name="new" required >
        <br>
        <label class="page-label">Confirm new password</label>
        <input type="password" class="form-control" name="cnew" required >
        <br>
        <br>
        <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>

