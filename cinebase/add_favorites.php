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

$query = "INSERT INTO utilizador_filme VALUES('$user_id', '$filmeid');";

//   echo "<script type='text/javascript'>alert('$query');</script>"; 

$result = $connection->query($query);

header('Location: movie_details.php?filmeid=' . $filmeid . '');
exit();
