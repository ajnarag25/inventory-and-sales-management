<?php
session_start();
require 'connection.php';
if (!isset($_SESSION['accountid'])) {
    header("LOCATION: dashboard.php");
    exit(); // Stop further execution
}

// Fetch the user's name from the database
$userId = $_SESSION['accountid'];
$stmt = $conn->prepare("SELECT username FROM tblaccount WHERE accountid = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userName);
$stmt->fetch();
$stmt->close();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>
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
            margin-top: 0px;
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
        .card-body{
            height: 70vh;
            overflow-y: auto;
        }
        .current-date-time {
        float: right;
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
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                <?php
date_default_timezone_set('Asia/Manila');
$currentHour = date('H');

if ($currentHour >= 5 && $currentHour < 12) {
    $greeting = "Good morning! Welcome";
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = "Good afternoon! Welcome";
} else {
    $greeting = "Good evening! Welcome";
}
?>

<span class="navbar-text mr-2" style="color: orange;"><?php echo $greeting; ?> <?php echo $userName; ?></span>

                </li>
            </ul>
        </div>
    </nav>
    <div class="content">

    <div class="row">
    <?php
$monthlySales = 0;
$yesterdaySales = 0;
$dailySales = 0;

// Get monthly sales
$select = mysqli_query($conn, "SELECT SUM((a.sellprice*b.quantity)-(a.amount*b.quantity)) AS total FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE YEAR(b.dop) = YEAR(CURDATE()) AND MONTH(b.dop) = MONTH(CURDATE())");
if ($row = mysqli_fetch_assoc($select)) {
    $monthlySales = $row['total'];
}

// Get yesterday's sales
$yesterday = date('Y-m-d', strtotime('-1 day'));
$select = mysqli_query($conn, "SELECT *, SUM((a.sellprice*b.quantity)-(a.amount*b.quantity)) AS total FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE DATE(b.dop) = '$yesterday'");
if ($row = mysqli_fetch_assoc($select)) {
    $yesterdaySales = $row['total'];
}

// Get today's sales
$today = date('Y-m-d');
$select = mysqli_query($conn, "SELECT *, SUM((a.sellprice*b.quantity)-(a.amount*b.quantity)) AS total FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE DATE(b.dop) = '$today'");
if ($row = mysqli_fetch_assoc($select)) {
    $dailySales = $row['total'];
}

// Get sales records
$salesRecords = array();
$selectRecords = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid ORDER BY b.dop DESC LIMIT 10");
while ($row = mysqli_fetch_assoc($selectRecords)) {
    $salesRecords[] = $row;
}
?>

<div class="col-lg-4">
    <div class="card card-height">
        <div class="card-header">
            <strong>Sales Overview</strong>
            <span class="current-date-time">Time: <?php echo (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('h:iA'); ?></span>
        </div>
        <div class="card-body">
            <p>Total Sales as of <?php echo date('F d, Y'); ?>: <b>₱<?php echo number_format($monthlySales, 2); ?></b></p>
            <p>Yesterday's Sales: <b>₱<?php echo number_format($yesterdaySales, 2); ?></b></p>
            <p>Today's Sales: <b>₱<?php echo number_format($dailySales, 2); ?></b></p>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card card-height">
        <div class="card-header">
            <strong>Sales Records</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salesRecords as $record) { ?>
                        <tr>
                            <td><?php echo $record['dop']; ?></td>
                            <td><?php echo $record['productdescription']; ?></td>
                            <td><?php echo $record['quantity']; ?></td>
                            <td>₱<?php echo number_format(($record['sellprice'] * $record['quantity']) - ($record['amount'] * $record['quantity']), 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="card card-height">
        <div class="card-header">
            <strong>Recently Bought Products</strong>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantity Bought</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT po.quantity, p.productdescription FROM tblproductout po JOIN tblproducts p ON po.productid = p.productid ORDER BY po.dop DESC LIMIT 5";
                    $result = mysqli_query($conn, $sql);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$counter}.</td>";
                            echo "<td>{$row['productdescription']}</td>";
                            echo "<td>{$row['quantity']}</td>";
                            echo "</tr>";
                            $counter++;
                        }
                    } else {
                        echo "<tr><td colspan='3'>No recently bought products.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

        