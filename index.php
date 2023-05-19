<?php
session_start();
require 'connection.php';

if (isset($_SESSION['accountid'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {

    $query = mysqli_query($conn, "SELECT * FROM tblaccount WHERE username='$_POST[username]' AND password='$_POST[password]'");
    $row = mysqli_fetch_assoc($query);
    $num = mysqli_num_rows($query);
    

    $_SESSION['accountid'] = $row['accountid'];
    
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sizzling House Inventory and Sales Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-color:#696969;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Sizzling House Inventory and Sales management</h4>
                        <form method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary btn-block mt-4">Log In</button>
                        </form>
                        <hr>
                        <p class="text-center">Don't have an account? <a href="signup.php" class="card-link">Sign up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success mt-3" role="alert">
            <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>


