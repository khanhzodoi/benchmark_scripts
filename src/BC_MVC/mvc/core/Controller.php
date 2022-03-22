<?php


// Hàm phụ Controller để gọi các controller gọi Models và Views
class Controller {
    protected $web_link = "http://localhost:8080";


    public function model($model){
        require_once "./mvc/models/".$model.".php";
        return new $model;
    }

    public function view($view, $data=[])
    {
        require_once "./mvc/views/".$view.".php";
    }
}

?>