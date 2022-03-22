<?php 
class Home extends Controller {

    private $user_obj = null;
    private $benchmark_obj = null;

    function __construct(){
        $this->user_obj = $this->model("UserModel");
        $this->benchmark_obj = $this->model("BenchmarkModel");
        $this->serverlist_json_path = __DIR__ . '/../../src/server/scores/';
    }

    function Index(){
        header("Location: " . $this->web_link . "/Home/Homepage", 301);
        exit;
    }
    
    function Homepage() {
        if (!$this->has_login()) {
            header("Location: " . $this->web_link . "/Home/Logout", 301);
            exit;
        }

        $user_data = $this->user_obj->find_user_by_id($_SESSION['user_id']);
        if($user_data ===  false) {
            header("Location: " . $this->web_link . "/Home/Logout", 301);
            exit;
        }
        // Get serverlist json data
        $serverlist_json_data = json_decode($this->benchmark_obj->getInstanceListOfAllPlatforms(), true);

        $this->view("homepage_layout",
        [
            "Page"=>"HomeViews/homepage",
            "serverlist_json_data" => $serverlist_json_data,
            "user_name" => $user_data -> username,
            "server_url" => $this->web_link
        ]);

    }


    function Logout() {
        global $web_link;
        // // Initialize the session.
        // // If you are using session_name("something"), don't forget it now!
        // session_start();

        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
        header("Location: " . $this->web_link . "/User/Login", 301);

        session_start();
    }

    function Chartpage($platform, $instance_type) {
        if (!$this->has_login()) {
            header("Location: " . $this->web_link . "/Home/Logout", 301);
            exit;
        }
        
        $user_data = $this->user_obj->find_user_by_id($_SESSION['user_id']);
        if($user_data ===  false) {
            header("Location: " . $this->web_link . "/Home/Logout", 301);
            exit;
        }
        // Intialize params
        $platform = explode("=", filter_var($platform))[1];
        $instance_type = explode("=", filter_var($instance_type))[1];

       
        //Create an empty new array
        $data_array = [];
        $new_data_array = [];

        $this->view("chartpage_layout",
        [
            "Page"=>"HomeViews/chartpage",
            "data_array" => $new_data_array,
            "platform" => $platform,
            "instance_type" => $instance_type,
            "user_name" => $user_data -> username,
            "server_url" => $this->web_link
        ]);
    }

    // Function to handle ajax calling from chartpage
    function HandleChartpage() {

        // Check if POST param exists
        if (!isset($_POST['function'], $_POST['instance_type'], $_POST['platform'], $_POST['year'])) {
            echo (json_encode(array()));
            exit;

        }
        // Get all client input data from POST method
        $client_input_data = [
            'function' => $this->validate_input($_POST['function']),
            'instance_type' => $this->validate_input($_POST['instance_type']),
            'platform' => $this->validate_input($_POST['platform']),
            'year' => $this->validate_input($_POST['year']) 
        ];

        // Initialize server output data to send to client
        $server_output_data = array();

        switch($client_input_data['function']) {
            case 'getBenchmarkData':
                if (!$this->benchmark_obj->getBenchmarkScoreByPlatformAndInstanceType($client_input_data)) {
                    $server_output_data["content"] = "No data";
                }
                else {
                    $server_output_data["content"] = $this->benchmark_obj->getBenchmarkScoreByPlatformAndInstanceType($client_input_data);
                }
                break;
        }

        echo (json_encode($server_output_data));

    } 

    // Function to validate and ensure that the input data is not harmful to the server script.
    private function validate_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Function to check if user has login.
    private function has_login() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
            return true;
        }

        return false;
    }
}
?>