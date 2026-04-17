<?php
include './include/admHeader.php'; 
$db = new Database();
$fn=$un=$email=$phone = '';
$pw = ''; $err = 0; $result = '';
$users = $db->Fetch('users');

?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <h4 class="control-panel">Control panel</h4>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <br>
    <h2 class="page-header">Manage users</h2>
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
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
    <label class="page-label">Existing users</label>
        <table class="table form-table table-striped table-bordered table-responsive">
            <tr>
                <td>Full Name</td>
                <td>Username</td>
                <td>Class</td>
                <td>School</td>
                <td>Picture</td>
                <td>Actions</td>
            </tr>
            <?php 
                if(!empty($users)){
                    foreach($users as $a){
                        ?>
                         <tr>
                            <td><?= $a['fullname'] ?></td>
                            <td><?= $a['username'] ?></td>
                            <td><?= $a['class'] ?></td>
                            <td><?= $a['school'] ?></td>
                            <td></td>
                            <td><button title="Block user" class="btn btn-danger fa fa-stop"></button></td>
                         </tr>
                        <?php
                    }
                }
            ?>
        </table>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>