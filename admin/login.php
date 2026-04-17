<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    );
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isHttps,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; font-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'self'; form-action 'self'");
}

include 'include/database.php';
$db = new Database();
$un=$pw='';
$result ='';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function get_rate_limit_store($path){
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

function put_rate_limit_store($path, $store){
    file_put_contents($path, json_encode($store), LOCK_EX);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $csrf = $_POST['csrf_token'] ?? '';
    if (!is_string($csrf) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

    $un = $db->FilterInput($_POST['username']);
    $pw = $db->FilterInput($_POST['pass']);
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = hash('sha256', strtolower($un).'|'.$ip);
    $storePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'physics_hub_admin_login_limit.json';
    $maxAttempts = 5;
    $lockWindowSeconds = 15 * 60;
    $now = time();

    $rateStore = get_rate_limit_store($storePath);
    $record = $rateStore[$key] ?? ['attempts' => 0, 'first' => $now, 'locked_until' => 0];

    if ((int)$record['locked_until'] > $now) {
        $result = 'Too many failed attempts. Please wait 15 minutes and try again.';
    } else {
        if (($now - (int)$record['first']) > $lockWindowSeconds) {
            $record = ['attempts' => 0, 'first' => $now, 'locked_until' => 0];
        }

        $res = $db->FetchAllWithCriteria('admins', ['username'=>$un], 'LIMIT 1');

        if(empty($res) || !$db->VerifyPassword($pw, $res[0]['pass'])){
            $record['attempts'] = (int)$record['attempts'] + 1;
            if ($record['attempts'] >= $maxAttempts) {
                $record['locked_until'] = $now + $lockWindowSeconds;
                $result = 'Too many failed attempts. Please wait 15 minutes and try again.';
            } else {
                $result = 'Invalid username or password';
            }
            $rateStore[$key] = $record;
            put_rate_limit_store($storePath, $rateStore);
        } else {
            if ($db->NeedsRehash($res[0]['pass'])) {
                $db->Update('admins', ['pass' => $db->HashPassword($pw)], ['username' => $res[0]['username']]);
            }

            unset($rateStore[$key]);
            put_rate_limit_store($storePath, $rateStore);
            session_regenerate_id(true);
            $_SESSION['int_phy_username'] = $res[0]['username'];
            $_SESSION['int_phy_fullname'] = $res[0]['fullname'];
            $_SESSION['int_phy_phone'] = $res[0]['phone'];
            $_SESSION['int_phy_email'] = $res[0]['email'];
            $_SESSION['int_phy_has_changed'] = $res[0]['has_changed'];
            header('Location: index.php');
            exit;
        }
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
    <title>Interactive MCQ Hub</title>
</head>
<body>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h6 class="main-large-head">The <sup>i</sup>nteractive M<sub>C</sub>Q Hub</h6>
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
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <br>
        <label class="page-label">Username</label>
        <input value="<?= $un ?>" type="text" class="form-control" name="username" required >
        <br>
        <label class="page-label">Password</label>
        <input type="password" class="form-control" name="pass" required >
        <br>
        <button name="submit" type="submit" class="btn btn-success">Login</button>
        </form>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
