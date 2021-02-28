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

$userid = $_GET['userid'];

$query = "UPDATE utilizador 
          SET expulso=0
          WHERE utilizadorid='$userid';";

//   echo "<script type='text/javascript'>alert('$query');</script>"; 

$result = $connection->query($query);

//  $num_of_results = mysqli_num_rows($result);

header('Location: manage_users.php');
exit();
