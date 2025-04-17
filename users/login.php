<?php include '../include/header.php' ?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <h2 class="page-header">Login</h2>
        <form action="" method="post">
            <label class="page-label">Username</label>
            <input type="text" placeholder="Enter your username" name="username" class="form-control">
            <label class="page-label">Password</label>
            <input type="password" placeholder="Enter your password" name="password" class="form-control">
            <label class="page-label">Role</label>
            <select name="role" class="form-control">
                <option value="">Choose one</option>
                <option value="student">Student</option>
                <option value="staff">Staff</option>
            </select>
            <br>
            <button type="submit" class="btn btn-success">Login</button>
        </form>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

    </div>
</div>
<?php include '../include/footer.php' ?>