<?php

session_start();

if ((!isset($_SESSION['admin_logged'])) || ($_SESSION['admin_logged'] == false)) {
    header('Location: index.php');
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage users</title>
    <link href="css/style_manage.css" rel="stylesheet" type="text/css">
    <link href="css/style_nav.css" rel="stylesheet" type="text/css">
    <script src="user_functions.js"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
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
        <?php

        require_once "connect.php";

        $connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        }

        $search = $_POST['search'];
        $search_filter = $_POST['search-filter'];
        $order_by = $_POST['order_by'];

        ?>

        <div class="container">
            <h1>Manage users</h1>
            <span class="iconify" data-icon="bx:bx-shield-quarter" data-inline="false"></span>
        </div>

        <div id="horizontal"></div><br>

        <!-- FILTROS -->
        <form action="manage_users.php" method="post">

            <div class="options">

                <div class="select">
                    <select name="order_by" id="order_by">
                        <?php
                        if (isset($_POST['order_by'])) {
                            echo '<option value="' . $order_by . '" hidden>' . $order_by . '</option>';
                        } else {
                            echo '<option value="Order by:" hidden>Order by:</option>';
                        }
                        ?>
                        <option disabled>Order by:</option>
                        <option value="Name (A-Z)">Name (A-Z)</option>
                        <option value="Name (Z-A)">Name (Z-A)</option>
                        <option value="Subscription (Asc)">Subscription (Asc)</option>
                        <option value="Subscription (Desc)">Subscription (Desc)</option>
                    </select>
                </div>

                <div class="contain-search">

                    <input type="text" placeholder="Search..." name="search" value="<?php echo $search ?>">
                    <button type="submit"><i class="fa fa-search"></i></button>

                    <div class="check_boxes">

                        <div>
                            <input type="radio" id="username" name="search-filter" value="Username" checked>
                            <label for="username">Username</label>
                        </div>

                        <div>
                            <input type="radio" id="name" name="search-filter" value="Name">
                            <label for="name">Name</label>
                        </div>

                        <div>
                            <input type="radio" id="email" name="search-filter" value="Email">
                            <label for="email">Email</label>
                        </div>

                    </div>
                </div>
            </div>
        </form>

        <!-- RESULTADOS -->
        <div class="wrap">
            <div class=table>

                <?php

                require_once "connect.php";

                $connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);
                if ($connection->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                }

                //search
                $search = $_POST['search'];
                $search_filter = $_POST['search-filter'];
                $order_by = $_POST['order_by'];

                $query = "SELECT * FROM utilizador ";

                if (isset($_POST['search-filter'])) {
                    if ($search_filter == 'Username') {
                        $query .= "WHERE LOWER(nomeutilizador) LIKE LOWER('%$search%') ";
                    } elseif ($search_filter == 'Name') {
                        $query .= "WHERE LOWER(CONCAT(primeironome, ' ', ultimonome)) LIKE LOWER('%$search%') ";
                    } elseif ($search_filter == 'Email') {
                        $query .= "WHERE LOWER(email) LIKE LOWER('%$search%') ";
                    }
                }

                if (isset($_POST['order_by']) && $order_by != 'Order by:') {
                    if ($order_by == 'Name (A-Z)') {
                        $query .= "ORDER BY nomeutilizador ASC";
                    } elseif ($order_by == 'Name (Z-A)') {
                        $query .= "ORDER BY nomeutilizador DESC";
                    } elseif ($order_by == 'Subscription (Asc)') {
                        $query .= "ORDER BY datainscricao ASC";
                    } elseif ($order_by == 'Subscription (Desc)') {
                        $query .= "ORDER BY datainscricao DESC";
                    }
                }

                $query .= ";";

                // search      
                $result = $connection->query($query);

                $num_of_results = mysqli_num_rows($result);

                if ($num_of_results > 0) {

                    echo '
                        <table id="users_table"> 
                            <tr>    
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th> 
                                <th>Subscription</th> 
                                <th>Kicked</th>
                            </tr>';


                    for ($i = 1; $i <= $num_of_results; $i++) {

                        $row = mysqli_fetch_assoc($result);

                        $userid = $row['utilizadorid'];
                        $userName = $row['nomeutilizador'];
                        $name = $row['primeironome'] ." ". $row['ultimonome'];
                        $email = $row['email'];
                        $subs_date = $row['datainscricao'];
                        $kicked = $row['expulso'];
                        if ($kicked == 0) {
                            $kicked_ = "No";
                        } else {
                            $kicked_ = "Yes";
                        }

                        echo
                            '<tr>
                        <td style="display:none;">' . $userid . '</td>
                        <td>' . $userName . '</td>
                        <td>' . $name . '</td>
                        <td>' . $email . '</td>
                        <td>' . $subs_date . '</td>
                        <td>' . $kicked_ . '</td>';

                        if ($kicked == 0) {
                            echo '<td><input class=table-button type="button" id="kick" value="Kick Out" onclick="kickUser(this)"></td>';
                        } elseif ($kicked == 1) {
                            echo '<td><input class=table-button type="button" id="readmit" value="Readmit" onclick="readmitUser(this)"></td>';
                        }

                        echo '</tr>';
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