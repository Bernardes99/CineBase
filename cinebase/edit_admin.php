<?php

require_once "connect.php";               
$connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());
}

function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}
session_start();
if ((!isset($_SESSION['admin_logged'])) || ($_SESSION['admin_logged'] == false)) {
    header('Location: index.php');
} 

$id = $_SESSION["admin_id"];

if (isset($_POST['password1'])) {
    //check password
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $flag_everything_OK = true;

    if ((strlen($password1) < 4) || (strlen($password1) > 20)) {
        $flag_everything_OK = false;
        $_SESSION['e_password1'] = "Password under 4 or over 20 characters!";
    }

    if ($password1 != $password2) {
        $flag_everything_OK = false;
        $_SESSION['e_password2'] = "Passwords are different!";
    }

    //$password_hash = password_hash($password1, PASSWORD_DEFAULT);
    $password_hash = $password1;


    //save insterted data
    $_SESSION['input_password1'] = $password1;
    $_SESSION['input_password2'] = $password2;
    $_SESSION['admin_pass'] = $password1;


    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            // Atualizar base de dados
            if ($flag_everything_OK == true) {

                if ($connection->query("UPDATE administrador 
                                    SET administrador.password='$password_hash'
                                    WHERE adminid='$id';")) {

                    header('Location: admin_profile.php');
                } else {
                    throw new Exception($connection->error);
                }
            } else {
                //echo isset($flag_everything_OK);
                //echo $flag_everything_OK;
            }
        }
        $connection->close();
    } catch (Exception $e) {
        echo '<span style="color:red;">Server error! Try later</span>';
        echo '<br />Developer info: ' . $e;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Change password</title>
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <link href="css/style_admin.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>

    <nav>
        <label class="logo">CineBase</label>

        <div class="search-nav">
            <form action="search_movie.php" method="post">
                <input type="text" placeholder="Search..." name="search">
                <input hidden name="search-filter" value="Title">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="nav-options">
            <li><a href="index.php">HOME</a></li>
            <li>
                <form action="search_movie.php" method="post">
                    <input hidden name="search" value="">
                    <input hidden name="search-filter" value="Title">
                    <button type="submit">MOVIES</button>
                </form>
            </li>
            <li><a href="admin_profile.php">MANAGEMENT</a></li>;
            <li><a href="logout.php">LOGOUT</a></li>
        </div>

    </nav>

    <main>

        <div class="wrapper">

            <form class="form-editprofile" method="post">

                <div class="form-header">
                    <h3>Change password</h3>
                </div>

                <!-- Password -->
                <p>Password:</p>
                <input type="password" class="form-input" id="admin" name="password1" value="<?php
                                                                                                if (isset($_SESSION['input_password1'])) {
                                                                                                    echo $_SESSION['input_password1'];
                                                                                                    unset($_SESSION['input_password1']);
                                                                                                } else {
                                                                                                    echo $_SESSION["user_pass"];;
                                                                                                }
                                                                                                ?>" />

                <?php
                if (isset($_SESSION['e_password1'])) {
                    echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
                    unset($_SESSION['e_password']);
                }
                ?>
                <br>
                <br>

                <!-- Repeat password -->
                <p>Repeat password:</p>
                <input type="password" class="form-input" name="password2" value="<?php
                                                                                    if (isset($_SESSION['input_password2'])) {
                                                                                        echo $_SESSION['input_password2'];
                                                                                        unset($_SESSION['input_password2']);
                                                                                    }
                                                                                    ?>" /><br />

                <?php
                if (isset($_SESSION['e_password1'])) {
                    echo '<div class="error">' . $_SESSION['e_password1'] . '</div>';
                    unset($_SESSION['e_password1']);
                } elseif (isset($_SESSION['e_password2'])) {
                    echo '<div class="error">' . $_SESSION['e_password2'] . '</div>';
                    unset($_SESSION['e_password2']);
                }
                ?>
                <br>

                <div class="flex-container">
                    <button class="form-button" type="submit">Save changes</button>
                    <a href="admin_profile.php" class="form-button" id="button-right">Cancel</a>
                </div>

            </form>

        </div>


    </main>

</body>



</html>