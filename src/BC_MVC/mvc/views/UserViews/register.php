<!-- main -->
<main class="container">
    <div class="jumbotron"><h2 class="text-center">Welcome to Khanh's. Facebook! <br><small>A simple Facebook clone.</small></h2></div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 offet-col-md-4">
            <!-- register form -->
            <form method="post" action="Register">
                <label for="username">Full Name*</label>
                <div class="form-group">
                    <input class="form-control" type="text" name="username" placeholder="Full Name">
                </div>
                <label for="username">Email Address*</label>
                <div class="form-group">
                    <input class="form-control" type="text" name="email" placeholder="Email">
                </div>
                <label for="username">Password*</label>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <input class="btn btn-success" type="submit" name="register" value="Register">
                </div>
                <?php if(isset($data["result"]["errorMessage"])): ?>
                    <div class="alert alert-danger">
                    <strong>Failed!</strong> <span id="errorMessage"><?php echo $data["result"]["errorMessage"] ?></span>
                    </div>
                <?php elseif(isset($data["result"]["successMessage"])): ?>
                    <div class="alert alert-success">
                    <strong>Successful!</strong> <span id="errorMessage"><?php echo $data["result"]["successMessage"] ?></span>
                    </div>
                <?php endif ?>
                <div style="text-align:center">
                    <h4>Already have an account? <a href="/User/Login" class="form_link">Login</a></h4>
                    <h4><a href="/User/ForgotPassword" class="form_link">Forgot your password?</a></h4>
                </div><br>
            </form>
            <!-- ./register form -->
        </div>
    </div>
</main>
<!-- ./main -->