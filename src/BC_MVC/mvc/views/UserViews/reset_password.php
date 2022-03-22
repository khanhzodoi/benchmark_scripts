<!-- main -->
<main class="container">
    <div class="jumbotron">
      <h2 class="text-center">Welcome to Khanh's. Facebook! <br><small>A simple Facebook clone.</small></h2>
    </div>
    <div class="row ">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 offset-col-md-4">
        <div class="alert alert-info" role="alert">
            <h4 style="text-align:center">Reset your password</h4>
        </div>
        
        <form class="form-signin" action="ResetPassword" method="POST">
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Type your new password here...">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="login" value="Submit">
            </div>
        </form>
        <?php if(isset($data["result"]["errorMessage"])): ?>
            <div class="alert alert-warning">
                <span id="errorMessage"><?php echo $data["result"]["errorMessage"] ?></span>
            </div>
        <?php endif ?>
        <?php if(isset($data["result"]["successMessage"])): ?>
            <div class="alert alert-success">
                <span id="successMessage"><?php echo $data["result"]["successMessage"] ?></span>
                <h4><a href="http://localhost:8888/BC_MVC/User/Login" class="form_link">Do you want to login?</a></h4>
            </div>
        <?php endif ?>
        </div>
    </div>
    
</main>

