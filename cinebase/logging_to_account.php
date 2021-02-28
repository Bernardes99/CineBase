<?php
session_start();
// se as var. que o form envia estiverem vazias volta para o login 
if((!isset($_POST['input_user_login']))||(!isset($_POST['input_user_password'])||(!isset($_POST['user_admin'])))) 
{
	header('Location: login.php');
	exit();
}

try
{
	require_once "connect.php";
    $connection = @new mysqli($host, $db_user, $db_password, $db_name, $port);

	$login = $_POST['input_user_login']; 
	$pass = $_POST['input_user_password'];
	$login = htmlentities($login, ENT_QUOTES, "UTF-8");

	$user_type = $_POST['user_admin'];

    if ($user_type=='user' and $result = @$connection-> query (sprintf("SELECT * FROM utilizador WHERE (email='%s' AND password='%s') OR (nomeutilizador='%s' AND password='%s')", mysqli_real_escape_string($connection,$login), mysqli_real_escape_string($connection,$pass), mysqli_real_escape_string($connection,$login), mysqli_real_escape_string($connection,$pass))))
	{
		$count_users = $result->num_rows;
		$row = $result->fetch_assoc();
		$kicked = $row['expulso'];
		
		if ($count_users>0 and $kicked==0)
		{
			$_SESSION["user_logged"] = true;
			$_SESSION["admin_logged"] = false;
			$_SESSION["user_id"] = $row["utilizadorid"];
            $_SESSION["user_email"] = $row["email"];
            $_SESSION["user_password"] = $row["password"];
            $_SESSION["user_username"] = $row["nomeutilizador"];
            $_SESSION["user_name"] = $row["primeironome"];
			$_SESSION["user_surname"] = $row["ultimonome"];
			
			unset($_SESSION["error_log"]);
			$result->free_result();
			#header('Location: user_main_page.php');
			header('Location: index.php');
				
		} 
		elseif ($count_users>0 and $kicked==1)
		{
			$_SESSION["error_log"] = '<span style="color:red">You have been kicked out of the platform by the Administrator!</span>';
			header("Location: login.php");	
		}
		else
		{
            // diferenciar erro no login ou password
			$_SESSION["error_log"] = '<span style="color:red">Wrong Login or Password!</span>';
			header("Location: login.php");		
		}
	}
	elseif ($user_type=='admin' and $result = @$connection-> query (sprintf("SELECT * FROM administrador WHERE (email='%s' AND password='%s') OR (nomeadmin='%s' AND password='%s')", mysqli_real_escape_string($connection,$login), mysqli_real_escape_string($connection,$pass), mysqli_real_escape_string($connection,$login), mysqli_real_escape_string($connection,$pass))))
	{
		$count_admins = $result->num_rows;
		$row = $result->fetch_assoc();
		
		if ($count_admins>0)
		{
			$_SESSION["user_logged"] = false;
			$_SESSION["admin_logged"] = true;
			$_SESSION["admin_id"] = $row["adminid"];
            $_SESSION["admin_email"] = $row["email"];
            $_SESSION["admin_pass"] = $row["password"];
            $_SESSION["admin_name"] = $row["nomeadmin"];
			
			unset($_SESSION["error_log"]);
			$result->free_result();
			#header('Location: admin_main_page.php');
			header('Location: index.php');	
		} 
		else
		{
            // diferenciar erro no login ou password
			$_SESSION["error_log"] = '<span style="color:red">Admin Wrong Login or Password!</span>';
			header("Location: login.php");		
		}
	}
	else
	{
		$_SESSION["error_log"] = '<span style="color:red">User not found</span>';
		header("Location: login.php");
	}
	
	$connection->close();
	
}

catch(Exception $e)
{
	$_SESSION["db_error"]=1;
	~header('Location: login.php');
}
	
?>