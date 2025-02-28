<?php include './include/admHeader.php' ?>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <br>
    <h2 class="page-header">View questions</h2>
    
    <br>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <?php include "./include/admSidebar.php"; ?>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <label class="page-label">Year</label>
            <select name="chapter" class="form-control">
                <option>Choose one</option>
            </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <label class="page-label">Exam</label>
            <select name="chapter" class="form-control">
                <option>Choose one</option>
            </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <label class="page-label">Chapter</label>
            <select name="chapter" class="form-control">
                <option>Choose one</option>
            </select>
            </div>
            
            <table class="table form-table table-striped table-bordered table-responsive" style="margin-top:20px;">
                <tr>
                    <th>Question</th>
                    <th>Answer 1</th>
                    <th>Answer 2</th>
                    <th>Answer 3</th>
                    <th>Answer 4</th>
                    <th>Correct answer</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <td>Test question 1</td>
                    <td>answers answers</td>
                    <td>answers answers</td>
                    <td>answers answers</td>
                    <td>answers answers</td>
                    <td>Correct answer</td>
                    <td>
                        <button title="Modify this question" type="button" class="btn btn-success fa fa-edit"></button>
                        <button title="Remove this question" type="button" class="btn btn-danger fa fa-trash"></button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
</div>
<br>