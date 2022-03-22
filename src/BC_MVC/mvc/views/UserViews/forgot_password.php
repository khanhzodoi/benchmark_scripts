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
            <h4 style="text-align:center">We'll send validation code to your email</h4>
        </div>
        
        <form class="form-signin" action="RecoverPassword" method="POST">
            <div class="form-group">
                <input class="form-control" type="text" name="email" placeholder="Type your login email address here...">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="login" value="Submit">
            </div>
        </form>
        <?php if(isset($data["error_info"])): ?>
            <div class="alert alert-warning">
                <span id="successMessage"><?php echo $data["error_info"] ?></span>
            </div>
        <?php endif ?>
        </div>
    </div>
    
</main>


