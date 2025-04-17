<?php 
include './include/admHeader.php'; 
$db = new Database();
$question = '';
$ans1 = '';
$ans2 = '';
$ans3 = '';
$ans4 = '';
$corr = '';
$explanation = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $db->FilterInput($_POST['question']);
    $ans1 = $db->FilterInput($_POST['ans1']);
    $ans2 = $db->FilterInput($_POST['ans2']);
    $ans3 = $db->FilterInput($_POST['ans3']);
    $ans4 = $db->FilterInput($_POST['ans4']);
    $corr = $db->FilterInput($_POST['corr']);
    $explanation = $db->FilterInput($_POST['explanation']);

    // Check if all fields are filled
    if (empty($question) || empty($ans1) || empty($ans2) || empty($ans3) || empty($ans4) || empty($corr) || empty($explanation)) {
        echo "<script>alert('All fields are required')</script>";
    } else {
        // Prepare data for insertion
        $data = [
            'qid' => $_GET['qid'],
            'question' => $question,
            'statement1' => '',
            'statement2' => '',
            'statement3' => '',
            'statement4' => '',
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
    <h2 class="page-header">Attach MCQ question to another</h2>
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
        <br>
        <label class="page-label">Question</label>
        <textarea name="question" class="form-control" rows="8" cols="30" required><?= $question ?></textarea>
        <br>
        <label class="page-label">Answer 1</label>
        <input value="<?= $ans1 ?>" type="text" class="form-control" name="ans1" required >
        <br>
        <label class="page-label">Answer 2</label>
        <input value="<?= $ans2 ?>" type="text" class="form-control" name="ans2" required >
        <br>
        <label class="page-label">Answer 3</label>
        <input value="<?= $ans3 ?>" type="text" class="form-control" name="ans3" required >
        <br>
        <label class="page-label">Answer 4</label>
        <input value="<?= $ans4 ?>" type="text" class="form-control" name="ans4" required >
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <br>
        <label class="page-label">Correct answer:</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans1"  >&nbsp;Answer 1
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans2" >&nbsp;Answer 2
        &nbsp;&nbsp;&nbsp;
        <br>
        <input type="radio" name="corr" value="ans3" >&nbsp;Answer 3
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans4" >&nbsp;Answer 4
        &nbsp;&nbsp;&nbsp;
        <br>
        <br>
        <label class="page-label">Explanation for correct answer</label>
        <textarea name="explanation" class="form-control" rows="6" cols="30" required><?= $explanation ?></textarea>
        <br>
        <button name="submit" type="submit" class="btn btn-success">Submit question</button>
        </form>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
