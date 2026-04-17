<?php 
include 'include/header.php';
$db = new Database();
$un = $pw = $role = '';
$result = '';

function get_user_rate_limit_store($path){
    if (!file_exists($path)) {
        file_put_contents($path, json_encode([]), LOCK_EX);
    }
    $raw = @file_get_contents($path);
    if (!is_string($raw) || $raw === '') {
        return [];
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function put_user_rate_limit_store($path, $store){
    file_put_contents($path, json_encode($store), LOCK_EX);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    verify_csrf_or_die();

    $un = $db->FilterInput($_POST['username']);
    $pw = $db->FilterInput($_POST['password']);
    $role = $db->FilterInput($_POST['role']);

    if(empty($un) || empty($pw) || empty($role)){
        echo "<script>alert('All fields are required')</script>";
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = hash('sha256', strtolower($un).'|'.$ip);
        $storePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'physics_hub_user_login_limit.json';
        $maxAttempts = 8;
        $lockWindowSeconds = 15 * 60;
        $now = time();

        $rateStore = get_user_rate_limit_store($storePath);
        $record = $rateStore[$key] ?? ['attempts' => 0, 'first' => $now, 'locked_until' => 0];

        if ((int)$record['locked_until'] > $now) {
            $result = 'Too many failed attempts. Please wait 15 minutes and try again.';
        } else {
            if (($now - (int)$record['first']) > $lockWindowSeconds) {
                $record = ['attempts' => 0, 'first' => $now, 'locked_until' => 0];
            }

            $userdata = $db->FetchAllWithCriteria('users', ['username'=>$un, 'user_type'=>$role], 'LIMIT 1');
            if(!empty($userdata) && $db->VerifyPassword($pw, $userdata[0]['password'])){
                if ($db->NeedsRehash($userdata[0]['password'])) {
                    $db->Update('users', ['password' => $db->HashPassword($pw)], ['username' => $userdata[0]['username']]);
                }

                unset($rateStore[$key]);
                put_user_rate_limit_store($storePath, $rateStore);
                session_regenerate_id(true);
                $_SESSION['fulln'] = $userdata[0]['fullname'];
                $_SESSION['usern'] = $userdata[0]['username'];
                $_SESSION['school'] = $userdata[0]['school'];
                $_SESSION['class'] = $userdata[0]['class'];
                $_SESSION['role'] = $userdata[0]['user_type'];
                $_SESSION['picture'] = $userdata[0]['picture'];
                if($userdata[0]['user_type'] == 'staff'){
                    header('Location: staff.php');
                    exit;
                }elseif($userdata[0]['user_type'] == 'student'){
                    header('Location: students.php');
                    exit;
                }
            } else {
                $record['attempts'] = (int)$record['attempts'] + 1;
                if ($record['attempts'] >= $maxAttempts) {
                    $record['locked_until'] = $now + $lockWindowSeconds;
                    $result = 'Too many failed attempts. Please wait 15 minutes and try again.';
                } else {
                    $result = "Invalid Username or password or role";
                }
                $rateStore[$key] = $record;
                put_user_rate_limit_store($storePath, $rateStore);
            }
        }
    }
}

?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h2 class="page-header">Login</h2>
    <p style="color:red; font-weight:bold;"><?= $result ?></p>
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
            <label class="page-label">Username<sup>*</sup></label>
            <input type="text" placeholder="Enter your username" name="username" class="form-control">
            <label class="page-label">Password<sup>*</sup></label>
            <input type="password" placeholder="Enter your password" name="password" class="form-control">
            <label class="page-label">Role<sup>*</sup></label>
            <select name="role" class="form-control">
                <option value="">Choose one</option>
                <option value="student">Student</option>
                <option value="staff">Staff</option>
            </select>
            <br>
            <button type="submit" class="btn btn-success">Login</button>
        </form>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    </div>
</div>
<?php include 'include/footer.php' ?>
