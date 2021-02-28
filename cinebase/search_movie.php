<?php
session_start();
$current_year = date('Y');
$_SESSION['current_year'] = $current_year;

require_once "connect.php";

$connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
    <link href="css/style_search.css" rel="stylesheet" type="text/css">
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <script src="movie_functions.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">

    <!--<script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
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
                    <button class="active" type="submit">MOVIES</button>
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

        require_once "connect.php";

        $connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        }

        //search
        $search = $_POST['search'];
        $search_filter = $_POST['search-filter'];

        if ($search == "") {
            $search_all = 'ALL_MOVIES';
        }

        if ($search_all == 'ALL_MOVIES') {
            echo "<h1>Movies</h1>";
        } else {
            echo "<h1>Search results for: $search</h1>";
        }
        ?>
        <div id="horizontal"></div><br>

        <!-- FILTROS -->

        <div class="filters-container">
            <form class="flex-container" action="search_movie.php" method="post">

                <?php
                $year_start = $_POST['year_start'];
                $year_end = $_POST['year_end'];
                $score_start = $_POST['score_start'];
                $score_end = $_POST['score_end'];
                $genre_filter = $_POST['filter_genre'];
                $order_by = $_POST['order_by'];

                if ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                    $hidden_movies = $_POST['hidden_movies'];
                }
                ?>

                <!-- ORDER BY -->
                <div class="select">
                    <select name="order_by" id="order_by">
                        <?php
                        if (isset($_POST['order_by'])) {
                            echo '<option value="' . $order_by . '" hidden>' . $order_by . '</option>';
                        } else {
                            echo '<option value="Order by:" hidden>Order by:</option>';
                        }
                        ?>
                        <option value="Order by:" disabled>Order by:</option>
                        <option value="Name (A-Z)">Name (A-Z)</option>
                        <option value="Name (Z-A)">Name (Z-A)</option>
                        <option value="IMDb Score (Asc)">IMDb Score (Asc)</option>
                        <option value="IMDb Score (Desc)">IMDb Score (Desc)</option>
                        <option value="Year (Asc)">Year (Asc)</option>
                        <option value="Year (Desc)">Year (Desc)</option>
                    </select>
                </div>

                <!-- GENERO -->
                <div class="select">
                    <select name="filter_genre" id="filter_genre">
                        <?php
                        if (isset($_POST['filter_genre'])) {
                            echo '<option value="' . $genre_filter . '" hidden>' . $genre_filter . '</option>';
                        } else {
                            echo '<option value="Genre:" hidden>Genre:</option>';
                        }

                        echo '<option value="Genre:" disabled>Genre:</option>';

                        $result = $connection->query("SELECT generonome FROM genero;");

                        $num_of_results = mysqli_num_rows($result);
                        echo $num_of_results;
                        if ($num_of_results > 0) {
                            for ($i = 1; $i <= $num_of_results; $i++) {

                                $row = mysqli_fetch_assoc($result);
                                $genre = $row['generonome'];
                                echo '<option value="' . $genre . '">' . $genre . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- HIDEN -->
                <?php
                if ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                    echo '<div class="select">
                            <select name="hidden_movies" id="hidden_movies">';
                    if (isset($_POST['hidden_movies'])) {
                        echo '<option value="' . $hidden_movies . '" hidden>' . $hidden_movies . '</option>';
                    } else {
                        echo '<option value="All Movies" hidden>All Movies</option>';
                    }

                    echo '  <option value="All Movies">All Movies</option>
                            <option value="Hidden only">Hidden only</option>
                            <option value="Visible only">Visible only</option>

                            </select>

                        </div>';
                } else {
                    unset($_POST['hidden_movies']);
                }
                ?>

                <div class="filters">
                    <p>Year</p>

                    <?php

                    if (!isset($_POST['year_start'])) {
                        $year_start = 1900;
                    }

                    if (!isset($_POST['year_end'])) {
                        $year_end = $current_year;
                    }

                    echo '
                    <div class="filter-text">
                        <span>From:</span>
                        <input type="number" name="year_start" placeholder="From..." min="1900" max="' . $current_year . '" value="' . $year_start . '">
                        <span>To:</span>
                        <input type="number" name="year_end" placeholder="To..." min="1900" max="' . $current_year . '" value="' . $year_end . '">
                    </div>';

                    if ($year_start > $year_end) {
                        $year_start = 1900;
                        $year_end = $current_year;
                        echo '<div class="error"><p>Starting year must be smaller</p><p>than ending year!</p></div>';
                    }
                    ?>
                </div>

                <div class="filters">
                    <p>Rating</p>
                    <?php

                    if (!isset($_POST['score_start'])) {
                        $score_start = 0;
                    }

                    if (!isset($_POST['score_end'])) {
                        $score_end = 10;
                    }

                    echo '
                    <div class="filter-text">
                        <span>From:</span>
                        <input type="number" step=0.1 name="score_start" min="0" max="10" placeholder="From..." value="' . $score_start . '">
                        <span>To:</span>
                        <input type="number" step=0.1 name="score_end" min="0" max="10" placeholder="To..." value="' . $score_end . '">
                    </div>';

                    if ($score_start > $score_end) {
                        $score_start = 0;
                        $score_end = 10;
                        echo '<div class="error"><p>Starting score must be</p><p> smaller than ending score!</p></div>';
                    }

                    ?>
                </div>

                <?php
                echo '
                <div> 
                    <input type="hidden" name="search" value=' . $search . '>
                    <input type="hidden" name="search-filter" value=' . $search_filter . '>
                    <button class="apply-button" name="apply-filters" type="submit">APPLY</button></div>';
                ?>

            </form>

            <form class="container-clear" action="search_movie.php" method="post">
                <?php
                echo '
                    <input type="hidden" name="search" value=' . $search . '>
                    <input type="hidden" name="search-filter" value=' . $search_filter . '>
                    <input type="hidden" name="filter_genre" value="Genre:">
                    <input type="hidden" name="order_by" value="Order by:">
                    <input type="hidden" name="year_start" value=1900>
                    <input type="hidden" name="year_end" value=' . $current_year . '>
                    <input type="hidden" name="score_start" value=0>
                    <input type="hidden" name="score_end" value=10>
                    <button class="apply-button" id="clear" name="clear-filters" type="submit">CLEAR</button>';
                ?>
            </form>
        </div>

        <!-- RESULTADOS -->
        <div class="wrapper">
            <div class=table>
                <?php

                if ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                    $query = "SELECT filme.filmeid, filme.titulo as Title, filme.pais as Country, filme.ano as Year, filme.imdbscore as IMDb_score, filme.escondido, foto_nome, foto_path
                                FROM filme
                                INNER JOIN genero_filme ON genero_filme.filme_filmeid = filme.filmeid
                                INNER JOIN genero ON genero.generoid = genero_filme.genero_generoid
                                WHERE (";

                    if ($hidden_movies == 'Hidden only') {
                        $query .= " filme.escondido = 1) AND (";
                    } elseif ($hidden_movies == 'Visible only') {
                        $query .= " filme.escondido = 0) AND (";
                    }
                } else {
                    $query = "SELECT filme.filmeid, filme.titulo as Title, filme.pais as Country, filme.ano as Year, filme.imdbscore as IMDb_score, filme.escondido, foto_nome, foto_path
                                FROM filme
                                INNER JOIN genero_filme ON genero_filme.filme_filmeid = filme.filmeid
                                INNER JOIN genero ON genero.generoid = genero_filme.genero_generoid
                                WHERE (filme.escondido=0)
                                AND (";
                    /*$query = "SELECT filmeid, titulo as Title, pais as Country, ano as Year, imdbscore as IMDb_score, escondido
                            FROM filme
                            WHERE escondido=0 
                            AND ";*/
                }

                if ($search_filter == 'Title') {
                    $result = $connection->query("SELECT filmeid FROM filme WHERE LOWER(titulo) LIKE LOWER('%$search%') ;");
                }
                /*elseif ($search_filter == 'Genre') {

                    $result_genre = $connection->query("SELECT generoid FROM genero WHERE LOWER(generonome) LIKE LOWER('%$search%') ;");
                    $num_of_results = mysqli_num_rows($result_genre);

                    if ($num_of_results > 0) {
                        $row = mysqli_fetch_assoc($result_genre);
                        $genreID = $row['generoid'];
                    }

                    $result = $connection->query("SELECT filme_filmeid as filmeid FROM genero_filme WHERE genero_generoid = $genreID ;");
                }
                elseif ($search_filter == 'Year') {

                    $result = $connection->query("SELECT filmeid FROM filme WHERE ano = $search ;");
                } */ elseif ($search_filter == 'Country') {

                    $result = $connection->query("SELECT filmeid FROM filme WHERE LOWER(pais) LIKE LOWER('%$search%') ;");
                } elseif ($search_filter == 'Actor') {

                    $result_actor = $connection->query("SELECT artistaid FROM artista WHERE LOWER(nome) LIKE LOWER('%$search%')
                                                                                        AND tipo LIKE 'Ator' ;");
                    $num_of_results = mysqli_num_rows($result_actor);

                    if ($num_of_results > 0) {
                        $row = mysqli_fetch_assoc($result_actor);
                        $actorID = $row['artistaid'];
                    }

                    $result = $connection->query("SELECT filme_filmeid as filmeid FROM artista_filme WHERE artista_artistaid = $actorID ;");
                } elseif ($search_filter == 'Director') {

                    $result_director = $connection->query("SELECT artistaid FROM artista WHERE LOWER(nome) LIKE LOWER('%$search%')
                                                                                            AND tipo LIKE 'Director' ;");
                    $num_of_results = mysqli_num_rows($result_director);

                    if ($num_of_results > 0) {
                        $row = mysqli_fetch_assoc($result_director);
                        $directorID = $row['artistaid'];
                    }

                    $result = $connection->query("SELECT filme_filmeid as filmeid FROM artista_filme WHERE artista_artistaid = $directorID ;");
                }


                $num_of_results = mysqli_num_rows($result);

                if ($num_of_results > 0) {

                    for ($i = 1; $i <= $num_of_results; $i++) {

                        $row = mysqli_fetch_assoc($result);
                        $movieID = $row['filmeid'];

                        if ($i == 1) {
                            $movieID_all .= "filmeid = $movieID ";
                        } else {
                            $movieID_all .= " OR filmeid = $movieID ";
                        }
                    }
                }

                $query .= " $movieID_all) ";

                if (isset($_POST['year_start']) && isset($_POST['year_end'])) {
                    $query .= " AND ( filme.ano BETWEEN $year_start AND $year_end ) ";
                }

                if (isset($_POST['score_start']) && isset($_POST['score_end'])) {
                    $query .= " AND ( filme.imdbscore BETWEEN $score_start AND $score_end ) ";
                }

                if (isset($_POST['filter_genre']) && ($_POST['filter_genre'] != 'Genre:')) {
                    $query .= " AND (LOWER(genero.generonome) LIKE LOWER('$genre_filter')) ";
                }

                $query .= " GROUP BY filme.filmeid ";

                if (isset($_POST['order_by']) && ($_POST['order_by'] != 'Order by:')) {
                    if ($order_by == 'Name (A-Z)') {
                        $query .= " ORDER BY Title ASC; ";
                    } elseif ($order_by == 'Name (Z-A)') {
                        $query .= " ORDER BY Title DESC; ";
                    } elseif ($order_by == 'IMDb Score (Asc)') {
                        $query .= " ORDER BY IMDb_score ASC; ";
                    } elseif ($order_by == 'IMDb Score (Desc)') {
                        $query .= " ORDER BY IMDb_score DESC; ";
                    } elseif ($order_by == 'Year (Asc)') {
                        $query .= " ORDER BY Year ASC; ";
                    } elseif ($order_by == 'Year (Desc)') {
                        $query .= " ORDER BY Year DESC; ";
                    }
                } else {
                    $query .= " ORDER BY filme.filmeid; ";
                }

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

                    if ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                        echo '<th>Hidden</th>';
                    }
                    echo '</tr>';


                    for ($i = 1; $i <= $num_of_results; $i++) {

                        $row = mysqli_fetch_assoc($result);

                        $filmeid = $row['filmeid'];
                        $title = $row['Title'];
                        $country = $row['Country'];
                        $year = $row['Year'];
                        $imdbscore = $row['IMDb_score'];
                        $hidden = $row['escondido'];
                        $movie_photo = $row['foto_nome'];
                        $movie_path = $row['foto_path'];

                        echo
                            '<tr>
                                <td style="display:none;">' . $filmeid . '</td>
                                <td><img src="' . $movie_path . '" alt="" title="' . $movie_photo . '" width=100 margin=4></img></td>
                                <td>' . $title . '</td>
                                <td>' . $country . '</td>
                                <td>' . $year . '</td>
                                <td>' . $imdbscore . '</td>';

                        if ((isset($_SESSION['admin_logged'])) && ($_SESSION['admin_logged'] == true)) {
                            if ($hidden == 0) {
                                $hidden_ = "No";
                            } else {
                                $hidden_ = "Yes";
                            }
                            echo '<td>' . $hidden_ . '</td>';
                        }

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

    </main>
</body>

</html>