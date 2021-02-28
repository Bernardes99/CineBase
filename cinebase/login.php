<?php
session_start();
if (((isset($_SESSION['user_logged'])) && ($_SESSION['user_logged'] == true)) || ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true))) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Login</title>
    <link href="css/style_login_register.css" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body>
    <div class="wrapper">

        <form class="form-login" action="logging_to_account.php" method="post">

            <div class="form-header">
                <h3>Login Form</h3>
                <p>Login to access your account</p>
            </div>

            <div class="check-boxes">

                <div class="check-group">
                    <input type="radio" name="user_admin" value="user" checked>
                    <label for="user">User</label>
                </div>
                <div class="check-group">
                    <input type="radio" name="user_admin" value="admin">
                    <label for="admin">Administrator</label>
                </div>

            </div>

            <!-- Email/Username Input -->
            <input type="text" class="form-input" name="input_user_login" placeholder="Email or Username">

            <!-- Password Input -->
            <input type="password" class="form-input" name="input_user_password" placeholder="Password">
           
           <?php
            if (isset($_SESSION['error_log'])) {
                echo '</br>';
                echo $_SESSION['error_log'];
                unset($_SESSION['error_log']);
            }
            ?>

            <!-- Login Button -->
            <button class="form-button" type="submit">Login</button>

            <!-- Back options -->
            <div class="form-footer">
                <a href="register.php">Sign in - Create free account!</a><br>
                <a href="index.php">Back to main</a>
            </div>

           

        </form>

    </div>

</body>

</html>