<?php
session_start();
require 'connection.php';
if (!isset($_SESSION['accountid'])) {
    header("LOCATION: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/font-awesome.css">
    <style>
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
        }
        .card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
        }
        .bg-secondary{
            background-color: #343a40 !important;
        }
    </style>
</head>
<body>
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
        <div class="card">
        <div class="container mt-5">
            <h1>Product Out Records</h1>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="fromDate">From Date:</label>
            <input type="date" class="form-control" id="fromDate" name="fromDate">
        </div>
        <div class="form-group col-md-4">
            <label for="toDate">To Date:</label>
            <input type="date" class="form-control" id="toDate" name="toDate">
        </div>
        <div class="form-group col-md-4">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary">Search</button>
            
        </div>
    </div>
</form>

    </div>
</form>

<?php

$conn = mysqli_connect($host, $username, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fromDate']) && isset($_POST['toDate'])) {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $sql = "SELECT p.productdescription, o.productid, o.quantity, o.dop, o.stat
            FROM tblproductout o
            INNER JOIN tblproducts p ON o.productid = p.productid
            WHERE o.dop BETWEEN '$fromDate' AND '$toDate'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table">';
        echo '<thead><tr><th>Product Name</th><th>Product No.</th><th>Quantity</th><th>Date</th><th>Status</th></tr></thead>';
        echo '<tbody>';
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>".$row["productdescription"]."</td><td>".$row["productid"]."</td><td>".$row["quantity"]."</td><td>".$row["dop"]."</td><td>".$row["stat"]."</td></tr>";
        }   
        echo '</tbody></table>';
    } else {
        echo "<p>No records found</p>";
    }
    } else {
        echo "<p>Please select a date range to search.</p>";
    }

mysqli_close($conn);
?>


</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

                       

