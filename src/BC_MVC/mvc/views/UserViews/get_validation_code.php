<!-- main -->
<main class="container">
    <div class="jumbotron">
      <h2 class="text-center">Welcome to Khanh's. Facebook! <br><small>A simple Facebook clone.</small></h2>
    </div>
    <div class="row ">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 offset-col-md-4">
        <?php if(isset($data["success_info"])): ?>
            <div class="alert alert-success">
                <span id="successMessage"><?php echo $data["success_info"] ?></span>
            </div>
            <div class="alert alert-info" role="alert">
                <h4 style="text-align:center">Please get your validation code in your email box.</h4>
            </div>
            <form class="form-signin" action="RecoverPassword" method="POST">
            <div class="form-group">
                <input class="form-control" type="text" name="validation_code" placeholder="Type your validation code here...">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="login" value="Submit">
            </div>
        </form>
        <?php endif ?>

        <?php if(isset($data["error_info"])): ?>
            <div class="alert alert-warning">
                <span id="errorMessage"><?php echo $data["error_info"] ?></span>
                <h4><a href="http://localhost:8888/Facebook_MVC/User/ForgotPassword" class="form_link">Forgot your password?</a></h4>
            </div>
        <?php endif ?>
        
        </div>
    </div>
    
</main>


