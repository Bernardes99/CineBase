<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBase</title>
    <link href="css/style_main.css" rel="stylesheet" type="text/css">
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
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

            <?php
            if ((isset($_SESSION['user_logged'])) && ($_SESSION['user_logged'] == true)) {
                echo '<li><a href="user_profile.php">PROFILE</a></li>';
                echo '<li><a href="logout.php">LOGOUT</a></li>';
            } 
            elseif ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                echo '<li><a href="admin_profile.php">MANAGEMENT</a></li>';
                echo '<li><a href="logout.php">LOGOUT</a></li>';
            } 
            else {
                echo '<li><a href="login.php">Login</a></li>';
            }
            ?>

        </div>

    </nav>

    <main>

        <h1>Welcome to <span style="color: rgb(162, 137, 247)">CineBase</span>!</h1>

        <form class="wrapper" action="search_movie.php" method="post">

            <input class="search_box" type="text" placeholder="Search..." name="search">
            <button type="submit" value="Search"><i class="fa fa-search"></i></button>

            <div class="check_boxes">

                <div>
                    <input type="radio" id="title" name="search-filter" value="Title" checked>
                    <label for="title">Title</label>
                </div>

                <!--<div>
                    <input type="radio" id="genre" name="search-filter" value="Genre">
                    <label for="genre">Genre</label>
                </div>

                <div>
                    <input type="radio" id="year" name="search-filter" value="Year">
                    <label for="year">Year</label>
                </div>-->

                <div>
                    <input type="radio" id="country" name="search-filter" value="Country">
                    <label for="country">Country</label>
                </div>

                <div>
                    <input type="radio" id="actor" name="search-filter" value="Actor">
                    <label for="actor">Actor</label>
                </div>

                <div>
                    <input type="radio" id="director" name="search-filter" value="Director">
                    <label for="director">Director</label>
                </div>

            </div>

        </form>



    </main>
</body>

</html>