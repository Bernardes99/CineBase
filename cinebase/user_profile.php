<?php

session_start();

if (!isset($_SESSION['user_logged']) || ($_SESSION['user_logged'] == false)) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";               
$connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());
}

$username = $_SESSION["user_username"];
$email = $_SESSION["user_email"];
$name = $_SESSION['user_name'];
$surname = $_SESSION['user_surname'];
$password = $_SESSION["user_password"];
$user_id = $_SESSION["user_id"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <link href="css/style_profile.css" rel="stylesheet" type="text/css">
    <script src="movie_functions.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body onload="openTab(event, 'Profile information')">

    <nav>
        <label class="logo">CineBase</label>

        <div class="search-nav">
            <form action="search_movie.php">
                <input type="text" placeholder="Search..." name="search">
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

        <h1>Your profile, <?php echo $name ?>!</h1>
        <h2>@<?php echo $username ?></h2>

        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Profile information')">Profile information</button>
            <button class="tablinks" onclick="openTab(event, 'Favorites')">Favorites</button>
        </div>

        <div id="Profile information" class="tabcontent">

            <div class="content">

                <div class="flex-container">
                    <div class="group" id="field-left">
                        <p>First name:</p>
                        <div class="field"><?php echo $name; ?></div>
                    </div>
                    <div class="group">
                        <p>Last name:</p>
                        <div class="field"><?php echo $surname; ?></div>
                    </div>
                </div>

                <div class="flex-container">
                    <div class="group" id="field-left">
                        <p>Username:</p>
                        <div class="field"><?php echo $username; ?></div>
                    </div>
                    <div class="group">
                        <p>Email:</p>
                        <div class="field"><?php echo $email; ?></div>
                    </div>
                </div>

                <button class="edit-button" type="submit" onclick="window.location='edit_profile.php';">Edit info or change password</button>
            </div>
        </div>

        <div id="Favorites" class="tabcontent">

            <div class="wrap">
                <div class=table>

                    <?php

                    $query = "SELECT filme.filmeid, filme.titulo as Title, filme.pais as Country, filme.ano as Year, filme.imdbscore as IMDb_score, foto_nome, foto_path
                            FROM filme
                            INNER JOIN utilizador_filme ON filmeid = filme_filmeid
                            WHERE (filme.escondido=0) AND utilizador_utilizadorid = $user_id;";

                    // search      
                    $result = $connection->query($query);
                    $num_of_results = mysqli_num_rows($result);

                    if ($num_of_results > 0) {

                        echo '
                        <table id="filme_table" cellspacing="0" cellpadding="0"> 
                            <tr>    
                                <th> </th>
                                <th>Title</th>
                                <th>Country</th> 
                                <th>Year</th> 
                                <th>IMDb Score</th>';
                        echo '</tr>';


                        for ($i = 1; $i <= $num_of_results; $i++) {

                            $row = mysqli_fetch_assoc($result);

                            $filmeid = $row['filmeid'];
                            $title = $row['Title'];
                            $country = $row['Country'];
                            $year = $row['Year'];
                            $imdbscore = $row['IMDb_score'];
                            $movie_photo = $row['foto_nome'];
                            $movie_path = $row['foto_path'];

                            echo
                                '<tr>
                                <td style="display:none;">' . $filmeid . '</td>
                                <td><img src="' . $movie_path . '" alt="" title="' . $movie_photo . '" width=100 margin=4></img></td>
                                <td>' . $title . '</td>
                                <td>' . $country . '</td>
                                <td>' . $year . '</td>
                                <td>' . $imdbscore . '</td>
                                ';
                            echo
                                '<td><input class=table-button type="button" id="see" value="See details" onclick="seeDetails(this)"></td>
                        </tr>';
                        }

                        echo '</table>';
                    } else {
                        echo "No match found";
                    }


                    ?>
                </div>
            </div>
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