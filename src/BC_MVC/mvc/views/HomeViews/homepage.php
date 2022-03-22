


<!-- nav -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php 
                        echo "Welcome, ". $data['user_name'];
                    ?>                
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo $data["server_url"] . '/Home/Logout'?>">Logout</a>
                </div>                
            </li>
        </ul>
    </div>
</nav>
<!-- ./nav -->


<!-- main -->
<div class="container-fluid">

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
                    
                foreach ($data['serverlist_json_data'] as $platform_key => $instances) {
                    foreach ($instances as $instance_key => $instance_info) {
                        echo "<tr>";
                        echo "<td>". $platform_key ."</td>";
                        echo "<td>". $instance_info["name"] ."</td>";
                        echo "<td>". $instance_info["metadata"]["vCPU"] ."</td>";
                        echo "<td>". $instance_info["metadata"]["RAM"]  ."</td>";
                        echo "<td>". $instance_info["metadata"]["Disk"] ."</td>";
                        echo "<td> <a href=/Home/Chartpage?platform=" . $platform_key . "&instance_type=". $instance_info["name"] . "> " . "Detail" . "</a></td>";
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


<script>
    $(document).ready( function () {
        $('#table_id').DataTable();
    } );
</script>