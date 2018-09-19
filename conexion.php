<?php

$servername = 'localhost';
$username = 'user';
$password = 'pass';
$dbname = 'tacos';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
	echo 'Conecction Error'.mysqli_connect_error();
}
?>