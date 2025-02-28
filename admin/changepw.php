<?php include './include/admHeader.php' ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <br>
    <h2 class="page-header">Change your password</h2>
    <br>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

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
        <input type="password" class="form-control" name="ans1" required >
        <br>
        <label class="page-label">New password</label>
        <input type="password" class="form-control" name="ans2" required >
        <br>
        <label class="page-label">Confirm new password</label>
        <input type="password" class="form-control" name="ans3" required >
        <br>
        <br>
        <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>

