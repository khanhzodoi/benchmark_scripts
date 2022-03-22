<?php

class Model {
    protected $db_obj = null;

    public function __construct(){
        $this->db_obj = new Database();
    }

}

?>