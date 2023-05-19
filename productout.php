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

    <title>Remove Item</title>
    <link href="design/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <script src="bootstrap/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="design/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="fontawesome/css/font-awesome.css">
    <script src="sweetalertresources/sweetalert.js"></script>
    <style>
        #body {
            overflow: auto;
            height: calc(100vh - 56px); /* Subtract the height of the navbar */
            background-color: #ffffff;
        }

        #body::-webkit-scrollbar {
        }

        #body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #body::-webkit-scrollbar-thumb {
            background: #888;
        }

        #body::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .content {
            margin-top: -50px;
            padding: 20px; /* Add some padding to the content section */
        }

        .col-lg-1 {
            background-color: #ffffff;
        }

        body {
            background-color: #343a40;
        }

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

        .sidenav a:hover {
            color: #fff;
            background-color: #212529;
        }

        .main {
            margin-left: 250px;
            padding: 0px;
            background-color: #343a40;
        }
        .bg-secondary{
            background-color: #343a40 !important;
        }
    </style>
</head>
<body onload="loaddata()" class="bg-secondary">
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
            <a class="navbar-brand" href="#"><img src="img/sett.png" width="35px;" alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <span class="fa fa-list"></span> Product List
                            <input class="float-right" style="" onkeyup="myFunction()" placeholder="Search Product" id="searchproduct">
                        </div>
                        <div class="card-body bg-light" id="body">
                            <div id="showproduct"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <span class="fa fa-list-alt"></span> Purchases
                            <input hidden="" class="float-right" type="date" id="mydate" onchange="mydate()" value="<?php echo date('Y-m-d');?>">
                            <label class="float-right" for="">Date: <?php echo date('M d, Y');?></label>
                        </div>
                        <div class="card-body bg-light" id="body">
                            <div id="showhistory"></div>
                            <div id="showmodal"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


<script>
        function loaddata(){
            var dop = $('#mydate').val();
                    $.ajax({
                        type: "POST",
                        url: "allquery.php",
                        async: false,
                        data: {
                            "showproductout": '1'
                        },
                        success: function (x) {
                            $('#showproduct').html(x);
                        }
                    });
                    
                    $.ajax({
                        type: "POST",
                        url: "allquery.php",
                        async: false,
                        data: {
                            "dop" : dop,
                            "showhistory": '1'
                        },
                        success: function (x) {
                            $('#showhistory').html(x);
                        }
                    });
        }
     
        </script>
<script type="text/javascript"> 
    function updatepurchase(productoutid){
        var productoutid = productoutid;
        var updateqty = $('#updateqty').val();
        $.ajax({
                type: "POST",
                url: "allquery.php",
                async: false,
                data: {
                    "updateqty" : updateqty,
                    "productoutid" : productoutid,
                    "updatepurchase": '1'
                },
                success: function (x) {
                     loaddata();
                     myalert();
                }
            });
    }
    function updatequantity(productoutid){
        var productoutid = productoutid;
        $.ajax({
                type: "POST",
                url: "allquery.php",
                async: false,
                data: {
                    "productoutid" : productoutid,
                    "updatequantity": '1'
                },
                success: function (x) {
                     $('#showmodal').html(x);
                }
            });
    }
    function deleteout(productoutid){
        var productoutid = productoutid;
       Swal.fire({
            title: 'Are You Sure?',
            text: "You won't be able to revert this record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                type: "POST",
                url: "allquery.php",
                async: false,
                data: {
                    "productoutid" : productoutid,
                    "deleteout": '1'
                },
                success: function (x) {
                    loaddata();
                }
            });

            }
        })  
    }
     function pay(){
        Swal.fire({
            title: 'Click "DONE!" For Next Transaction',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Done!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                type: "POST",
                url: "allquery.php",
                async: false,
                data: {
                    "pay": '1'
                },
                success: function (x) {
                    loaddata();
                }
            });

            }
        })
    }
    function myalert() {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
    function saveproductout(productid){
         var productid = productid;
         var quantity = $('#qty').val();
         $.ajax({
                        type: "POST",
                        url: "allquery.php",
                        async: false,
                        data: {
                            "quantity" : quantity,
                            "productid" : productid,
                            "saveproductout": '1'
                        },
                        success: function (x) {
                            loaddata();
                            myalert();
                        }
                    });
    }
    function productout(productid){
        var productid = productid;
        $.ajax({
                        type: "POST",
                        url: "allquery.php",
                        async: false,
                        data: {
                            "productid" : productid,
                            "productout": '1'
                        },
                        success: function (x) {
                            $('#showmodal').html(x);
                        }
                    });
    }
    function mydate(){
        loaddata();
    }
    
    
    
    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchproduct");
  filter = input.value.toUpperCase();
  table = document.getElementById("producttableout");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
    </script>   

            </div>
        </div>
    </body>
</html>