


<!-- main -->
<main class="container d-flex justify-content-center">

  <div class="row ">
    <div class="col-md-4 offset-col-md-4"></div>
    <div class="bg-dark col-md-4 offset-col-md-4">
      <h4>Login to enjoy</h4>
      <!-- login form -->
      <form method="post" action="/User/Login">
        <div class="form-group">
          <input class="form-control" type="text" name="email" placeholder="Email">
        </div>
        <div class="form-group">
          <input class="form-control" type="password" name="password" placeholder="Password">
        </div>
        <div style="text-align:center">
          <h4>Don't have an account yet? <a href="/User/Register" class="form_link">Register</a></h4>
          <h4><a href="/User/ForgotPassword" class="form_link">Forgotten Password?</a></h4>
        </div><br>
        <div class="form-group">
          <input class="btn btn-primary" type="submit" name="login" value="Login">
        </div>
        <?php if(isset($data["result"]["Message"]) && $data["result"]["Message"] != "Password matched" ): ?>
        <div class="alert alert-danger">
          <strong>Failed!</strong> <span id="Message"><?php echo $data["result"]["Message"] ?></span>
        </div>

        <?php elseif(isset($data["result"]["Message"]) && $data["result"]["Message"] === "Password matched"): ?>
        <div class="alert alert-success">
          <strong>Successful!</strong> <span id="successMessage"><?php echo $data["result"]["successMessage"] ?></span>
        </div>
        <?php endif ?>
        
      </form>
      <!-- ./login form -->
    </div>
  </div>
</main>
<!-- ./main -->