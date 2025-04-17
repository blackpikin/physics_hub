<?php 
include './include/admHeader.php';
$db = new Database();
$years = $db->FetchDistinct('mcqs', 'year', 'ORDER BY year DESC');
$sources = $db->FetchDistinct('mcqs', 'qsource', 'ORDER BY qsource ASC');
$chapters = $db->FetchDistinct('mcqs', 'chapter', 'ORDER BY chapter ASC');
$classes =   $db->FetchDistinct('mcqs', 'class', 'ORDER BY chapter ASC');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['year'] != 'Choose one' && $_POST['exam'] != 'Choose one' && $_POST['chapter'] != 'Choose one' && $_POST['class'] != 'Choose one'){
        $data = [
            'year'=>$_POST['year'],
            'qsource'=>$_POST['exam'],
            'chapter'=>$_POST['chapter'],
            'class'=> $_POST['class']
        ];
        $questions = $db->FetchAllWithCriteria('mcqs', $data);
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
    <h2 class="page-header">View questions</h2>
    
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
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
        <div class="row">
            <form action="" method="post">
           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
           <label class="page-label">Year</label>
            <select name="year" class="form-control">
                <option value="">Choose one</option>
                <?php 
                    foreach($years as $y){
                        ?>
                            <option value="<?= $y['year'] ?>"><?= $y['year'] ?></option>
                        <?php
                    }
                ?>
            </select>
            <label class="page-label">Exam</label>
            <select name="exam" class="form-control">
                <option value="">Choose one</option>
                <?php 
                    foreach($sources as $s){
                        ?>
                            <option value="<?= $s['qsource'] ?>"><?= $s['qsource'] ?></option>
                        <?php
                    }
                ?>
            </select>
           </div>
           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"> 
           <label class="page-label">Chapter</label>
            <select name="chapter" class="form-control">
                <option value="">Choose one</option>
                <?php 
                    foreach($chapters as $c){
                        ?>
                            <option value="<?= $c['chapter'] ?>"><?= $c['chapter'] ?></option>
                        <?php
                    }
                ?>
            </select>
            <label class="page-label">Class</label>
            <select name="class" class="form-control">
                <option value="">Choose one</option>
                <?php 
                    foreach($classes as $c){
                        ?>
                            <option value="<?= $c['class'] ?>"><?= $c['class'] ?></option>
                        <?php
                    }
                ?>
            </select>
            <br>
            <button type="submit" class="fa fa-search btn btn-outline-success form-control"></button>
                </form>
        </div>
        <?php
            if(!empty($questions)){
                ?>
                     <table class="table form-table table-striped table-bordered table-responsive" style="margin-top:20px;">
                <tr>
                    <th>Question</th>
                    <th>Answer 1</th>
                    <th>Answer 2</th>
                    <th>Answer 3</th>
                    <th>Answer 4</th>
                    <th>Correct answer</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                <?php
                    foreach($questions as $q){
                        ?>
                <tr>
                    <td><?= $q['question'] ?></td>
                    <td><?= $q['answer1'] ?></td>
                    <td><?= $q['answer2'] ?></td>
                    <td><?= $q['answer3'] ?></td>
                    <td><?= $q['answer4'] ?></td>
                    <td><?= $q['correct'] ?></td>
                    <td><?= strToUpper($q['qtype']) ?></td>
                    <td>
                        <a href="router.php?qid=<?= $q['id'] ?>&qtyp=<?= $q['qtype'] ?>&act=modify" title="Modify this question" type="button" class="btn btn-success fa fa-edit"></a>
                        <button title="Remove this question" type="button" class="btn btn-danger fa fa-trash"></button>
                        <?php 
                            if($q['qtype'] == 'simple2' && $q['instruction'] != ''){
                                ?>
                                    <a href="router.php?qid=<?= $q['id'] ?>&qtyp=<?= $q['qtype'] ?>&act=attach" title="Attach follow up questions" type="button" class="btn btn-warning fa fa-cog"></a>
                                <?php
                            }
                        ?>
                         <?php 
                            if($q['qtype'] == 'cmc' && $q['instruction'] != ''){
                                ?>
                                    <a href="router.php?qid=<?= $q['id'] ?>&qtyp=<?= $q['qtype'] ?>&act=attach" title="Attach follow up questions" type="button" class="btn btn-warning fa fa-cog"></button>
                                <?php
                            }
                        ?>
                        
                    </td>
                </tr>
                        <?php
                    }
                ?>
            </table>
                <?php
            }
        ?>
    </div>

    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
<br>