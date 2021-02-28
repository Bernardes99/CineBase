<?php

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

if (isset($_POST['title'])) {

    //validation flag
    $flag_everything_OK = true;

    //check validations

    //title 
    $title = $_POST['title'];
    if (strlen($title) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_title'] = "Title space must be filled!";
    }

    //genre
    $genre = $_POST['genre'];
    if (strlen($genre) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_genre'] = "Genre space must be filled!";
    }

    $genre_array  = explode(", ",  $genre); //Os vários generos ficam guardados no array
    //echo "Genero1: $genre_array[0]"; 
    //echo "Genero2: $genre_array[1]";
    //$contagem_genre=count($genre_array);
    //echo "contagem: $contagem_genre";

    //Cast 
    $cast = $_POST['cast'];
    if (strlen($cast) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_cast'] = "Cast space must be filled!";
    }
    $cast_array  = explode(", ",  $cast); //Os vários atores ficam guardados no array

    //Synopsis
    $synopsis = $_POST['synopsis'];
    if (strlen($synopsis) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_synopsis'] = "Synopsis space must be filled!";
    }

    //trailer
    $trailer = $_POST['trailer'];
    if (strlen($trailer) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_trailer'] = "Trailer space must be filled!";
    }

    //Year
    $current_year = date('Y');

    // para o ano ser válido tem que ser um inteiro, não pode ser maior que o ano em que estamos e assumimos que
    // não existem filmes feitos antes de 1900
    $year = $_POST['year'];
    if ($year > $current_year || $year < 1900) { //!is_int($year) || $year_atual
        $flag_everything_OK = false;
        $_SESSION['e_year'] = "Year not valid!";
    }

    //IMDB
    $imdb_score = $_POST['imdb_score'];
    if ($imdb_score < 0 || $imdb_score > 10) { //!is_numeric($imdb_score) || 
        $flag_everything_OK = false;
        $_SESSION['e_imdb_score'] = "IMDb score space must be a numerical value between 0 and 10!";
    }

    //Duration
    // Assumimos que não há filmes com menos de 20 minutos nem com mais de 500 
    $duration = $_POST['duration'];
    if ($duration < 20 || $duration > 500) {
        $flag_everything_OK = false;
        $_SESSION['e_duration'] = "Invalid film duration!";
    }

    // Director     
    $director = $_POST['director'];
    if (strlen($director) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_director'] = "Director space must be filled!";
    }
    $director_array  = explode(", ",  $director); //Caso haja mais que um diretor ficam guardados no array

    // Country
    $country = $_POST['country'];
    if (strlen($country) < 1) {
        $flag_everything_OK = false;
        $_SESSION['e_country'] = "Country space must be filled!";
    }

    // Movie Photo
    $photo_tmp = $_FILES["movie_photo"]["tmp_name"]; // path atual
    $photo_name = $_FILES["movie_photo"]["name"]; // nome da foto escolhida
    $photo_path = "photos/" . $photo_name;
    if (file_exists($photo_path)) {
        $_SESSION['e_movie_photo'] = "Image already exists!";
        $flag_everything_OK = false;
    }
    if ($photo_name == ""){
        $_SESSION['e_movie_photo'] = "Please select an image!";
        $flag_everything_OK = false;
    }

    //save insterted data
    $_SESSION['input_title'] = $title;
    $_SESSION['input_genre'] = $genre;
    $_SESSION['input_cast'] = $cast;
    $_SESSION['input_synopsis'] = $synopsis;
    $_SESSION['input_trailer'] = $trailer;
    $_SESSION['input_year'] = $year;
    $_SESSION['input_imdb_score'] = $imdb_score;
    $_SESSION['input_duration'] = $duration;
    $_SESSION['input_director'] = $director;
    $_SESSION['input_country'] = $country;
    $_SESSION['input_photo_name'] = $photo_name;
    $_SESSION['input_photo_tmp'] = $photo_tmp;
    $_SESSION['input_photo_path'] = $photo_path;

    if (isset($_POST['terms'])) $_SESSION['input_terms'] = true;

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } 
        else {

            if ($flag_everything_OK == true) {

                $sucess = true;
                //Add to database

                //gerar um novo id para o filme a ser adicionado
                $result = $connection->query("SELECT COALESCE(max(filmeid),0)+1 FROM filme;");
                $row = $result->fetch_assoc();

                $new_ID_movie = $row["COALESCE(max(filmeid),0)+1"]; //Seleciona o valor resultante

                $sql = "INSERT INTO filme VALUES('$new_ID_movie', '$title', '$country', '$imdb_score', '$synopsis', '$trailer', '$duration', FALSE, '$year', '$photo_name', '$photo_path');";
                console_log($sql);
                move_uploaded_file($photo_tmp, $photo_path); // para meter a foto do filme na pasta 'photos'

                if ($connection->query("INSERT INTO filme VALUES('$new_ID_movie', '$title', '$country', '$imdb_score', '$synopsis', '$trailer', '$duration', FALSE, '$year', '$photo_name', '$photo_path');")) {
                    $_SESSION['add_success'] = true;
                } else {
                    throw new Exception($connection->error);
                }

                //Checkar se os generos introduzidos já existem na db, se não, serão lá adicionados
                for ($i = 0; $i < count($genre_array); $i++) {

                    //echo "<p>Genero: $genre_array[$i]<p>"; // para ver se está a separar bem os generos
                    $gen = $genre_array[$i];

                    $result = $connection->query("SELECT generonome FROM genero
                                                     WHERE LOWER(generonome) LIKE LOWER('$gen');");
                    $count_rows = $result->num_rows;

                    if ($count_rows < 1) //Caso não haja generos com esse nome na base de dados adiciona-se
                    {
                        //gerar um novo id para o novo genero
                        $result = $connection->query("SELECT COALESCE(max(generoid),0)+1 FROM genero;");
                        $row = $result->fetch_assoc();

                        $new_ID_genre = $row["COALESCE(max(generoid),0)+1"];

                        $sql = "INSERT INTO genero VALUES('$new_ID_gen', '$gen');";
                        console_log($sql);

                        // rever este if
                        if ($connection->query("INSERT INTO genero VALUES('$new_ID_genre', '$gen');")) {
                            $_SESSION['add_success'] = true;
                        } else {
                            $sucess = false;
                            throw new Exception($connection->error);
                        }
                    }

                    // Relação entre filme e os generos
                    $result = $connection->query("SELECT generoid FROM genero
                                                     WHERE LOWER(generonome) LIKE LOWER('$gen');");
                    $row = $result->fetch_assoc();
                    $ID_genre = $row["generoid"]; //Seleciona o id do genero que resulta da pesquisa

                    $sql = "INSERT INTO genero_filme VALUES('$ID_genre', '$new_ID_movie');";
                    console_log($sql);

                    // rever este if
                    if ($connection->query("INSERT INTO genero_filme VALUES('$ID_genre', '$new_ID_movie');")) {

                        $_SESSION['add_success'] = true;
                    } else {
                        $sucess = false;
                        throw new Exception($connection->error);
                    }
                }


                //Checkar se os atores introduzidos já existem na db, se não, serão lá adicionados
                for ($i = 0; $i < count($cast_array); $i++) {

                    $ator = $cast_array[$i];

                    $result = $connection->query("SELECT nome FROM artista
                                                     WHERE LOWER(nome) LIKE LOWER('$ator') AND tipo='Ator';");
                    $count_rows = $result->num_rows;


                    if ($count_rows < 1) //Caso não haja atores com esse nome na base de dados
                    {
                        //gerar um novo id para o novo ator
                        $result = $connection->query("SELECT COALESCE(max(artistaid),0)+1 FROM artista;");
                        $row = $result->fetch_assoc();

                        $new_ID_ator = $row["COALESCE(max(artistaid),0)+1"];

                        $sql = "INSERT INTO artista VALUES('$new_ID_ator', '$ator', 'Ator');";
                        console_log($sql);

                        // rever este if
                        if ($connection->query("INSERT INTO artista VALUES('$new_ID_ator', '$ator', 'Ator');")) {
                            $_SESSION['add_success'] = true;
                        } else {
                            $sucess = false;
                            throw new Exception($connection->error);
                        }
                    }

                    // Relação entre filme e os atores
                    $result = $connection->query("SELECT artistaid FROM artista
                                                     WHERE LOWER(nome) LIKE LOWER('$ator') AND tipo='Ator';");
                    $row = $result->fetch_assoc();
                    $ID_ator = $row["artistaid"]; //Seleciona o id do artista que resulta da pesquisa

                    $sql = "INSERT INTO artista_filme VALUES('$ID_ator', '$new_ID_movie');";
                    console_log($sql);

                    // rever este if
                    if ($connection->query("INSERT INTO artista_filme VALUES('$ID_ator', '$new_ID_movie');")) {
                        $_SESSION['add_success'] = true;
                    } else {
                        $sucess = false;
                        throw new Exception($connection->error);
                    }
                }

                //Checkar se os diretores introduzidos já existem na db, se não, serão lá adicionados
                for ($i = 0; $i < count($director_array); $i++) {

                    $dir = $director_array[$i];

                    $result = $connection->query("SELECT nome FROM artista
                                                     WHERE LOWER(nome) LIKE LOWER('$dir') AND tipo='Director';");
                    $count_rows = $result->num_rows;


                    if ($count_rows < 1) //Caso não haja atores com esse nome na base de dados
                    {
                        //gerar um novo id para o novo ator
                        $result = $connection->query("SELECT COALESCE(max(artistaid),0)+1 FROM artista;");
                        $row = $result->fetch_assoc();

                        $new_ID_dir = $row["COALESCE(max(artistaid),0)+1"];

                        $sql = "INSERT INTO artista VALUES('$new_ID_dir', '$dir', 'Director');";
                        console_log($sql);

                        // rever este if
                        if ($connection->query("INSERT INTO artista VALUES('$new_ID_dir', '$dir', 'Director');")) {
                            $_SESSION['add_success'] = true;
                        } else {
                            $sucess = false;
                            throw new Exception($connection->error);
                        }
                    }

                    // Relação entre filme e os diretores
                    $result = $connection->query("SELECT artistaid FROM artista
                                                     WHERE LOWER(nome) LIKE LOWER('$dir') AND tipo='Director';");
                    $row = $result->fetch_assoc();
                    $ID_dir = $row["artistaid"]; //Seleciona o id do artista que resulta da pesquisa

                    $sql = "INSERT INTO artista_filme VALUES('$ID_dir', '$new_ID_movie');";
                    console_log($sql);

                    // rever este if
                    if ($connection->query("INSERT INTO artista_filme VALUES('$ID_dir', '$new_ID_movie');")) {
                        $_SESSION['add_success'] = true;
                    } else {
                        $sucess = false;
                        throw new Exception($connection->error);
                    }
                }

                if ($sucess == true) {
                    header('Location: admin_profile.php');
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
    <title>Add Movie Form</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="css/style_add_movie.css" rel="stylesheet" type="text/css">
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
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
            <li><a href="admin_profile.php">MANAGEMENT</a></li>
            <li><a href="logout.php">LOGOUT</a></li>
        </div>

    </nav>

    <main>

        <div class="wrapper">

            <form class="form-addmovie" method="post" name="upfrm" action="" enctype="multipart/form-data">

                <div class="form-header">
                    <h3>Add Movie Form</h3>
                </div>

                <div class="flex-container">

                    <!-- Title -->
                    <div class="form-group" id="field-left">
                        <input type="text" class="form-input" id="field-left" placeholder="Title" name="title" value="<?php
                                                                                                                        if (isset($_SESSION['input_title'])) {
                                                                                                                            echo $_SESSION['input_title'];
                                                                                                                            unset($_SESSION['input_title']);
                                                                                                                        }
                                                                                                                        ?>" />
                        <?php
                        if (isset($_SESSION['e_title'])) {
                            echo '<div class="error">' . $_SESSION['e_title'] . '</div>';
                            unset($_SESSION['e_title']);
                        }
                        ?>
                    </div>

                    <!-- Year -->
                    <div class="form-group">
                        <input type="number" class="form-input" placeholder="Year" name="year" value="<?php
                                                                                                        if (isset($_SESSION['input_year'])) {
                                                                                                            echo $_SESSION['input_year'];
                                                                                                            unset($_SESSION['input_year']);
                                                                                                        }
                                                                                                        ?>" />

                        <?php
                        if (isset($_SESSION['e_year'])) {
                            echo '<div class="error">' . $_SESSION['e_year'] . '</div>';
                            unset($_SESSION['e_year']);
                        }
                        ?>
                    </div>

                </div>

                <div class="flex-container">

                    <!-- Genre -->
                    <div class="form-group" id="field-left">
                        <input type="text" class="form-input" id="field-left" placeholder="Genres" name="genre" value="<?php
                                                                                                                        if (isset($_SESSION['input_genre'])) {
                                                                                                                            echo $_SESSION['input_genre'];
                                                                                                                            unset($_SESSION['input_genre']);
                                                                                                                        }
                                                                                                                        ?>" />

                        <?php
                        if (isset($_SESSION['e_genre'])) {
                            echo '<div class="error">' . $_SESSION['e_genre'] . '</div>';
                            unset($_SESSION['e_genre']);
                        }
                        ?>
                    </div>

                    <!-- IMDB Score -->
                    <div class="form-group">
                        <input type="number" step=0.1 class="form-input" placeholder="IMDB Score" name="imdb_score" value="<?php
                                                                                                                            if (isset($_SESSION['input_imdb_score'])) {
                                                                                                                                echo $_SESSION['input_imdb_score'];
                                                                                                                                unset($_SESSION['input_imdb_score']);
                                                                                                                            }
                                                                                                                            ?>" />

                        <?php
                        if (isset($_SESSION['e_imdb_score'])) {
                            echo '<div class="error">' . $_SESSION['e_imdb_score'] . '</div>';
                            unset($_SESSION['e_imdb_score']);
                        }
                        ?>
                    </div>
                </div>

                <div class="flex-container">

                    <!-- Trailer -->
                    <div class="form-group" id="field-left">
                        <input type="text" class="form-input" id="field-left" placeholder="Trailer link" name="trailer" value="<?php
                                                                                                                                if (isset($_SESSION['input_trailer'])) {
                                                                                                                                    echo $_SESSION['input_trailer'];
                                                                                                                                    unset($_SESSION['input_trailer']);
                                                                                                                                }
                                                                                                                                ?>" />

                        <?php
                        if (isset($_SESSION['e_trailer'])) {
                            echo '<div class="error">' . $_SESSION['e_trailer'] . '</div>';
                            unset($_SESSION['e_trailer']);
                        }
                        ?>
                    </div>

                    <!-- Duration -->
                    <div class="form-group">
                        <input type="number" class="form-input" placeholder="Duration of the movie (minutes)" name="duration" value="<?php
                                                                                                                                        if (isset($_SESSION['input_duration'])) {
                                                                                                                                            echo $_SESSION['input_duration'];
                                                                                                                                            unset($_SESSION['input_duration']);
                                                                                                                                        }
                                                                                                                                        ?>" />

                        <?php
                        if (isset($_SESSION['e_duration'])) {
                            echo '<div class="error">' . $_SESSION['e_duration'] . '</div>';
                            unset($_SESSION['e_duration']);
                        }
                        ?>
                    </div>
                </div>

                <div class="flex-container">

                    <!-- Country -->
                    <div class="form-group" id="field-left">
                        <input type="text" class="form-input" id="field-left" placeholder="Country" name="country" value="<?php
                                                                                                                            if (isset($_SESSION['input_country'])) {
                                                                                                                                echo $_SESSION['input_country'];
                                                                                                                                unset($_SESSION['input_country']);
                                                                                                                            }
                                                                                                                            ?>" />

                        <?php
                        if (isset($_SESSION['e_country'])) {
                            echo '<div class="error">' . $_SESSION['e_country'] . '</div>';
                            unset($_SESSION['e_country']);
                        }
                        ?>
                    </div>

                    <!-- Director -->
                    <div class="form-group">
                        <input type="text" class="form-input" placeholder="Director" name="director" value="<?php
                                                                                                            if (isset($_SESSION['input_director'])) {
                                                                                                                echo $_SESSION['input_director'];
                                                                                                                unset($_SESSION['input_director']);
                                                                                                            }
                                                                                                            ?>" />

                        <?php
                        if (isset($_SESSION['e_director'])) {
                            echo '<div class="error">' . $_SESSION['e_director'] . '</div>';
                            unset($_SESSION['e_director']);
                        }
                        ?>
                    </div>
                </div>

                <!-- Cast -->
                <input type="text" class="form-input" placeholder="Cast" name="cast" value="<?php
                                                                                            if (isset($_SESSION['input_cast'])) {
                                                                                                echo $_SESSION['input_cast'];
                                                                                                unset($_SESSION['input_cast']);
                                                                                            }
                                                                                            ?>" />
                <?php
                if (isset($_SESSION['e_cast'])) {
                    echo '<div class="error">' . $_SESSION['e_cast'] . '</div>';
                    unset($_SESSION['e_cast']);
                }
                ?>

                <!-- Synopsis -->
                <input type="text" class="form-input" id="synopsis" placeholder="Synopsis" name="synopsis" value="<?php
                                                                                                                    if (isset($_SESSION['input_synopsis'])) {
                                                                                                                        echo $_SESSION['input_synopsis'];
                                                                                                                        unset($_SESSION['input_synopsis']);
                                                                                                                    }
                                                                                                                    ?>" />

                <?php
                if (isset($_SESSION['e_synopsis'])) {
                    echo '<div class="error">' . $_SESSION['e_synopsis'] . '</div>';
                    unset($_SESSION['e_synopsis']);
                }
                ?>

                <!-- Image -->
                <div class='file-input'>
                    <input type='file' name="movie_photo" value="movie_photo" accept=".jpg, .jpeg, .png">
                    <span class='button'>Choose movie image</span>
                    <span class='label' data-js-label>No file selected</span>
                </div>

                <?php
                if (isset($_SESSION['e_movie_photo'])) {
                    echo '<div class="error">' . $_SESSION['e_movie_photo'] . '</div>';
                    unset($_SESSION['e_movie_photo']);
                }
                ?>

                <!-- Submit -->
                <button type="submit" class="form-button" value="upload" name="upload">Submit</button>

            </form>

            <script>
                var inputs = document.querySelectorAll('.file-input')

                for (var i = 0, len = inputs.length; i < len; i++) {
                    customInput(inputs[i])
                }

                function customInput(el) {
                    const fileInput = el.querySelector('[type="file"]')
                    const label = el.querySelector('[data-js-label]')

                    fileInput.onchange =
                        fileInput.onmouseout = function() {
                            if (!fileInput.value) return

                            var value = fileInput.value.replace(/^.*[\\\/]/, '')
                            el.className += ' -chosen'
                            label.innerText = value
                        }
                }
            </script>

        </div>

    </main>

</body>

</html>