<?php 
include './include/admHeader.php'; 
$db = new Database();
$chapters = $db->Fetch('chapters');
$class = '';
$chapter ='';
$instruct = '';
$picture = '';
$question = '';
$source = '';  
$mock = '';
$year = '';
$ans1 = '';
$ans2 = '';
$ans3 = '';
$ans4 = '';
$corr = '';
$explanation = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf_or_die();

    $class = $db->FilterInput($_POST['class']);
    $chapter = $db->FilterInput($_POST['chapter']);
    $instruct = $db->FilterInput($_POST['instruction']);
    $question = $db->FilterInput($_POST['question']);
    $source = $db->FilterInput($_POST['quesType']);
    $mock = $db->FilterInput($_POST['mock']);
    $year = $db->FilterInput($_POST['year']);
    $ans1 = $db->FilterInput($_POST['ans1']);
    $ans2 = $db->FilterInput($_POST['ans2']);
    $ans3 = $db->FilterInput($_POST['ans3']);
    $ans4 = $db->FilterInput($_POST['ans4']);
    $corr = $db->FilterInput($_POST['corr']);
    $explanation = $db->FilterInput($_POST['explanation']);

    // Handle the picture upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $maxBytes = 2 * 1024 * 1024;

        $fileExtension = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
        $fileMime = mime_content_type($_FILES['picture']['tmp_name']);

        if (!in_array($fileExtension, $allowedExtensions, true) || !in_array($fileMime, $allowedMimeTypes, true)) {
            echo "<script>alert('Invalid image type. Allowed: jpg, jpeg, png, webp')</script>";
            $picture = '';
        } elseif ((int)$_FILES['picture']['size'] > $maxBytes) {
            echo "<script>alert('Image is too large. Maximum size is 2MB')</script>";
            $picture = '';
        } else {
            $uploadDir = '../img/';
            $safeName = 'qimg_'.bin2hex(random_bytes(8)).'.'.$fileExtension;
            $uploadFile = $uploadDir.$safeName;

            if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile)) {
                $picture = $uploadFile;
            } else {
                echo "<script>alert('Failed to upload the picture.')</script>";
                $picture = '';
            }
        }
    } else {
        echo "<script>alert('Please upload a valid picture.')</script>";
        $picture = '';
    }

    // Check if all fields are filled
    if (empty($chapter) || empty($question) || empty($instruct) || empty($picture) || empty($source) || empty($mock) || empty($year) || empty($ans1) || empty($ans2) || empty($ans3) || empty($ans4) || empty($corr) || empty($explanation)) {
        echo "<script>alert('All fields are required')</script>";
    } else {
        // Prepare data for insertion
        $data = [
            'qtype' => 'simple2',
            'class'=> $class,
            'chapter' => $chapter,
            'instruction' => $instruct,
            'picture' => $picture, // Save the file path
            'question' => $question,
            'statement1' => '',
            'statement2' => '',
            'statement3' => '',
            'statement4' => '',
            'qsource' => $source,
            'mock' => $mock,
            'year' => $year,
            'answer1' => $ans1,
            'answer2' => $ans2,
            'answer3' => $ans3,
            'answer4' => $ans4,
            'correct' => $corr,
            'explanation' => $explanation,
            'connected' => 'no'
        ];

        // Insert into the database
        $result = $db->Insert('mcqs', $data);
        if ($result == 'Successful') {
            echo "<script>alert('Question added successfully')</script>";
            $class = '';
            $chapter ='';
            $instruct = '';
            $picture = '';
            $question = '';
            $source = '';  
            $mock = '';
            $year = '';
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
    <h2 class="page-header">Create MCQ questions with picture</h2>
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
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
        <br>
            <label class="page-label">Class</label>
            <select name="class" class="form-control">
                <option value="">Choose one</option>
                <option value="Form 1">Form 1</option>
                <option value="Form 2">Form 2</option>
                <option value="Form 3">Form 3</option>
                <option value="Form 4">Form 4</option>
                <option value="Form 5">Form 5</option>
                <option value="Lower Sixth">Lower Sixth</option>
                <option value="Upper Sixth">Upper Sixth</option>
            </select>
        <br>
            <label class="page-label">Chapter</label>
            <select name="chapter" class="form-control">
                <option>Choose one</option>
                <?php 
                    foreach($chapters as $chap){
                        ?>
                            <option value="<?= $chap['chapter'] ?>"><?= $chap['chapter'] ?></option>
                        <?php
                    }
                    ?>
            </select>
            <br>
        <label class="page-label">Instruction</label>
        <textarea name="instruction" class="form-control" rows="4" cols="30" ><?= $instruct ?></textarea> 
        <br>
        <label class="page-label">Picture</label>
        <input name="picture" type="file" accept=".jpg,.jpeg,.png,.webp" class="form-control" required>
        <br>
        <label class="page-label">Question</label>
        <textarea name="question" class="form-control" rows="6" cols="30" required><?= $question ?></textarea>
        <br>
        <label class="page-label">Question source</label>
        <select name="quesType" class="form-control">
            <option>Choose one</option>
            <option>Mock</option>
            <option>GCE</option>
        </select>
        <div>
            <br>
            <label class="page-label">Mocks</label>
            <select name="mock" class="form-control">
                <option>Choose one</option>
                <option>North west</option>
                <option>South West</option>
                <option>Center</option>
                <option>West</option>
                <option>Adamawa</option>
                <option>South</option>
            </select>
            </div>
            <br>
            <label class="page-label">Year</label>
            <select name="year" class="form-control">
                <option>Choose one</option>
                <?php 
                    for($i = date('Y'); $i >= 1990; $i--){
                        ?>
                            <option><?= $i ?></option>
                        <?php
                    }
                ?>
            </select>
            <br>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
    <br>
        <label class="page-label">Answer 1</label>
        <input type="text" class="form-control" name="ans1" value="<?= $ans1 ?>" required >
        <label class="page-label">Answer 2</label>
        <input type="text" class="form-control" name="ans2" value="<?= $ans2 ?>" required >
        <label class="page-label">Answer 3</label>
        <input type="text" class="form-control" name="ans3" value="<?= $ans3 ?>" required >
        <label class="page-label">Answer 4</label>
        <input type="text" class="form-control" name="ans4" value="<?= $ans4 ?>" required >
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
