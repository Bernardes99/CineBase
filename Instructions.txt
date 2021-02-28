BDAI - PROJECTO FINAL

André Matias Bernardes	 
Joana Sofia Baião Vieira  
João Alves Ferreira	 

------------------------------------------------------------------------------------

Todo o conteúdo do site encontra-se armazenado numa base de dados chamada “cinebase”. 
Para utilizar esta base de dados, o script de nome “cinebase.sql” deve ser importado 
para um servidor php e, se necessário, deverão ser ajustados os parâmetros definidos
no ficheiro “connect.php” conforme as definições desse servidor, de forma a permitir
a conexão do site à base de dados, ao longo de toda a sua utilização.

As varíaveis predefinidas são:
	$host = "localhost";
	$port = "8889";
	$db_user = "pedro";
	$db_password = "pedro";
	$db_name = "cinebase";

No servidor, deverá criar uma database com o nome "cinebase" e só depois importar o
ficheiro "cinebase.sql" disponibilizado para essa database.

A pasta "cinebase", que contém todos os ficheiros php, javascript e css necessários,
assim como algumas fotos incluidas na base de dados, terá de ser copiada para a 
diretoria C:/MAMP/htdocs/