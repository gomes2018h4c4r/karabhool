<?php


include('config/database_connection.php');

session_start();

$query = "
UPDATE users 
SET activity = now() 
WHERE id = '" . $_SESSION["id"] . "'
";

$statement = $connect->prepare($query);

$statement->execute();

?>

