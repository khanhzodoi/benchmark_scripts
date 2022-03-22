<?php
// user.php
class UserModel extends Model {
    protected $db_connection;
    protected $user_name;
    protected $user_email;
    protected $user_pass;
    protected $hash_pass;
    
    function __construct(){
        parent::__construct();
        $this->db_connection = $this->db_obj->dbConnection();
    }

    // SING UP USER
    function singUpUser($username, $email, $password){
        try{
            $this->user_name = trim($username);
            $this->user_email = trim($email);
            $this->user_pass = trim($password);
            if(!empty($this->user_name) && !empty($this->user_email) && !empty($this->user_pass)){

                if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) { 
                    $check_email = $this->db_connection->prepare("SELECT * FROM `users` WHERE user_email = ?");
                    $check_email->execute([$this->user_email]);

                    if($check_email->rowCount() > 0) {
                        return ['errorMessage' => 'This Email Address is already registered. Please Try another.'];
                    }
                    else {
                        
                        $user_image = rand(1,12);

                        $this->hash_pass = password_hash($this->user_pass, PASSWORD_DEFAULT);
                        $sql = "INSERT INTO `users` (username, user_email, user_password, user_image) VALUES(:username, :user_email, :user_pass, :user_image)";
            
                        $sign_up_stmt = $this->db_connection->prepare($sql);
                        //BIND VALUES
                        $sign_up_stmt->bindValue(':username',htmlspecialchars($this->user_name), PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_email',$this->user_email, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_pass',$this->hash_pass, PDO::PARAM_STR);


                        // INSERTING RANDOM IMAGE NAME
                        $sign_up_stmt->bindValue(':user_image',$user_image.'.png', PDO::PARAM_STR);
                        $sign_up_stmt->execute();
                        return ['successMessage' => 'You have signed up successfully.'];                   
                    }
                }
                else{
                    return ['errorMessage' => 'Invalid email address!'];
                }    
            }
            else{
                return ['errorMessage' => 'Please fill in all the required fields.'];
            } 
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // LOGIN USER
    function loginUser($email, $password){
        
        try{
            $this->user_email = trim($email);
            $this->user_pass = trim($password);

            $find_email = $this->db_connection->prepare("SELECT * FROM `users` WHERE user_email = ?");
            $find_email->execute([$this->user_email]);
            
            if($find_email->rowCount() === 1){
                $row = $find_email->fetch(PDO::FETCH_ASSOC);

                $match_pass = password_verify($this->user_pass, $row['user_password']);
                if($match_pass){
                    return [
                        'Message' => 'Password matched',
                        'SessionArray' => [
                            'user_id' => $row['id'],
                            'email' => $row['user_email']
                        ]
                    ];
                }
                else{
                    return ['Message' => 'Invalid password'];

                    
                }
                
            }
            else{
                return ['Message' => 'Invalid email address!'];
            }

        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // FIND USER BY ID
    function find_user_by_id($id){
        try{
            $find_user = $this->db_connection->prepare("SELECT * FROM `users` WHERE id = ?");
            $find_user->execute([$id]);
            if($find_user->rowCount() === 1){
                return $find_user->fetch(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    // FIND USER BY USER MAIL
    function find_user_by_user_email($user_email){
        try{
            $this->user_email = trim($user_email);
            if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)){
                $find_user = $this->db_connection->prepare("SELECT * FROM `users` WHERE user_email = ?");
                $find_user->execute([$this->user_email]);
                if($find_user->rowCount() === 1){
                    return $find_user->fetch(PDO::FETCH_OBJ);
                }
                else{
                    return false;
                }
            }
            else {
                return false;
            }
            
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    function all_users($id){
        try{
            $get_users = $this->db_connection->prepare("SELECT id, username, user_image FROM `users` WHERE id != ?");
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    function reset_user_password($email, $new_password) {
        try{
            $this->user_email = trim($email);
            $this->user_pass = trim($new_password);

            if(!empty($this->user_email) && !empty($this->user_pass)) {
                if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) {

                    $check_email = $this->db_connection->prepare("SELECT * FROM `users` WHERE user_email = ?");
                    $check_email->execute([$this->user_email]);

                    if($check_email->rowCount() === 0) {
                        return ['errorMessage' => 'This Email Address is not registered yet. Please Try another.'];
                    }
                    // Hash Password
                    $this->hash_pass = password_hash($this->user_pass, PASSWORD_DEFAULT);

                    $sql = "UPDATE `users` SET user_password=:user_pass WHERE user_email=:user_email";

                    // Prepare statement
                    $stmt = $this->db_connection->prepare($sql);
        
                    // execute the query
                    //BIND VALUES
                    $stmt->bindValue(':user_email',$this->user_email, PDO::PARAM_STR);
                    $stmt->bindValue(':user_pass',$this->hash_pass, PDO::PARAM_STR);


                    if($stmt->execute()) {
                        return ['successMessage' => 'You have changed your password successfully.']; 
                    }

                    
                }
                else{
                    return ['errorMessage' => 'Invalid email address!'];
                } 

            }
            else {
                return ['errorMessage' => 'Please fill in all the required fields.'];
            }
           
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    function get_user_name() {
        return $this->user_name;
    }
}
?>