<!DOCTYPE html>
<html>
<head>
    <title>Benchmark Cloud Plaforms</title>

    <?php define('PATH', 'http://localhost:8080/src/'); ?>
    <link rel="stylesheet" type="text/css" href="<?=PATH?>css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=PATH?>css/style.css">
    <style>
        #page-container {
            position: relative;
            min-height: 100vh;
        }

        #content-wrap {
            padding-bottom: 2.5rem;    /* Footer height */
        }

        #footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 2.5rem;            /* Footer height */
        }
    </style>
</head>
<body>
    
    <!-- nav -->
    <!-- <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/BC_MVC/User/Login'?>">
                <img src="<?=PATH?>img/facebook.png">
            </a>
            </div>
        </div>
    </nav> -->
    <!-- ./nav -->

    <!-- Render Body-->
    

    <!-- footer -->
    <div id="page-container">
        <div id="content-wrap">
            <?php require_once "./mvc/views/".$data["Page"].".php"?>
        </div>
        <footer id="footer" class="container text-center">
            <ul class="nav nav-pills pull-right">
                <li>Made by Research Cloud group</li>
            </ul>
        </footer>
    </div>
    <!-- ./footer -->

    <!-- script -->
    <script type="text/javascript" src="<?=PATH?>js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?=PATH?>js/bootstrap.js"></script>
</body>