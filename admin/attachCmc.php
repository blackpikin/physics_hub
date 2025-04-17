<?php 
include './include/admHeader.php'; 
$db = new Database();
$state1 = '';
$state2 = '';
$state3 = '';
$state4 = '';
$question = '';
$ans1 = '';
$ans2 = '';
$ans3 = '';
$ans4 = '';
$corr = '';
$explanation = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $db->FilterInput($_POST['question']);
    $state1 = $db->FilterInput($_POST['state1']);
    $state2 = $db->FilterInput($_POST['state2']);
    $state3 = $db->FilterInput($_POST['state3']);
    $state4 = $db->FilterInput($_POST['state4']);
    $ans1 = $db->FilterInput($_POST['ans1']);
    $ans2 = $db->FilterInput($_POST['ans2']);
    $ans3 = $db->FilterInput($_POST['ans3']);
    $ans4 = $db->FilterInput($_POST['ans4']);
    $corr = $db->FilterInput($_POST['corr']);
    $explanation = $db->FilterInput($_POST['explanation']);

    // Check if all fields are filled
    if (empty($question) || empty($state1) || empty($state2) || empty($state3) || empty($ans1) || empty($ans2) || empty($ans3) || empty($ans4) || empty($corr) || empty($explanation)) {
        echo "<script>alert('All fields are required')</script>";
    } else {
        // Prepare data for insertion
        $data = [
            'qid' => $_GET['qid'],
            'question' => $question,
            'statement1' => $state1,
            'statement2' => $state2,
            'statement3' => $state3,
            'statement4' => $state4,
            'answer1' => $ans1,
            'answer2' => $ans2,
            'answer3' => $ans3,
            'answer4' => $ans4,
            'correct' => $corr,
            'explanation' => $explanation,
        ];

        // Insert into the database
        $result = $db->Insert('attachments', $data);
        if ($result == 'Successful') {
            echo "<script>alert('Question added successfully')</script>";
            $state1 = '';
            $state2 = '';
            $state3 = '';
            $state4 = '';
            $question = '';
            $ans1 = '';
            $ans2 = '';
            $ans3 = '';
            $ans4 = '';
            $corr = '';
            $explanation = '';
        } else {
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
    <h2 class="page-header">Attach CMC questions to another</h2>
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
        <form action="" method="post" enctype="multipart/form-data">
        <br>
        <label class="page-label">Question</label>
        <textarea name="question" class="form-control" rows="6" cols="30" required><?= $question ?> </textarea>
        <br>
        <label class="page-label">Statement 1</label>
        <input type="text" class="form-control" name="state1" value="<?= $state1 ?>"  required >
        <label class="page-label">Statement 2</label>
        <input type="text" class="form-control" name="state2" value="<?= $state2 ?>"  required >
        <label class="page-label">Statement 3</label>
        <input type="text" class="form-control" name="state3" value="<?= $state3 ?>"  required >
        <label class="page-label">Statement 4</label>
        <input type="text" class="form-control" name="state4" value="<?= $state4 ?>"  required >
        <br>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <br>
        <label class="page-label">Answer 1</label>
        <input type="text" class="form-control" name="ans1" value="<?= $ans1 ?>"  required >
        <label class="page-label">Answer 2</label>
        <input type="text" class="form-control" name="ans2" value="<?= $ans2 ?>" required >
        <label class="page-label">Answer 3</label>
        <input type="text" class="form-control" name="ans3" value="<?= $ans3 ?>"  required >
        <label class="page-label">Answer 4</label>
        <input type="text" class="form-control" name="ans4" value="<?= $ans4 ?>"  required >
        <br>
        <label class="page-label">Correct answer:</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans1"  >Answer 1&nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans2" >Answer 2&nbsp;&nbsp;&nbsp;
        <br>
        <input type="radio" name="corr" value="ans3" >Answer 3&nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans4" >Answer 4&nbsp;&nbsp;&nbsp;
        <br><br>
        <label class="page-label">Explanation for correct answer</label>
        <textarea name="explanation" class="form-control" rows="6" cols="30" required><?= $explanation ?></textarea>
        <br>
        <button type="submit" class="btn btn-success">Submit question</button>&nbsp;&nbsp;&nbsp;
        </form>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
<br>
