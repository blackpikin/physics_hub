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

include '../admin/include/database.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!function_exists('csrf_token')) {
    function csrf_token(){
        return $_SESSION['csrf_token'] ?? '';
    }
}

if (!function_exists('verify_csrf_or_die')) {
    function verify_csrf_or_die(){
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            exit('Invalid CSRF token');
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
    <title>Interactive Physics Hub</title>
</head>
<body>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <img src="../img/logo.jpg" alt="" class="logo" >
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
        <br>
        <?php 
            if(!isset($_SESSION['usern']) || $_SESSION['usern'] == ''){

            }else{
                if($_SESSION['role'] == 'staff'){
                    ?>
                        <a class="menu-link" href="create.php" >Create a test</a>
                    <?php
                }
            }
        ?>
        <a class="menu-link" href="take.php" >Take a test</a>
        <a class="menu-link" href="videos.php" >Videos</a>
        <a class="menu-link" href="articles.php" >Articles</a>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <?php 
            if(!isset($_SESSION['usern']) || $_SESSION['usern'] == ''){
                ?>
                    <a class="menu-link" href="./login.php" >Login</a>
                    <a class="menu-link" href="./registerStud.php" >Sign up</a>
                <?php
            }else{
                ?>
                <span class="menu-link" ><?= $_SESSION['usern']  ?></span>
                <a href="logout.php" class="logout">Logout</a>
            <?php
            }
        ?>
        
    </div>
</div>
<hr>
