<?php
session_start();
require 'connection.php';
if (!isset($_SESSION['accountid'])) {
    header("LOCATION: index.php");
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Sales</title>
        <link href="design/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/simple-sidebar.css" rel="stylesheet">
        <link rel="stylesheet" href="fontawesome/css/font-awesome.css">
        <script src="sweetalertresources/sweetalert.js"></script>
        <script src="canvas.js"></script>
        <style>
            .sidenav {
            height: 100vh;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 18px;
            color: #f1f1f1;
            display: block;
            transition: 0.3s;
        }
        .main {
            background-color: #343a40;
            margin-left: 115px;
            padding: 0px;
        }
        .col-12{
            margin-left:115px;
        }
        html{
            background-color:#343a40;
        }
        .row content{
            background-color:#343a40;
        }
        .col-lg-10{
            background-color:#343a40;
        }
        .col-lg-1{
            background-color:#343a40;
        }
        .bg-secondary{
            background-color: #343a40 !important;
        }
        </style>
    </head>
    <body onload="loaddata()" class="bg-secondary">
        <div class="row content">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
            <div class="sidenav">
        <a href="dashboard.php"><i class="fa fa-dashboard mr-2"></i>Dashboard</a>
        <a href="product.php"><i class="fa fa-download mr-2"></i>Add Item</a>
        <a href="productout.php"><i class="fa fa-upload mr-2"></i>Remove Item</a>
        <a href="sales.php"><i class="fa fa-bar-chart mr-2"></i>Profit Sales</a>
        <a href="records.php"><i class="fa fa-bar-chart mr-2"></i>Records</a>
        <a href="#" onclick="confirmLogout();"><i class="fa fa-sign-out mr-2"></i>Logout</a>

<script>
function confirmLogout() {
  var confirmation = confirm("Are you sure you want to log out?");
  if (confirmation) {
    window.location.href = "logout.php"; // Redirect to the logout page
  }
}
</script>

    </div>
    <div class="main">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

   
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        
                    </li>
                </ul>
            </div>
        </nav>
                <div class="" style="">
                    <div class="row">

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <span class="fa fa-bar-chart-o"></span> Sales Graph |
                                    
                                    <span><b>YEAR: </b></span>
                                    <select name="" id="myyear" onchange="myyear()" class="">
                                        <?php
                                        $currentyear = date('Y');
                                    for ($x = $currentyear; $x >= 2009; $x--) {
                                        echo '<option value="'.$x.'">'.$x.'</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                                <div class="card-body bg-light">
                                    <div id="showsalesgraph"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <span class="fa fa-bar-chart-o"></span> Sales Report |
                                    <span><b>FROM: </b></span>
                                    <input class="" type="date" id="mydatefrom" onchange="mydate()" value="<?php echo date('Y-m-d'); ?>">
                                    <span><b>TO: </b></span>
                                    <input class="" type="date" id="mydateto" onchange="mydate()" value="<?php echo date('Y-m-d'); ?>">
                                    <button onclick="searchreport()">Search</button>
                                </div>
                                <div class="card-body bg-light" id="">
                                    <div id="showhistory"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function myyear(){
                        loaddata();
                    }
                    function loaddata() {
                        var myyear = $('#myyear').val();
                        $.ajax({
                            type: "POST",
                            url: "allquery.php",
                            async: false,
                            data: {
                                "myyear" : myyear,
                                "showsalesgraph": '1'
                            },
                            success: function (x) {
                                $('#showsalesgraph').html(x);
                            }
                        });
                    }
                    function searchreport() {
                        var mydatefrom = $('#mydatefrom').val();
                        var mydateto = $('#mydateto').val();
                        var tax = $('#tax').val();
                        $.ajax({
                            type: "POST",
                            url: "allquery.php",
                            async: false,
                            data: {
                                "tax" : tax,
                                "mydatefrom": mydatefrom,
                                "mydateto": mydateto,
                                "showsalesreport": '1'
                            },
                            success: function (x) {
                                $('#showhistory').html(x);
                            }
                        });
                    }
                </script>

                <script src="bootstrap/jquery.js"></script>
                <script src="bootstrap/js/bootstrap.js"></script>
                <script src="design/bootstrap/js/bootstrap.bundle.min.js"></script>
            </div>
        </div>
    </body>
</html>