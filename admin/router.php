<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if(!isset($_SESSION['int_phy_username']) || $_SESSION['int_phy_username'] === ''){
    header('Location: ./login.php');
    exit;
}

include './include/database.php';
$db = new Database();

$qid = isset($_GET['qid']) ? (int)$_GET['qid'] : 0;
$qtyp = isset($_GET['qtyp']) ? $db->FilterInput($_GET['qtyp']) : '';
$action = isset($_GET['act']) ? $db->FilterInput($_GET['act']) : '';

if ($qid <= 0) {
    http_response_code(400);
    exit('Invalid question id');
}

$target = null;

if ($qtyp === 'simple' && $action === 'modify') {
    $target = 'addQuestion.php';
} elseif ($qtyp === 'simple2' && $action === 'modify') {
    $target = 'addQuestion2.php';
} elseif ($qtyp === 'cmc' && $action === 'modify') {
    $target = 'addQuestion3.php';
} elseif ($qtyp === 'simple2' && $action === 'attach') {
    $target = 'attachSimple.php';
} elseif ($qtyp === 'cmc' && $action === 'attach') {
    $target = 'attachCmc.php';
}

if ($target === null) {
    http_response_code(400);
    exit('Invalid route');
}

header('Location: '.$target.'?qid='.$qid);
exit;
