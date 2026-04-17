<?php 
include './include/admHeader.php';
$db = new Database();
$chapter ='';
$subject = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    verify_csrf_or_die();

    $subject = $db->FilterInput($_POST['subject']);
    $chapter = $db->FilterInput($_POST['chapter']);
    if(empty($chapter) || empty($subject)){
        echo "<script>alert('All fields are required')</script>";
    } else {
        $is_exist = $db->FetchAllWithCriteria('chapters', ['chapter' => $chapter, 'subject'=>$subject]);
        if(empty($is_exist)){
            $data = [
                'subject' => $subject,
                'chapter'=>$chapter,
            ];
            $result = $db->Insert('chapters', $data);
            if($result == 'Successful'){
                echo "<script>alert('Chapter added successfully')</script>";
            }else{
                echo "<script>alert('$result')</script>";
            }
        }else{
            echo "<script>alert('Chapter already existing in records')</script>";
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
    <h2 class="page-header">Add Chapters to subject</h2>
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
            <label class="page-label">Subject</label>
            <select name="subject" class="form-control">
                <option>Choose one</option>
                <option>Biology</option>
                <option>Chemistry</option>
                <option>Computer Science</option>
                <option>Mathematics</option>
                <option>Physics</option>
            </select>
            <br>
        <label class="page-label">Chapter title</label>
        <input class="form-control" type="text" name="chapter" required>
        <br>
        <button type="submit" class="btn btn-success">Submit chapter</button>
        </form>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
