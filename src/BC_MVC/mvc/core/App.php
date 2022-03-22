<?php

class App{
    protected $controller="User";
    protected $action="Login";
    protected $params=[];

    function __construct()
    {

        try {
            // Xử lý url
            $arr = $this->UrlProcess();

            // // Bỏ path đầu tiên sau "/"
            // unset($arr[0][0]);

            // Xu li path thứ 2 để làm Controller
            if(isset($arr[0][0]))
            {
                if(file_exists("./mvc/controllers/".$arr[0][0].".php") )
                {
                    $this->controller = $arr[0][0];
                    
                }
                unset($arr[0][0]);
            }

            // Thêm controller và khởi tạo một instance controller
            require_once "./mvc/controllers/".$this->controller.".php";
            $this->controller = new $this->controller;

            // Xu li Acion
            if(isset($arr[0][1]))
            {
                // Kiểm tra action khong tồn tại trong controller
                //  Trả về 404 - Page Not Found nếu không timf thấy controller trong action
                if(!method_exists($this->controller, $arr[0][1]))
                {
                    echo "<h1>404 - Page Not Found</h1>";
                    exit();
                }
            
                $this->action = $arr[0][1];     
                unset($arr[0][1]);
            }

            // Lấy tất cả params hiện có
            if (isset($arr[1])) {
                $this->params = $arr[1];
            }


            // Tạo biến controller, chạy action trong controller đó và truyền params đã được xử lý ở phía trên
            call_user_func_array([$this->controller, $this->action], $this->params);
        }

        //catch exception
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }

       
    }
    
    // Hàm tiền xử lý Url trước khi lấy thông tin được trong đó
    function UrlProcess()
    {
        global $url_arr;
        $url_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $url_query = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
        

        if(isset($url_path))
        {
            // Loại bỏ khoảng trắng và cắt url theo dấu /
            $url_arr = array(
                explode("/", filter_var(trim($url_path, "/"))),
            );

            // Lấy tất cả param nếu có
            if (isset($url_query))
            {   
                array_push($url_arr, explode("&", filter_var($url_query)));
            }

            return $url_arr;
            
        }


    }
}
?>