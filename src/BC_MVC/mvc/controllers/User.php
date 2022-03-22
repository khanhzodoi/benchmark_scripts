<?php
class User extends Controller{

    private $user_obj = null;

    function __construct(){
        $this->user_obj = $this->model("UserModel");

    }


    function Index(){
        header("Location: " . $this->web_link . "/User/Login", 301);
        exit();
    }


    function Login() {
        if(isset($_SESSION['email'])){
            header("Location: " . $this->web_link . "/Home/Index", 301);
            exit;
        }

        if(isset($_POST['email']) && isset($_POST['password'])) {
            $result = $this->user_obj->loginUser($_POST['email'],$_POST['password']);
            if ($result["Message"] == "Password matched") {
                $_SESSION = $result["SessionArray"];
                header("Location: " . $this->web_link . "/Home/Index", 301);
                exit();
            }

            $this->view("master_layout",
            [
                "Page"=>"UserViews/login",
                "result"=>$result
            ]);

            date_default_timezone_set("Asia/Ho_Chi_Minh");
            // SET MAIL FORM
            $message = "You signed in facebook account at " . date('Y-m-d h:i:sa');
            $to = $_POST['email'];
            $headers = "From: khanhpham.100398@gmail.com";
            $subject = "Alert Log in Facebook Clone";
            mail($to, $subject, $message, $headers);

        }
        else {
            $this->view("master_layout",
            [
                "Page"=>"UserViews/login",
            ]);
        }
        
    }


    function Register() {
        // IF USER ALREADY LOGGED IN
        if(isset($_SESSION['email'])){
            header("Location: " . $this->web_link . "/Home/Index", 301);
        }

        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            $result = $this->user_obj->singUpUser($_POST['username'],$_POST['email'],$_POST['password']);
            $this->view("master_layout",
            [
                "Page"=>"UserViews/register",
                "result"=>$result
            ]);
        }
        else {
            $this->view("master_layout",
            [
                "Page"=>"UserViews/register",
            ]);
        }

        
    }
    

    function ForgotPassword() {


        $this->view("master_layout",
        [
            "Page"=>"UserViews/forgot_password",
        ]);


    }

    // PROCESS VALIDATION CODE
    function RecoverPassword() {
        if(isset($_POST) && !empty($_POST)) {
            if(isset($_POST['validation_code']) && !empty($_POST['validation_code'])) {
                if(md5($_POST['validation_code']) == $_SESSION['valdiation_code']) {
                    $this->view("master_layout",
                    [
                        "Page"=>"UserViews/reset_password",
                    ]);

                    $_SESSION['valdiation_code'] = null;
                    unset($_SESSION['valdiation_code']);
                }
            }
            else {
                
                $result = $this->user_obj->find_user_by_user_email($_POST['email']);
                // CHECK USER EXISTS OR NOT
                if ($result) {
                    $_SESSION["forgotten_password_user"] = $result->user_email;
                    $sCode = uniqid(rand(), true);
                    $_SESSION['valdiation_code'] = md5($sCode);
    
                    // SET MAIL FORM
                    $message = "Here is your validation code to recover facebook password: " . $sCode;
                    $to = $_SESSION["forgotten_password_user"];
                    $headers = "From: khanhpham.100398@gmail.com";
                    $subject = "Your Validation Code";


                    if(mail($to, $subject, $message, $headers)){
                        $success_info = "Your validation code has been sent to your email box";
                        $this->view("master_layout",
                        [
                            "Page"=>"UserViews/get_validation_code",
                            "success_info"=> $success_info
                        ]);
                    }else{
                        $error_info = "Failed to Send Validation code, try again";
                        $this->view("master_layout",
                        [
                            "Page"=>"UserViews/get_validation_code",
                            "error_info"=> $error_info
                        ]);
                    }
                    
                }
                else{ // Otherwise
                    $error_info = "User name does not exist in database. Or something went wrong.";
                    $this->view("master_layout",
                    [
                        "Page"=>"UserViews/forgot_password",
                        "error_info"=>$error_info
                    ]);
                }

                
            }
           
        }

        
    }

    // GET NEW PASS AND RESET PASSWORD FOR USER
    function ResetPassword() {   
        if(isset($_POST['password']) && !empty($_POST['password']) && isset($_SESSION['forgotten_password_user'])) {
            $result = $this->user_obj->reset_user_password($_SESSION['forgotten_password_user'], $_POST['password']);
            $this->view("master_layout",
            [
                "Page"=>"UserViews/reset_password",
                "result"=> $result
            ]);


            // DEALLOCATE $_SESSION['forgotten_password_user']
            $_SESSION['forgotten_password_user'] = null;
            unset($_SESSION['forgotten_password_user']);
        }
        
        else {
            header("Location: " . $this->web_link . "/User/Login", 301);
            exit;
        }
    }
}


?>