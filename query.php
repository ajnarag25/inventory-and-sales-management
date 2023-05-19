<?php
include 'connection.php';
if(isset($_POST['viewproductdetails'])){
    $selectproduct = mysqli_query($conn, "SELECT * FROM tblproducts WHERE productid='$_POST[productid]'");
    $rowproduct = mysqli_fetch_assoc($selectproduct);
    echo '<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #51D3D1;">
                    <h5 class="modal-title" id="exampleModalLongTitle">ITEM DETAILS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
          <div class="col-lg-12">
          <img src="upload/' . $rowproduct['productfilename'] . '" width="100%" style="margin-bottom: 25px;" alt="">';
                       echo ' <h6>'.$rowproduct['productdescription'].'</h6>
                            Price: <span style="color: #51D3D1;">P'. number_format($rowproduct['amount']).'</span>
                                <hr>
                            <center>
                            <div class="row">
                                <div class="col-8" style="padding 19px">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="1" onkeyup="getamount()" id="quantity" style="text-align: right; font-weight: bold" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-sm" type="button" id="minus" value="'.$rowproduct['amount'].'" onclick="minusquantity('.$rowproduct['amount'].')" style="background-color: #dbdbdb"><span class="fa fa-minus" style="font-size: 25px; color: black"></span></button>
                                            <button class="btn btn-sm" type="button" onclick="plusquantity('.$rowproduct['amount'].')" style="background-color: #dbdbdb"><span class="fa fa-plus" style="font-size: 25px; color: black"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4" style="padding 19px">
                                Total: <span id="totalamount" style="color: red;">P</span><span id="totalamount" style="color: red;"></span>
                                </div>
                            </div>
                            <button class="btn btn-info" onclick="addtowishlist('.$rowproduct['productid'].')"><span class="fa fa-heart"></span> Add to Wishlist!</button>
                            </center>
                            <hr>
                            ';
            $getitems = "SELECT * FROM tblproducts WHERE catid='$rowproduct[catid]' AND productid !='$rowproduct[productid]'";
            $rungetitems = mysqli_query($conn, $getitems);
            echo '<label>Also Try:</label>';
            while ($rowitems = mysqli_fetch_assoc($rungetitems)) {
              echo '<div class="row card-body">
                  <div class="col-3">
                    <img src="upload/' . $rowitems['productfilename'] . '" style="width:100%" alt="">
                  </div>
                    <div class="col-6" onclick="viewproductdetails('.$rowitems['productid'].')">  
                        ' . substr(ucwords($rowitems['productdescription']), 0, 15) . '
                        <h5>P' . number_format($rowitems['amount'], 2) . '</h5>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-warning btn-sm" style="font-size: 11px;" onclick="addtowishlist('.$rowitems['productid'].')">Add to Wishlist!</button>
                    </div>
                  </div>';
                    }
              echo '</div>
                </div>    
            </div>
        </div>
    </div>';
}

if(isset($_POST['showwishlist'])){
            $rungetitems = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblorders AS b ON a.productid=b.productid WHERE b.accountid='$_POST[accountid]' AND b.remark=''");
            $totalamount = 0;
            while ($rowitems = mysqli_fetch_assoc($rungetitems)) {
                $totalamount += $rowitems['orderqty'] * $rowitems['amount'];
                echo '<div class="itemcontent">
                    <div class="item">
                        <img src="upload/' . $rowitems['productfilename'] . '" alt="" width="" height="">
                    </div>
                    <div class="item">
                        <p>' . strtoupper($rowitems['productdescription']) . '</p>
                            <p><small>P' . number_format($rowitems['amount'],2) . '</small></p>
                    </div>
                    <div class="item">
                    <div class="quantity"><div class="input-group mb-3">
                                        <input type="text" class="form-control" value="' . $rowitems['orderqty'] . '" onkeyup="inputquantity('.$rowitems['orderid'].')" id="quantity" style="text-align: right; font-weight: bold" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-sm" type="bu tton" id="minus" value="" onclick="minusquantity('.$rowitems['orderid'].')" style="background-color: #dbdbdb"><span class="fa fa-minus" style="font-size: 25px; color: black"></span></button>
                                            <button class="btn btn-sm" type="button" onclick="plusquantity('.$rowitems['orderid'].')" style="background-color: #dbdbdb"><span class="fa fa-plus" style="font-size: 25px; color: black"></span></button>
                                        </div>
                                    </div>
                    </div>
                    <h6>Total: <span>' . number_format($rowitems['orderqty'] * $rowitems['amount'],2) . '</span></h6>
                    </div>
                    </div>';
            }
            if($totalamount <= 0){
                echo '<center><a href="index.php" class="btn btn-sm btn-secondary">Go To Store</a></center>'; 
            }else{
              echo '<div class="card-body" style="text-align:right"><button class="btn btn-sm btn-warning float-left" onclick="checkout()">Checkout</button> Total Amount: <span style="color: red">P' . number_format($totalamount,2) . '</span></div>';  
            }
}

if(isset($_POST['showordered'])){
      $getitems = "SELECT *, SUM(b.orderqty * a.amount) AS myamount, COUNT(orderqty) AS mycount FROM tblproducts AS a INNER JOIN tblorders AS b ON a.productid=b.productid INNER JOIN tblordereditems AS c ON b.orderid=c.orderid WHERE b.accountid='$_POST[accountid]' AND b.remark='1' GROUP BY c.qrcode";
            $rungetitems = mysqli_query($conn, $getitems);
            $totalamount = 0;
            while ($rowitems = mysqli_fetch_assoc($rungetitems)) {
                $totalamount += $rowitems['orderqty'] * $rowitems['amount'];
                $qrcode = $rowitems['qrcode'];
                echo '<div class="itemcontent">
                    <div class="item" style="backgrund-color: red">
                        <span>'.$rowitems['mycount'].' Item(s)</span>
                        <img src="upload/' . $rowitems['productfilename'] . '" alt="" width="" height="">
                    </div>
                    <div class="item">
                    <h6 style="color:red">Total: <span>' . number_format($rowitems['myamount'],2) . '</span></h6>
                        <span>Ordered Date: '.$rowitems['ordereddate'].'</span>
                    </div>
                    <div class="item">
                        <a href="scanqrcode.php?qrcode='.$qrcode.'" class="btn btn-sm btn-info btn-lg float-left"><span class="fa fa-qrcode"></span> View QR</a>
                    </div>
                    </div>';
            }
           
}

if(isset($_POST['minusquantity'])){
    $update = mysqli_query($conn, "UPDATE tblorders SET orderqty=orderqty-1 WHERE orderid='$_POST[orderid]'");
}
if(isset($_POST['plusquantity'])){
    $update = mysqli_query($conn, "UPDATE tblorders SET orderqty=orderqty+1 WHERE orderid='$_POST[orderid]'");
}
if(isset($_POST['updateorderquantity'])){
    $update = mysqli_query($conn, "UPDATE tblorders SET orderqty=$_POST[quantity] WHERE orderid='$_POST[orderid]'");
}

if(isset($_POST['checkout'])){
    $check = mysqli_query($conn, "SELECT * FROM tblorders WHERE accountid='$_POST[accountid]' AND remark=''");
    $qrcode = $_POST['accountid'].date('m-d-Y');
    while($row = mysqli_fetch_assoc($check)){
      $update = mysqli_query($conn, "UPDATE tblorders SET remark='1' WHERE orderid='$row[orderid]'");
      $checkout = mysqli_query($conn, "INSERT INTO `tblordereditems`(`orderid`, `ordereddate`, `qrcode`) VALUES ('$row[orderid]', '".date('m/d/Y H:i a')."', '$qrcode')");  
    }
    
}


if(isset($_POST['showorderedbyqrcode'])){
      $getitems = "SELECT * FROM tblproducts AS a INNER JOIN tblorders AS b ON a.productid=b.productid INNER JOIN tblordereditems AS c ON b.orderid=c.orderid WHERE b.accountid='$_POST[accountid]' AND c.qrcode='$_POST[qrcode]'";
         $rungetitems = mysqli_query($conn, $getitems);
            $totalamount = 0;
            while ($rowitems = mysqli_fetch_assoc($rungetitems)) {
                $totalamount += $rowitems['orderqty'] * $rowitems['amount'];
                echo '<div class="itemcontent">
                    <div class="item">
                        <img src="upload/' . $rowitems['productfilename'] . '" alt="" width="" height="">
                    </div>
                    <div class="item">
                        <p>' . strtoupper($rowitems['productdescription']) . '</p>
                            <p><small>P' . number_format($rowitems['amount'],2) . '</small></p>
                                Qty: '.$rowitems['orderqty'].'
                    </div>
                    <div class="item">
                    <h6>Total: <span>' . number_format($rowitems['orderqty'] * $rowitems['amount'],2) . '</span></h6>
                    </div>
                    </div>';
            }
            echo '<div class="card-body" style="text-align:right">Total Amount: <span style="color: red">P' . number_format($totalamount,2) . '</span></div>';
           
}
?>