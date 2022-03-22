<?php

class BenchmarkModel extends Model {

    function __construct(){
        $this->json_files_path = __DIR__ . '/../../json_src/server/';
    }

    function getBenchmarkScoreByPlatformAndInstanceType($input_data=[]) {

        if(!isset($input_data["platform"], $input_data["instance_type"], $input_data["year"])) {
            return false;
        }

        // Get serverlist json data from all json files
        $files = glob($this->json_files_path . "/scores/" . $input_data["year"] .'/*.json');

        //Create an empty new array
        $data_array = [];
        $new_data_array = [];

        //Get all the instance data within the same platform from all files in a specific year
        foreach($files as $file){

            $serverlist_json_file =  file_get_contents($file);
            //Decode the json
            $serverlist_json_data = json_decode($serverlist_json_file, true);

            //Add $serverlist_json_data of specific platform to the new array
            $data_array[] = $serverlist_json_data[$input_data["platform"]];
        }
        
        // Retrive data from data_array - all month within the same year
        foreach ($data_array as $monthly_data) {
            foreach ($monthly_data as $monthly_instance_data) {
                if (! strcmp($monthly_instance_data["name"] , $input_data["instance_type"])) {
                    $new_data_array[] = $monthly_instance_data;
                }
                
            }
        }
        
        return $new_data_array;
    }

    // Function to get instance list from all platform
    function getInstanceListOfAllPlatforms() {
        $serverlist_json_file = $this->json_files_path. "serverlist.json";

        if (file_exists($serverlist_json_file)) {
            return file_get_contents($serverlist_json_file);
        }
        return null;
    }

}


?>