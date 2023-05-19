<?php
require 'connection.php';

//category
if(isset($_POST['savecategory'])){
    $insertcategory = mysqli_query($conn, "INSERT INTO tblcategories(catdescription, catfilename) VALUES('$_POST[categoryname]', '$_POST[filename]')");
}

if(isset($_POST['showcategory'])){
    $count = 1;
    $select = mysqli_query($conn, "SELECT * FROM tblcategories");
    echo '<table class="table table-sm table-condensed table-hover" id="categorytable">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 1px;">#</th>
                                <th scope="col">Category Name</th>
                                <th scope="col" style="width: 1px"></th>
                                <th scope="col" style="width: 1px"></th>
                            </tr>
                        </thead>
                        <tbody>';
    while ($row = mysqli_fetch_assoc($select)){
        $countquery = mysqli_query($conn, "SELECT * FROM tblproducts WHERE catid='$row[catid]'");
        $productcnt = mysqli_num_rows($countquery);
    echo '<tr style="cursor: pointer" onclick="showproduct('.$row['catid'].')">
                                <th scope="row"  style="width: 1px">'.$count++.'.</th>
                                <td>'. ucwords($row['catdescription']).'</td>
                                <td><span class="badge badge-info">'.$productcnt.'</span></td>
                                <td  style="width: 1px">
                                <button data-toggle="popover" data-trigger="hover" data-content="Delete Record!" onclick="removecategory('.$row['catid'].')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                                </td>
                            </tr>';
    }
    echo '</tbody>
        </table>';
}
if(isset($_POST['deleteout'])){
    mysqli_query($conn, "DELETE FROM tblproductout WHERE productoutid='$_POST[productoutid]'");
}




if (isset($_POST['showsalesreport'])) {
  $count = 1;
  $totalprice = 0;
  $regular = 0;
  $grantotal = 0;
  $tax = 0;
  $totaltax = 0;
  $select = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE b.dop >= '$_POST[mydatefrom]' AND b.dop <= '$_POST[mydateto]' AND b.stat='out' GROUP BY b.productid");   

  echo '<table class="table table-sm table-condensed table-hover table-bordered" id="producttable">
      <thead class="table-info">
          <tr>
              <th scope="col" style="width: 1px;">#</th>
              <th scope="col">Product Name</th>
              <th scope="col">Price</th>
              <th scope="col">Quantity</th>
              <th scope="col">Total Amount</th>
              <th scope="col">Total Profit</th>
          </tr>
      </thead>
      <tbody>';
      
  while ($row = mysqli_fetch_assoc($select)) {
      $selectout = mysqli_query($conn, "SELECT *, SUM(quantity) AS totalqty FROM tblproductout WHERE productid='$row[productid]'");
      $rowout = mysqli_fetch_assoc($selectout);
      
      $subtotal = ($rowout['totalqty'] * $row['sellprice']) - ($rowout['totalqty'] * $row['amount']);
      $grantotal += $subtotal;
      
      echo '<tr style="cursor: pointer">
          <th scope="row" style="width: 1px">'.$count++.'.</th>
          <td>'.ucwords($row['productdescription']).'</td>
          <td>'.number_format($row['sellprice'], 2).'</td>
          <td>'.$rowout['totalqty'].'</td>
          <td align="right">'.number_format($rowout['totalqty'] * $row['sellprice'], 2).'</td>
          <td align="right">'.number_format($subtotal, 2).'</td>
      </tr>';
  }
  
  echo '<tr>
      <td colspan="4"></td>
      <td align="right"><b>TOTAL</b></td>
      <td align="right"><b>'.number_format($grantotal, 2).'</b></td>
  </tr>';
  
  echo '</tbody>
  </table>';
}


if(isset($_POST['showhistory'])){
    $count = 1;
    $totalprice = 0;
    $select = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE stat=''");   

    echo '<table class="table table-sm table-condensed table-hover table-bordered" id="producttable">
                        <thead class="table-info">
                            <tr>
                                <th scope="col" style="width: 1px;">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total Amount</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>';
    while ($row = mysqli_fetch_assoc($select)){
        $totalprice += $row['quantity']*$row['sellprice'];
    echo '<tr style="cursor: pointer">
                                <th scope="row" style="width: 1px">'.$count++.'.</th>
                                <td>'. ucwords($row['productdescription']).'</td>
                                <td>'. number_format($row['sellprice'],2).'</td>
                                <td>'.$row['quantity'].'</td>
                                <td align="right">'. number_format($row['quantity']*$row['sellprice'],2).'</td>
                                <td  style="width: 1px">
                                    <button data-toggle="popover" data-trigger="hover" data-content="Delete Record!" onclick="deleteout('.$row['productoutid'].')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                                        
                                </td>
                                <td  style="width: 1px">
                                <button data-toggle="popover" data-trigger="hover" data-content="Update Record!" onclick="updatequantity('.$row['productoutid'].')" class="btn btn-sm btn-warning"><span class="fa fa-edit"></span></button>
                                    </td>
                            </tr>';
    }
    echo '<tr>
        <td align="right" colspan="4"><b>TOTAL AMOUNT</b></td>
        <td align="right"><b>'. number_format($totalprice,2).'</b></td>
        <td colspan="2"></td>
    </tr>';
    echo '</tbody>
        </table>';
    echo '<center><button class="btn btn-lg-lg  btn-primary btn-sm float-right" onclick="pay()">Done</button></center>';
}

if (isset($_POST['showproduct'])) {
  $count = 1;
  if ($_POST['categoryid'] == '') {
    $select = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblcategories AS b ON a.catid=b.catid ORDER BY productdescription ASC");
  } else {
    $select = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblcategories AS b ON a.catid=b.catid WHERE b.catid='$_POST[categoryid]' ORDER BY productdescription ASC");
  }
  
  echo '<table class="table table-sm table-condensed table-hover table-bordered table-striped" id="producttable">
          <thead class="">
            <tr>
              <th scope="col" style="width: 1px;">#</th>
              <th scope="col">Product Name</th>
              <th scope="col">Regular Price</th>
              <th scope="col">Sell Price</th>
              <th scope="col">In Stock</th>
              <th scope="col" style="width: 1px"></th>
              <th scope="col" style="width: 1px"></th>
            </tr>
          </thead>
          <tbody>';

  while ($row = mysqli_fetch_assoc($select)) {
    $sumout = mysqli_query($conn, "SELECT SUM(quantity) AS totalout FROM tblproductout WHERE productid='$row[productid]'");
    $sumqty = mysqli_fetch_assoc($sumout);

    echo '<tr style="cursor: pointer">
            <th scope="row" style="width: 1px">' . $count++ . '.</th>
            <td>' . ucwords($row['productdescription']) . '</td>
            <td>PHP ' . number_format($row['amount'], 2) . '</td>
            <td>PHP ' . number_format($row['sellprice'], 2) . '</td>
            <td>' . ucwords($row['instock'] - $sumqty['totalout']) . '</td>
            <td style="width: 1px">
              <button data-toggle="popover" data-trigger="hover" data-content="Delete Record!" onclick="removeproduct(' . $row['productid'] . ')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
            </td>
            <td style="width: 1px">
              <button data-toggle="modal" data-target="#myModalupdate' . $row['productid'] . '" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></button>
              <div class="modal fade" id="myModalupdate' . $row['productid'] . '">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">' . ucwords($row['productdescription']) . '</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <form id="updateForm' . $row['productid'] . '">
                        <label>Product Description</label>
                        <input class="form-control" name="productdescription" value="' . $row['productdescription'] . '" type="text">
                        <label>Amount</label>
                        <input class="form-control" name="amount" value="' . $row['amount'] . '" type="number">
                        <label>Stock Quantity</label>
                        <input class="form-control" name="instock" value="' . $row['instock'] . '" type="number">
                        <label>Sell Price</label>
                        <input class="form-control" name="sellprice" value="' . $row['sellprice'] . '" type="number">
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" onclick="updateproduct(' . $row['productid'] . ')" class="btn btn-primary" data-dismiss="modal">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>';
  }

  echo '</tbody>
        </table>';
}
if(isset($_POST['saveproductout'])){
    mysqli_query($conn, "INSERT INTO `tblproductout`(`productid`, `quantity`, `dop`, stat) VALUES ('$_POST[productid]', '$_POST[quantity]', '".date('Y-m-d')."', '')");
}
if(isset($_POST['updatepurchase'])){
    mysqli_query($conn, "UPDATE tblproductout SET quantity='$_POST[updateqty]' WHERE productoutid='$_POST[productoutid]'");
}
if(isset($_POST['updatequantity'])){
    $selectproduct = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE b.productoutid='$_POST[productoutid]'");
    $rowproduct = mysqli_fetch_assoc($selectproduct);
    echo '<script>$("#myModalupdate").modal("show")</script>';
    echo '<div class="modal fade" id="myModalupdate">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">'. ucwords($rowproduct['productdescription']).'</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <label>Quantity</label>
        <input class="form-control" id="updateqty" value="'.$rowproduct['quantity'].'" type="number">
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" onclick="updatepurchase('.$rowproduct['productoutid'].')" class="btn btn-primary" data-dismiss="modal">Save</button>
      </div>

    </div>
  </div>
</div>';
}
if(isset($_POST['productout'])){
    $selectproduct = mysqli_query($conn, "SELECT * FROM tblproducts WHERE productid='$_POST[productid]'");
    $rowproduct = mysqli_fetch_assoc($selectproduct);
    echo '<script>$("#myModal").modal("show")</script>';
    echo '<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">'. ucwords($rowproduct['productdescription']).'</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <label>Quantity</label>
        <input class="form-control" id="qty" type="number">
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" onclick="saveproductout('.$rowproduct['productid'].')" class="btn btn-primary" data-dismiss="modal">Save</button>
      </div>

    </div>
  </div>
</div>';
}
if(isset($_POST['pay'])){
    mysqli_query($conn, "UPDATE tblproductout SET stat='out' WHERE stat=''");
}
if(isset($_POST['showproductout'])){
    $count = 1;
    $select = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblcategories AS b ON a.catid=b.catid ORDER BY productdescription ASC"); 
    echo '<table class="table table-sm table-condensed table-hover table-bordered" id="producttableout">
                        <thead class="table-info">
                            <tr>
                                <th scope="col" style="width: 1px;">#</th>
                                <th scope="col">Product Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">In Stock</th>
                                <th scope="col" style="width: 1px"></th>
                            </tr>
                        </thead>
                        <tbody>';
    while ($row = mysqli_fetch_assoc($select)){
           $sumout = mysqli_query($conn, "SELECT SUM(quantity) AS totalout FROM tblproductout WHERE productid='$row[productid]'");
           $sumqty = mysqli_fetch_assoc($sumout);
    echo '<tr style="cursor: pointer">
                                <th scope="row" style="width: 1px">'.$count++.'.</th>
                                <td>'. ucwords($row['productdescription']).'</td>
                                <td>PHP '. number_format($row['sellprice'],2).'</td>
                                <td>'. ucwords($row['instock']-$sumqty['totalout']).'</td>
                                <td  style="width: 1px">
                                <button data-toggle="popover" data-trigger="hover" data-content="Product Out!" onclick="productout('.$row['productid'].')" class="btn btn-sm btn-warning"><span class="fa fa-upload"></span></button>
                                </td>
                            </tr>';
    }
    echo '</tbody>
        </table>';
}

if(isset($_POST['saveproduct'])){
    $insert = mysqli_query($conn, "INSERT INTO `tblproducts`(`productdescription`, `productfilename`, `productunit`, `amount`, `instock`, `catid`, `sellprice`) VALUES ('$_POST[productdescription]', '$_POST[filename]', '', '$_POST[price]', '$_POST[quantity]', '$_POST[categoryid]', '$_POST[sellprice]')");
}

if(isset($_POST['selectcategory'])){
    $select = mysqli_query($conn, "SELECT * FROM tblcategories ORDER BY catdescription ASC");
    echo '<select name="" id="categoryid" class="form-control  form-control-sm">';
    while($rowcategory = mysqli_fetch_assoc($select)){
        echo '<option value="'.$rowcategory['catid'].'">'. ucwords($rowcategory['catdescription']).'</option>';
    }
    echo '</select>';
}


if(isset($_POST['removeproduct'])){
    $delete = mysqli_query($conn, "DELETE FROM tblproducts WHERE productid='$_POST[productid]'");
}

if(isset($_POST['removecategory'])){
    $delete = mysqli_query($conn, "DELETE FROM tblcategories WHERE catid='$_POST[categoryid]'");
}

//qrscanner
if(isset($_POST['qrscan'])){
    $count = 1;
    $total = 0;
    $select = mysqli_query($conn, "SELECT * FROM tblproducts AS a INNER JOIN tblorders AS b ON a.productid=b.productid INNER JOIN tblordereditems AS c ON b.orderid=c.orderid WHERE c.qrcode='$_POST[qrcode]'");   
    echo '<table class="table text-white table-sm table-condensed table-hover table-bordered" id="producttable">
                        <thead class="">
                            <tr>
                                <th scope="col" style="width: 1px;">#</th>
                                <th scope="col">Item Name</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Net Price</th>
                            </tr>
                        </thead>
                        <tbody>';
    while ($row = mysqli_fetch_assoc($select)){
        $total += $row['amount']*$row['orderqty'];
    echo '<tr style="cursor: pointer">
                                <th scope="row" style="width: 1px">'.$count++.'.</th>
                                <td>'. ucwords($row['productdescription']).'</td>
                                <td style="text-align: right">'. number_format($row['amount'],2).'</td>
                                <td style="text-align: center">
                                    <div contenteditable class="update" data-id="'. ucwords($row['orderid']).'" data-column="quantity">'. ucwords($row['orderqty']).'</div>
                                </td>
                                <td style="text-align: right">'. number_format($row['amount']*$row['orderqty'],2).'
                                
                                </td>
                            </tr>';
    }
    echo '<input type="text" hidden id="hiddentotal" value="'. number_format($total,2).'">';
    echo '</tbody>
        </table>';
}



if(isset($_POST['insertorder'])){
    $accountid = $_POST['accountid'];
    $productid = $_POST['productid'];
    $date = date('m-d-Y H:i a');
    $quantity = $_POST['quantity'];
    mysqli_query($conn, "INSERT INTO `tblorders`(`accountid`, `productid`, `orderdate`, `orderqty`, `remark`) VALUES ('$accountid','$productid','$date','$quantity','')");
}

if(isset($_POST['updateorderqty'])){
    mysqli_query($conn, "UPDATE tblorders SET orderqty='$_POST[orderqty]' WHERE orderid='$_POST[orderid]'");
}


if(isset($_POST['updateprofile'])){
    $query = mysqli_query($conn, "UPDATE tblaccounts SET imagefile='$_POST[imagefile]' WHERE accountid='$_POST[accountid]'");
}
if(isset($_POST['updateprofiledetails'])){
    $query = mysqli_query($conn, "UPDATE `tblaccounts` SET `phonenumber`='$_POST[phonenumber]', `firstname`='$_POST[firstname]',`lastname`='$_POST[lastname]',`password`='$_POST[password]' WHERE accountid='$_POST[accountid]'");
}
?>

<?php if(isset($_POST['showsalesgraph'])){?>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script>
                        var chart = new CanvasJS.Chart("chartContainer", {
                            animationEnabled: true,
                            theme: "light2",
                            title: {
                                text: "Profit Overview - <?php echo $_POST['myyear'] ?>"
                            },
                            axisY: {
                                title: "",
                                suffix: "",
                                includeZero: false
                            },
                            axisX: {
                                title: "Monthly Graph"
                                
                            },
                            data: [{
                                    type: "column",
                                    yValueFormatString: "#,##0.00#\"\"",
                                    dataPoints: [
<?php
$total = 0;
for ($x = 01; $x <= 12; $x++) {

    $select = mysqli_query($conn, "SELECT *, SUM((a.sellprice*b.quantity)-(a.amount*b.quantity)) AS total FROM tblproducts AS a INNER JOIN tblproductout AS b ON a.productid=b.productid WHERE month(b.dop)='$x' AND year(b.dop)='" . $_POST['myyear'] . "' ");
    while ($row = mysqli_fetch_assoc($select)) {
        $total = $row['total'];
        $date = date_create("$x/27/2020");
        $datecreate = date_format($date, "M");
        echo '{ label: "' . date('M', strtotime($datecreate)) . '", y: ' . number_format($total) . ' },';
    }
}
?>

                                    ]
                                }]
                        });
                        chart.render();
                                        </script>
 <?php }?>


                                                            

<script>
$(document).ready(function(){
  $('[data-toggle="popover"]').popover();   
});
</script>

  <script>
(function ($) {

  $.fn.enableCellNavigation = function () {

    var arrow = {
      left: 37,
      up: 38,
      right: 39,
      down: 40
    };

    this.find('div').keydown(function (e) {

      if ($.inArray(e.which, [arrow.left, arrow.up, arrow.right, arrow.down]) < 0) {
        return;
      }

      var input = e.target;
      var td = $(e.target).closest('td');
      var moveTo = null;

      switch (e.which) {

        case arrow.left:
          {
            if (input.selectionStart == 0) {
              moveTo = td.prev('td:has(div,textarea)');
            }
            break;
          }
        case arrow.right:
          {
            if (input.selectionEnd == input.value.length) {
              moveTo = td.next('td:has(div,textarea)');
            }
            break;
          }

        case arrow.up:
        case arrow.down:
          {

            var tr = td.closest('tr');
            var pos = td[0].cellIndex;

            var moveToRow = null;
            if (e.which == arrow.down) {
              moveToRow = tr.next('tr');
            } else if (e.which == arrow.up) {
              moveToRow = tr.prev('tr');
            }

            if (moveToRow.length) {
              moveTo = $(moveToRow[0].cells[pos]);
            }

            break;
          }

      }

      if (moveTo && moveTo.length) {

        e.preventDefault();

        moveTo.find('div,textarea').each(function (i, input) {
          input.focus();
          input.select();
        });

      }

    });

  };

})(jQuery);


$(function () {
  $('#producttable').enableCellNavigation();
});


</script>