<?php 
include 'include/header.php';
if(!isset($_SESSION['usern']) || $_SESSION['usern'] == ''){
    echo '<script>window.location.href="login.php"</script>';
}
$db = new Database();
 ?>
 <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <?php include "include/takeSide.php"; ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <form action="" method="post">
    <h2 class="page-header">Test from Mock</h2>
        <br>
        <label class="page-label">Number of questions (minimum 10; Maximum 50)</label>
        <input class="form-control" type="text" name="numQues" required>
        <br>
        <label class="page-label">Duration (in minutes)</label>
        <input class="form-control" type="text" name="duration" required>
        <br>
        <button type="submit" class="btn btn-success">Start test</button>
        </form>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>