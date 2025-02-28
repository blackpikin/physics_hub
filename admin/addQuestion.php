<?php include './include/admHeader.php' ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <br>
    <h2 class="page-header">Create MCQ questions without picture</h2>
    <br>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <?php include "./include/admSidebar.php"; ?>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
        <form action="" method="post">
        <br>
            <label class="page-label">Chapter</label>
            <select name="chapter" class="form-control">
                <option>Choose one</option>
            </select>
            <br>
        <label class="page-label">Question</label>
        <textarea class="form-control" rows="8" cols="30" required></textarea>
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
            <select name="quesType" class="form-control">
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
            <select name="quesType" class="form-control">
                <option>Choose one</option>
                <?php 
                    for($i = date('Y'); $i >= 1984; $i--){
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
        <input type="text" class="form-control" name="ans1" required >
        <br>
        <label class="page-label">Answer 2</label>
        <input type="text" class="form-control" name="ans2" required >
        <br>
        <label class="page-label">Answer 3</label>
        <input type="text" class="form-control" name="ans3" required >
        <br>
        <label class="page-label">Answer 4</label>
        <input type="text" class="form-control" name="ans4" required >
        <br>
        <label class="page-label">Correct answer:</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans1"  >&nbsp;Answer 1
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans2" >&nbsp;Answer 2
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="an3" >&nbsp;Answer 3
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="corr" value="ans4" >&nbsp;Answer 4
        &nbsp;&nbsp;&nbsp;
        <br>
        <br>
        <label class="page-label">Explanation for correct answer</label>
        <textarea class="form-control" rows="6" cols="30" required></textarea>
        <br>
        <button type="submit" class="btn btn-success">Submit question</button>
        </form>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
