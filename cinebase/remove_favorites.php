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

$filmeid = $_GET['filmeid'];
$user_id = $_SESSION['user_id'];



$query = "DELETE FROM utilizador_filme WHERE utilizador_utilizadorid=$user_id AND filme_filmeid=$filmeid;";

//   echo "<script type='text/javascript'>alert('$query');</script>"; 

$result = $connection->query($query);

//  $num_of_results = mysqli_num_rows($result);

header('Location: movie_details.php?filmeid=' . $filmeid . '');
exit();
