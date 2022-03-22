    <style>
            .arrow {
                border: solid gray;
                border-width: 0 3px 3px 0;
                display: inline-block;
                padding: 3px;
            }

            .down {
                transform: rotate(45deg);
                -webkit-transform: rotate(45deg);
            }

            #datepicker {
                width: 276px;
                margin: 0 auto;

            }

    </style>


    <div id="content-wrap">
        <div class="jumbotron text-center" style="margin-bottom: 0px">
            <h1>Instance Benchmark Scores</h1>
        </div>
        <!-- Render Body-->
        <?php require_once "./mvc/views/".$data["Page"].".php"?>
    </div>
    <!-- nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/Home/Homepage">Home<span class="sr-only">(current)</span></a>
                </li>
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
    
    <div style="text-align: center">
        <h4>Year Input</h4>
        <input type="text" class="form-control" name="datepicker" id="datepicker" />
    </div>


    <div class="container-fluid">

    
        <!-- <h2>CPU scores</h2>
        <button type="button" class="btn" data-toggle="collapse" data-target="#row_1"><i class="arrow down"></i></button>
        <div class="row collapse in" id="row_1" >
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_1"  style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_2" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_3" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
            
        </div>

        <hr>
        <h2>Memory - RAM</h2>
        <button type="button" class="btn" data-toggle="collapse" data-target="#row_2"><i class="arrow down"></i></button>
        <div class="row collapse in" id="row_2" >
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_4" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_5" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_6" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
            
        </div>


        <hr>

        <h2>File system</h2>
        <button type="button" class="btn" data-toggle="collapse" data-target="#row_3"><i class="arrow down"></i></button>
        <div class="row collapse in" id="row_3">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_7" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_8" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_9" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
            
        </div>


        <hr>

        <h2>Disk</h2>
        <button type="button" class="btn" data-toggle="collapse" data-target="#row_4"><i class="arrow down"></i></button>
        <div class="row collapse in" id="row_4">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_10" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_11" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Panel Heading</div>
                    <div class="panel-body">
                        <div id="chart_div_12" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>
            
        </div> -->


    </div>
    <script type="text/javascript" src="/src/js/benchmark_details.js"></script>

