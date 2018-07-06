<?php

function getDB()
{
	//set database parameters
	$dbname = "newsaggregatordb";
	$dbserver = "localhost";
	$username = "root";
	try {
		//create and return database connection
		$conn = new PDO("mysql:host=$dbserver;dbname=$dbname", $username, "");
		return $conn;
	}
	catch (PDOException $e)
	{
		die("Failure: ".$e->getMessage());
	}
}
?>