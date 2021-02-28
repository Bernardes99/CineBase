<?php
session_start();
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Movie details</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="css/style_movie.css" rel="stylesheet" type="text/css">
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="movie_functions.js"></script>

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
            } elseif ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                echo '<li><a href="admin_profile.php">MANAGEMENT</a></li>';
                echo '<li><a href="logout.php">LOGOUT</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
            }
            ?>
        </div>

    </nav>

    <main>
        <?php
        session_start();

        require_once "connect.php";

        $connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        }

        $filmeid = $_GET['filmeid'];
        $user_id = $_SESSION['user_id'];

        $result = $connection->query("SELECT titulo as Title, pais as Country, ano as Year, imdbscore as IMDb_score, 
                                        sinopse as synopsis, trailerlink as trailer, duracao as duration, 
                                        foto_nome as photo_name, foto_path as photo_path
                                        FROM filme
                                        WHERE filmeid = $filmeid ; ");

        $row = mysqli_fetch_assoc($result);

        $title = $row['Title'];
        $country = $row['Country'];
        $year = $row['Year'];
        $imdbscore = $row['IMDb_score'];
        $synopsis = $row['synopsis'];
        $trailer = $row['trailer'];
        $duration = $row['duration'];
        $poster = $row['poster'];
        $photo_name = $row['photo_name'];
        $photo_path = $row['photo_path'];

        // Valores em session para caso se vá para edit movie
        $_SESSION['filmeid'] = $filmeid;
        $_SESSION['title'] = $title;
        $_SESSION['synopsis'] = $synopsis;
        $_SESSION['trailer'] = $trailer;
        $_SESSION['year'] = $year;
        $_SESSION['imdb_score'] = $imdbscore;
        $_SESSION['duration'] = $duration;
        $_SESSION['director'] = $director;
        $_SESSION['country'] = $country;
        $_SESSION['photo_name'] = $photo_name;
        $_SESSION['photo_path'] = $photo_path;

        // $result_artists = $connection->query("SELECT artista_artistaid as artistaid FROM artista_filme WHERE filme_filmeid = $filmeid ; ");
        $result_artists = $connection->query("SELECT artista.nome, artista.tipo 
                                                FROM artista 
                                                INNER JOIN artista_filme ON artista_filme.artista_artistaid = artista.artistaid
                                                WHERE filme_filmeid = $filmeid ; ");

        $num_of_results = mysqli_num_rows($result_artists);

        $directors = '';
        $cast = '';

        if ($num_of_results > 0) {

            $first_director = true;
            $first_actor = true;

            for ($i = 1; $i <= $num_of_results; $i++) {

                $row = mysqli_fetch_assoc($result_artists);
                
                /* $artistID = $row['artistaid'];
                $result = $connection->query("SELECT nome, tipo FROM artista WHERE artistaid = $artistID ;");

                $row = mysqli_fetch_assoc($result);*/

                $artist_name = $row['nome'];
                $artist_type = $row['tipo'];

                if ($artist_type == 'Director') {
                    if ($first_director) {
                        $directors .= $artist_name;
                        $first_director = false;
                    } else {
                        $directors .= ', ' . $artist_name;
                    }
                } elseif ($artist_type == 'Ator') {
                    if ($first_actor) {
                        $cast .= $artist_name;
                        $first_actor = false;
                    } else {
                        $cast .= ', ' . $artist_name;
                    }
                }
            }
        }
        $_SESSION['cast'] = $cast;
        $_SESSION['directors'] = $directors;

        //$result_genres = $connection->query("SELECT genero_generoid as generoid FROM genero_filme WHERE filme_filmeid = $filmeid ;");
        $result_genres = $connection->query("SELECT genero.generonome 
                                                FROM genero 
                                                INNER JOIN genero_filme ON genero_filme.genero_generoid = genero.generoid
                                                WHERE filme_filmeid = $filmeid ; ");
        $num_of_results = mysqli_num_rows($result_genres);

        $genres = '';

        if ($num_of_results > 0) {

            for ($i = 1; $i <= $num_of_results; $i++) {

                $row = mysqli_fetch_assoc($result_genres);
                
                /*$genreID = $row['generoid'];

                $result = $connection->query(" SELECT generonome FROM genero WHERE generoid = $genreID ;");

                $row = mysqli_fetch_assoc($result);*/

                $genre_name = $row['generonome'];

                if ($i == 1) {
                    $genres .= $genre_name;
                } else {
                    $genres .= ', ' . $genre_name;
                }
            }
        }
        $_SESSION['genres'] = $genres;

        echo
            '<div class="col-container">

            <div class="column-left" id="left1">

                <h1>' . $title . '</h1>

                <div class="flex-container-main">

                    <div class="flex-container">
                        <span class="iconify" id="details" data-icon="ic:baseline-access-time" data-inline="false"></span>
                        <p>' . $duration . ' min</p>
                    </div>

                    <div class="flex-container">
                        <span class="iconify" id="details" data-icon="bx:bx-calendar-alt" data-inline="false"></span>
                        <p>' . $year . '</p>
                    </div>

                    <div class="flex-container">
                        <span class="iconify" id="imdb" data-icon="simple-icons:imdb" data-inline="false"></span>
                        <p>' . $imdbscore . ' / 10</p>
                    </div>

                    <div class="flex-container">
                        <span class="iconify" id="details" data-icon="ant-design:play-circle-filled" data-inline="false"></span>
                        <a class="link-trailer" href = "' . $trailer . '">Trailer</a>
                    </div>
                </div>

                <div class="synopsis-box">
                    <p>' . $synopsis . '</p>
                </div>

            </div>

            <div class="column-right" id="right1">
                <img src="' . $photo_path . '" alt="" title="' . $photo_name . '" />
            </div>
        </div>
        <br>

        <div class="col-container">
            <div class="column-left" id="left2">

                <h3>Countries: ' . $country . '</h3><br>
                <h3>Genres: ' . $genres . '</h3><br>
                <h3>Directors: ' . $directors . '</h3><br>
                <h3>Cast: ' . $cast . '</h3><br>
            </div>
            

            <div class="column-right" id="right2">';

        if ((isset($_SESSION['user_logged'])) && ($_SESSION['user_logged'] == true)) {

            echo '                    
                </div>
                <br>';

            $result = $connection->query("SELECT * FROM utilizador_filme WHERE filme_filmeid = $filmeid AND utilizador_utilizadorid = $user_id ;");
            $num_of_results = mysqli_num_rows($result);

            if ($num_of_results == 0) {
                echo '<button class="fav-button" onclick="addToFavorites_details(' . $filmeid . ')"><span class="iconify" id="heart" data-icon="el:heart-empty" data-inline="false"></span>Add to favorites</button>';
            } else {
                echo '<button class="fav-button" onclick="removeFromFavorites_details(' . $filmeid . ')"><span class="iconify" id="heart" data-icon="el:heart" data-inline="false"></span>Remove from favorites</button>';
            }

            echo '</div>';
        } elseif ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {

            echo
                '<br>
            <button class="fav-button" onclick="edit_movie(' . $filmeid . ')"><span class="iconify" id="heart" data-icon="ic:baseline-edit" data-inline="false"></span>Edit Movie</button>
            <br>';

            $result = $connection->query("SELECT * FROM filme WHERE filmeid = $filmeid AND escondido=0;");
            $num_of_results = mysqli_num_rows($result);

            if ($num_of_results > 0) {
                echo '<button class="fav-button" onclick="hide_movie(' . $filmeid . ')"><span class="iconify" id="heart" data-icon="ant-design:eye-invisible-filled" data-inline="false"></span>Hide Movie</button>';
            } else {
                echo '<button class="fav-button" onclick="putVisible_movie(' . $filmeid . ')"><span class="iconify" id="heart" data-icon="ant-design:eye-filled" data-inline="false"></span></span>Make Visible</button>';
            }

            echo '</div>';
        }
        echo '</div>';

        ?>

    </main>
</body>

</html>