<?php
include './include/admHeader.php'; 
$db = new Database();
$fn=$un=$email=$phone = '';
$pw = ''; $err = 0; $result = '';
$admins = $db->Fetch('admins');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    verify_csrf_or_die();

    $fn = $db->FilterInput($_POST['fullname']);
    $un = $db->FilterInput($_POST['username']);
    $email = $db->FilterInput($_POST['email']);
    $phone = $db->FilterInput($_POST['phone']);
    $tempPlainPassword = bin2hex(random_bytes(6));
    $pw = $db->HashPassword($tempPlainPassword);
    if(empty($fn) || empty($un) || empty($email) || empty($phone)){
        $err = 1;
        echo "<script>alert('All fields are required.')</script>";
    }

    if($err == 0){
        $exists = $db->FetchAllWithCriteria('admins', ['username' => $un], 'LIMIT 1');
        if(!empty($exists)){
            $err = 1;
            echo "<script>alert('Username already exists')</script>";
        }
    }

    if($err == 0){
        $data = ['fullname' => $fn, 'username'=>$un, 'email'=>$email, 'phone'=>$phone, 'pass'=>$pw];
        $result = $db->Insert('admins', $data);
        if($result == 'Successful'){
            echo "<script>alert('Admin added successfully. Temporary password: ".$tempPlainPassword."')</script>";
            $admins = $db->Fetch('admins');
            $fn=$un=$email=$phone = '';
            $pw = ''; $err = 0;
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
    <h2 class="page-header">Manage Administrators</h2>
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
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <br>
        <label class="page-label">Full name</label>
        <input value="<?= $fn ?>" type="text" class="form-control" name="fullname" required >
        <br>
        <label class="page-label">Username</label>
        <input value="<?= $un ?>" type="text" class="form-control" name="username" required >
        <br>
        <label class="page-label">Email</label>
        <input value="<?= $email ?>" type="text" class="form-control" name="email" required >
        <br>
        <label class="page-label">Phone number</label>
        <input value="<?= $phone ?>" type="text" class="form-control" name="phone" required >
        <br>
        <button name="submit" type="submit" class="btn btn-success">Add admin</button>
        </form>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <label class="page-label">Existing admins</label>
        <table class="table form-table table-striped table-bordered table-responsive">
            <tr>
                <td>Full Name</td>
                <td>Email</td>
                <td>Phone</td>
            </tr>
            <?php 
                if(!empty($admins)){
                    foreach($admins as $a){
                        ?>
                         <tr>
                            <td><?= $a['fullname'] ?></td>
                            <td><?= $a['email'] ?></td>
                            <td><?= $a['phone'] ?></td>
                         </tr>
                        <?php
                    }
                }
            ?>
        </table>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
