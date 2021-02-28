<?php

session_start();
if ((!isset($_SESSION['admin_logged'])) || ($_SESSION['admin_logged'] == false)) {
    header('Location: index.php');
} 

require_once "connect.php";               
$connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());
}

$email = $_SESSION["admin_email"];
$name = $_SESSION['admin_name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link href="css/style_admin.css" rel="stylesheet" type="text/css">
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

</head>

<body onload="openTab(event, 'management-options')">

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
            <li><a href="admin_profile.php">MANAGEMENT</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </div>

    </nav>

    <main>

        <div class="container">
            <h1>Administrator</h1>
            <span class="iconify" data-icon="bx:bx-shield-quarter" data-inline="false"></span>
        </div>

        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'management-options')">Management Options</button>
            <button class="tablinks" onclick="openTab(event, 'settings')">Session settings</button>
        </div>

        <div id="management-options" class="tabcontent">
            <br>

            <div class="container">
                <a href="manage_users.php" class="admin-button" id="manage">Manage Users</a>
            </div>

            <div class="container">
                <a href="add_movie.php" class="admin-button" id="add">Add Movies</a>
            </div>
            
            <div class="container">
                <form action="search_movie.php" method="post">
                    <input hidden placeholder="Search..." name="search" value="">
                    <input hidden name="search-filter" value="Title">
                    <input hidden name="hidden_movies" value="Hidden only">
                    <button class="admin-button" id="hidden" type="submit">Hidden Movies</button>
                </form>
            </div>
            <br>

        </div>

        <div id="settings" class="tabcontent">

            <p>Administrator Name:</p>
            <div class="container">
                <div class="field"><?php echo $name; ?></div>
            </div>
            <br>

            <p>E-mail:</p>
            <div class="container">
                <div class="field"><?php echo $email; ?></div>
            </div>
            <br>

            <button class="edit-button" type="submit" onclick="window.location='edit_admin.php';">Change password</button>
        </div>

        <script>
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace("active", "");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }
        </script>

    </main>

</body>

</html>