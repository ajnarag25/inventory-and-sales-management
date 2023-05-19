<?php
session_start();
require 'connection.php';

if (isset($_SESSION['accountid'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['signup'])) {
    $check_username = mysqli_query($conn, "SELECT * FROM tblaccount WHERE username='$_POST[username]'");
    if (mysqli_num_rows($check_username) > 0) {
        $error = "Username already taken.";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblaccount (username, password) VALUES ('$_POST[username]', '$_POST[password]')");
        if ($query) {
            $_SESSION['success_message'] = "Account successfully created";
        } else {
            $error = "An error occurred while creating your account. Please try again later.";
        }
    }
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
        body {
            background-color: #696969;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Sign Up</h4>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger mb-3" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success mb-3" role="alert">
                                <?php echo $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="signup" class="btn btn-primary btn-block">Sign Up</button>
                            </div>
                            <div class="text-center">
                            <p class="mt-2">Already have an account? <a href="index.php">Log in</a></p>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

