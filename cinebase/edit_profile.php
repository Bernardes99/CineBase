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
if ((!isset($_SESSION['user_logged'])) || ($_SESSION['user_logged'] == false)) {
    header('Location: index.php');
    exit();
}

$id = $_SESSION["user_id"];

if (isset($_POST['email'])) {

    //validation flag
    $flag_everything_OK = true;

    //check username
    $username = $_POST['username'];

    //length of username
    if ((strlen($username) < 4) || (strlen($username) > 20)) {
        $flag_everything_OK = false;
        $_SESSION['e_username'] = "Username under 4 or over 20 characters!";
    }

    if (ctype_alnum($username) == false) {
        $flag_everything_OK = false;
        $_SESSION['e_username'] = "Username has to consist of only letters and numbers!";
    }

    //check email
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $flag_everything_OK = false;
        $_SESSION['e_email'] = "Incorrect email address!";
    }

    //check password
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

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

    //check name
    $name = $_POST['name'];
    if ((strlen($name) < 1)) {
        $flag_everything_OK = false;
        $_SESSION['e_name'] = "Name field cannot be empty!";
    }

    //check surname
    $surname = $_POST['surname'];
    if ((strlen($surname) < 1)) {
        $flag_everything_OK = false;
        $_SESSION['e_surname'] = "Surname field cannot be empty!";
    }
    //console_log( $flag_everything_OK );

    //save insterted data
    $_SESSION['input_username'] = $username;
    $_SESSION['input_email'] = $email;
    $_SESSION['input_password1'] = $password1;
    $_SESSION['input_password2'] = $password2;
    $_SESSION['input_name'] = $name;
    $_SESSION['input_surname'] = $surname;

    $_SESSION['user_username'] = $username;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_password'] = $password1;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_surname'] = $surname;

    if (isset($_POST['terms'])) $_SESSION['input_terms'] = true;

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            //check if email exists
            $result = $connection->query("SELECT nomeutilizador FROM utilizador WHERE (email='$email' AND utilizadorid != '$id')");

            if (!$result) throw new Exception($connection->error);

            $count_emails = $result->num_rows;
            //echo $count_emails;
            if ($count_emails > 0) {
                $flag_everything_OK = false;
                $_SESSION['e_email'] = "Account with this email already exists!";
            }

            //check if username taken
            $result = $connection->query("SELECT nomeutilizador FROM utilizador WHERE (nomeutilizador='$username' AND utilizadorid != '$id')");

            if (!$result) throw new Exception($connection->error);

            $count_usernames = $result->num_rows;
            //echo $count_usernames;
            if ($count_usernames > 0) {
                $flag_everything_OK = false;
                $_SESSION['e_username'] = "Username already exists. Pick another one!";
            }

            // Atualizar base de dados
            if ($flag_everything_OK == true) {

                if ($connection->query("UPDATE utilizador 
                                        SET nomeutilizador='$username', email='$email', utilizador.password='$password_hash', primeironome='$name', ultimonome='$surname' 
                                        WHERE utilizadorid='$id';")) {

                    header('Location: user_profile.php');
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
    <title>Edit profile</title>
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <link href="css/style_profile.css" rel="stylesheet" type="text/css">
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
            <li><a href="user_profile.php">PROFILE</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </div>

    </nav>

    <main>

        <div class="wrapper">

            <form class="form-editprofile" method="post">

                <div class="form-header">
                    <h3>Edit profile info</h3>
                </div>

                <div class="content">

                    <div class="flex-container">

                        <!-- First name -->
                        <div class="group" id="field-left">
                            <p>First name:</p>
                            <input type="text" class="form-input" name="name" value="<?php
                                                                                        if (isset($_SESSION['input_name'])) {
                                                                                            echo $_SESSION['input_name'];
                                                                                            unset($_SESSION['input_name']);
                                                                                        } else {
                                                                                            echo $_SESSION["user_name"];;
                                                                                        }
                                                                                        ?>" />

                            <?php
                            if (isset($_SESSION['e_name'])) {
                                echo '<div class="error">' . $_SESSION['e_name'] . '</div>';
                                unset($_SESSION['e_name']);
                            }
                            ?>
                        </div>

                        <!-- Last name -->
                        <div class="group">
                            <p>Last name:</p>
                            <input type="text" class="form-input" name="surname" value="<?php
                                                                                        if (isset($_SESSION['input_surname'])) {
                                                                                            echo $_SESSION['input_surname'];
                                                                                            unset($_SESSION['input_surname']);
                                                                                        } else {
                                                                                            echo $_SESSION["user_surname"];;
                                                                                        }
                                                                                        ?>" />
                            <?php
                            if (isset($_SESSION['e_surname'])) {
                                echo '<div class="error">' . $_SESSION['e_surname'] . '</div>';
                                unset($_SESSION['e_surname']);
                            }
                            ?>
                        </div>
                    </div>
                    <br>

                    <div class="flex-container">

                        <!-- Username -->
                        <div class="group" id="field-left">
                            <p>Username:</p>
                            <input type="text" class="form-input" name="username" value="<?php
                                                                                            if (isset($_SESSION['input_username'])) {
                                                                                                echo $_SESSION['input_username'];
                                                                                                unset($_SESSION['input_username']);
                                                                                            } else {
                                                                                                echo $_SESSION["user_username"];;
                                                                                            }
                                                                                            ?>" />
                            <?php
                            if (isset($_SESSION['e_username'])) {
                                echo '<div class="error">' . $_SESSION['e_username'] . '</div>';
                                unset($_SESSION['e_username']);
                            }
                            ?>
                        </div>

                        <!-- Email -->
                        <div class="group">
                            <p>Email:</p>
                            <input type="text" class="form-input" name="email" value="<?php
                                                                                        if (isset($_SESSION['input_email'])) {
                                                                                            echo $_SESSION['input_email'];
                                                                                            unset($_SESSION['input_email']);
                                                                                        } else {
                                                                                            echo $_SESSION["user_email"];;
                                                                                        }
                                                                                        ?>" />

                            <?php
                            if (isset($_SESSION['e_email'])) {
                                echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                                unset($_SESSION['e_email']);
                            }
                            ?>
                        </div>
                    </div>
                    <br>

                    <div class="flex-container">

                        <!-- Password -->
                        <div class="group" id="field-left">
                            <p>Password:</p>
                            <input type="password" class="form-input" name="password1" value="<?php
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
                        </div>

                        <!-- Repeat password -->
                        <div class="group">
                            <p>Repeat password:</p>
                            <input type="password" class="form-input" name="password2" value="<?php
                                                                                                if (isset($_SESSION['input_password2'])) {
                                                                                                    echo $_SESSION['input_password2'];
                                                                                                    unset($_SESSION['input_password2']);
                                                                                                }
                                                                                                ?>" /><br />
                        </div>
                    </div>
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
                        <a href="user_profile.php" class="form-button" id="button-right">Cancel</a>
                    </div>

            </form>

        </div>


    </main>

</body>



</html>