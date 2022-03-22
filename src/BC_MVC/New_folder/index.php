

<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">    
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
  
    </head>

    <body>

        <div class="container">
            <div class="jumbotron">
                <h1>Demo</h1>
                <p>This is for instances of all cloud platforms: GCP, AWS, TC ...</p>
            </div>
            <div class="row">
                <div class="col-sm-12">
                   
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>Cloud Platforms</th>
                                <th>Instance Types</th>
                                <th>vCPU</th>
                                <th>RAM</th>
                                <th>Disk size</th>
                                <th>Link here</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            // Read the JSON file 
                            $json = file_get_contents('serverlist.json');

                            // Decode the JSON file
                            $json_data = json_decode($json,true);

                            
                            foreach ($json_data as $platform_key => $instances) {
                                foreach ($instances as $instance_key => $instance_info) {
                                    echo "<tr>";
                                    echo "<td>". $platform_key ."</td>";
                                    echo "<td>". $instance_info["name"] ."</td>";
                                    echo "<td>". $instance_info["vCPU"] ."</td>";
                                    echo "<td>". $instance_info["RAM"]  ."</td>";
                                    echo "<td>". $instance_info["Disk"] ."</td>";
                                    echo "<td> <a href=/Chart/chart.php?platform=" . $platform_key . "&instance_type=". $instance_info["name"] . "> " . "Detail" . "</a></td>";
                                    echo "</tr>";
                                }
                            }
                            
                            ?>
                        </tbody>
                    </table> 
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="chart" style="width:auto; height:300px;"></div> 

                </div>
            </div>
        </div>
        
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable();
        } );
    </script>
</html>