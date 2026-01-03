<?php
$username = 'root';
$password = '';
$hostname = 'localhost:4306';
$databasename='ad_server';
$conn = mysqli_connect($hostname, $username, $password, $databasename);

if($conn){
	//echo 'successfully connected'. '<br>';

}else{
	die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}


?>