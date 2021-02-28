<?php

function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

session_start();

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

    //check if terms accepted
    if (!isset($_POST['terms'])) {
        $flag_everything_OK  = false;
        $_SESSION['e_terms'] = "You have to accept terms and conditions!";
    }

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

    //check if bot
    $secret = "6LeUyjkUAAAAAIGZBg3JXGyONgXZHV4HRa0Zx1YU";

    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);

    $response = json_decode($check);

    if ($response->success == false) {
        $flag_everything_OK = false;
        $_SESSION['e_bot'] = "Prove you are not a robot!";
    }

    //console_log( $flag_everything_OK );

    //save insterted data
    $_SESSION['input_username'] = $username;
    $_SESSION['input_email'] = $email;
    $_SESSION['input_password1'] = $password1;
    $_SESSION['input_password2'] = $password2;
    $_SESSION['input_name'] = $name;
    $_SESSION['input_surname'] = $surname;

    if (isset($_POST['terms'])){
        $_SESSION['input_terms'] = true;
    }

    $_SESSION['user_username'] = $username;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_password'] = $password1;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_surname'] = $surname;
    
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);


    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //check if email exists
            $result = $connection->query("SELECT nomeutilizador FROM utilizador WHERE email='$email'");

            if (!$result) throw new Exception($connection->error);

            $count_emails = $result->num_rows;
            if ($count_emails > 0) {
                $flag_everything_OK = false;
                $_SESSION['e_email'] = "Account with this email already exists!";
            }

            //check if username taken
            $result = $connection->query("SELECT nomeutilizador FROM utilizador WHERE nomeutilizador='$username'");

            if (!$result) throw new Exception($connection->error);

            $count_usernames = $result->num_rows;
            if ($count_usernames > 0) {
                $flag_everything_OK = false;
                $_SESSION['e_username'] = "Username already exists. Pick another one!";
            }

            //console_log( $flag_everything_OK );

            if ($flag_everything_OK == true) {
                //Add to database

                //gerar um novo id
                $result = $connection->query("SELECT COALESCE(max(utilizadorid),0)+1 FROM utilizador;");
                $row = $result->fetch_assoc();

                $new_ID = $row["COALESCE(max(utilizadorid),0)+1"]; //Seleciona o valor resultante

                $sys_date = $connection->query("SELECT SYSDATE()");


                $sql = "INSERT INTO utilizador VALUES('$new_ID', '$email', '$password_hash', '$username', '$name', '$surname', SYSDATE(), FALSE)";
                console_log($sql);


                if ($connection->query("INSERT INTO utilizador VALUES('$new_ID', '$email', '$password_hash', '$username', '$name', '$surname', SYSDATE(), FALSE)")) {

                    $_SESSION["user_id"] = $new_ID;
                    $_SESSION['registration_success'] = true;
                    header('Location: login.php');

                } else {
                    throw new Exception($connection->error);
                }
            }

            $connection->close();
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Server error! Try later</span>';
        echo '<br />Developer info: ' . $e;
    }
}


?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Register</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="css/style_login_register.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body>

    <div class="wrapper">

        <form class="form-register" method="post">

            <div class="form-header">
                <h3>Register Form</h3>
                <p>Create an account to enjoy more features!</p>
            </div>

            <div class="flex-container">

                <!-- First name -->
                <div class="form-group" id="field-left">
                    <input type="text" class="form-input" placeholder="First name" name="name" value="<?php
                                                                                                        if (isset($_SESSION['input_name'])) {
                                                                                                            echo $_SESSION['input_name'];
                                                                                                            unset($_SESSION['input_name']);
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
                <div class="form-group">
                    <input type="text" class="form-input" placeholder="Last name" name="surname" value="<?php
                                                                                                        if (isset($_SESSION['input_surname'])) {
                                                                                                            echo $_SESSION['input_surname'];
                                                                                                            unset($_SESSION['input_surname']);
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

            <!-- Username -->
            <input type="text" class="form-input" placeholder="Username" name="username" value="<?php
                                                                                                if (isset($_SESSION['input_username'])) {
                                                                                                    echo $_SESSION['input_username'];
                                                                                                    unset($_SESSION['input_username']);
                                                                                                }
                                                                                                ?>" />
            <?php
            if (isset($_SESSION['e_username'])) {
                echo '<div class="error">' . $_SESSION['e_username'] . '</div>';
                unset($_SESSION['e_username']);
            }
            ?>

            <!-- Email -->
            <input type="text" class="form-input" placeholder="Email" name="email" value="<?php
                                                                                            if (isset($_SESSION['input_email'])) {
                                                                                                echo $_SESSION['input_email'];
                                                                                                unset($_SESSION['input_email']);
                                                                                            }
                                                                                            ?>" />

            <?php
            if (isset($_SESSION['e_email'])) {
                echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                unset($_SESSION['e_email']);
            }
            ?>

            <div class="flex-container">

                <!-- Password -->
                <div class="form-group" id="field-left">
                    <input type="password" class="form-input" placeholder="Password" name="password1" value="<?php
                                                                                                                if (isset($_SESSION['input_password1'])) {
                                                                                                                    echo $_SESSION['input_password1'];
                                                                                                                    unset($_SESSION['input_password1']);
                                                                                                                }
                                                                                                                ?>" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <input type="password" class="form-input" placeholder="Repeat password" name="password2" value="<?php
                                                                                                                    if (isset($_SESSION['input_password2'])) {
                                                                                                                        echo $_SESSION['input_password2'];
                                                                                                                        unset($_SESSION['input_password2']);
                                                                                                                    }
                                                                                                                    ?>" />
                </div>
            </div>

            <?php
            if (isset($_SESSION['e_password1'])) {
                echo '<div class="error">' . $_SESSION['e_password1'] . '</div>';
                unset($_SESSION['e_password1']);
            }

            elseif (isset($_SESSION['e_password2'])) {
                echo '<div class="error">' . $_SESSION['e_password2'] . '</div>';
                unset($_SESSION['e_password2']);
            }
            ?>
            <br>

            <!-- Terms -->
            <label>
                <input type="checkbox" name="terms" <?php
                                                    if (isset($_SESSION['input_terms'])) {
                                                        echo "checked";
                                                        unset($_SESSION['input_terms']);
                                                    }
                                                    ?> /> Terms and conditions of platform
            </label>
            <br>

            <?php
            if (isset($_SESSION['e_terms'])) {
                echo '<div class="error">' . $_SESSION['e_terms'] . '</div>';
                unset($_SESSION['e_terms']);
            }
            ?>

            <!-- Recaptcha -->
            <div class="form-group-register">
                <div class="g-recaptcha" data-sitekey="6LeUyjkUAAAAAPv0FIf0Rd7TDFlFjDCxmuNh8XBJ"></div> <br>
            </div>

            <?php
            if (isset($_SESSION['e_bot'])) {
                echo '<div class="error">' . $_SESSION['e_bot'] . '</div>';
                unset($_SESSION['e_bot']);
            }
            ?>

            <!-- Submit -->
            <div class="form-group-login">
                <button class="form-button" type="submit">Register</button>
            </div>

            <!-- Back options -->
            <div class="form-footer">
                <a href="login.php">Already have an account? Sign in!</a><br>
                <a href="index.php">Back to main</a>
            </div>

        </form>

    </div>

</body>

</html>