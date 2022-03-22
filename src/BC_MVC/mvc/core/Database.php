<?php 

// Database.php
class Database
{
    public function dbConnection() {
        $db_host = "localhost";
        $db_name = "BC";
        $db_username = "root";
        $db_password = "dkmvng";
         
        $dsn_db = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8';
        try{
            $site_db = new PDO($dsn_db, $db_username, $db_password);
            $site_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $site_db;

        }catch (PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }

    

}

?>