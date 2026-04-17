<?php 
include 'include/header.php';
$db = new Database();
$name = $class = $school = $user = $pw = $result = $sec = $cpw = '';
$err = 0;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    verify_csrf_or_die();

    if(empty($_POST['sec'])){
        $name = $db->FilterInput($_POST['name']);
        $class = $db->FilterInput($_POST['position']);
        $school = $db->FilterInput($_POST['school']);
        $user = $db->FilterInput($_POST['username']);
        $pw = $db->FilterInput($_POST['pw']);
        $cpw = $db->FilterInput($_POST['cpw']);
        if(empty($name) || empty($class) || empty($school) || empty($user) || empty($pw) || empty($cpw)){
            $err = 1;
            echo "<script>alert('All fields are required')</script>";
        }

        if($pw != $cpw){
            $err = 1;
            echo "<script>alert('The password and its confirmation do not match')</script>";
        }

        if(strlen($pw) < 8){
            $err = 1;
            echo "<script>alert('Password must be at least 8 characters long')</script>";
        }

        if(!empty($_POST['sec'])){
            $err = 1;
        }

        if($err == 0){
            $exists = $db->FetchAllWithCriteria('users', ['username' => $user], 'LIMIT 1');
            if(!empty($exists)){
                $err = 1;
                echo "<script>alert('Username already exists')</script>";
            }
        }

        if($err == 0){
            $data = ['fullname'=>$name, 'username'=>$user, 'password'=>$db->HashPassword($pw), 'class'=>$class,'school'=>$school, 'user_type'=>'staff', 'picture'=>''];
            $result = $db->Insert('users', $data);
            if($result == 'Successful'){
                echo "<script>alert('User added successfully')</script>";
                header('Location: login.php');
                exit;
            }else{
                echo "<script>alert('$result')</script>";
            }
        }

    }
}
?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h2 class="page-header">Register Staff</h2>
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
            <label class="page-label">Name</label>
            <input type="text" placeholder="Enter your name" name="name" class="form-control">
            <label class="page-label">Position</label>
            <input type="text" placeholder="Enter your position" name="position" class="form-control">
            <label class="page-label">School</label>
            <input type="text" placeholder="Enter your school" name="school" class="form-control">
            <input type="hidden" placeholder="d" name="sec" class="form-control">
            <label class="page-label">Username</label>
            <input type="text" placeholder="Enter your username" name="username" class="form-control">
            <label class="page-label">Password</label>
            <input type="password" placeholder="Enter your password" name="pw" class="form-control">
            <label class="page-label">Confirm Password</label>
            <input type="password" placeholder="Confirm the password" name="cpw" class="form-control">
            <br>
            <button type="submit" class="btn btn-success">Register</button>
        </form>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    </div>
</div>
<?php include 'include/footer.php' ?>
