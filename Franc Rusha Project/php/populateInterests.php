<?php
	session_start();
	include_once('dbconn.php');
	
	$conn = getDB(); //get database connection
	$user_id = $_SESSION['user_id']; //get user id
	
	if (isset($_POST["saveInterests"]))  //if there's a request from Interests modal form
	{
		if (isset($_POST["newsCheck"])) //if news checkbox is checked add relevant records to user_feed table
		{
			$query1 = "INSERT IGNORE INTO user_feed (user_id,feed_id) VALUES ('$user_id','1')";
			$stmt1 = $conn -> prepare($query1);
			$stmt1 -> execute();
			$query2 = "INSERT IGNORE INTO user_feed (user_id,feed_id) VALUES ('$user_id','2')";
			$stmt2 = $conn -> prepare($query2);
			$stmt2 -> execute();
		}
		else  //if news checkbox is unchecked delete relevant records from user_feed table
		{
			$query1 = "DELETE FROM user_feed WHERE (user_id = '$user_id' AND feed_id = '1')";
			$stmt1 = $conn -> prepare($query1);
			$stmt1 -> execute();
			$query2 = "DELETE FROM user_feed WHERE (user_id = '$user_id' AND feed_id = '2')";
			$stmt2 = $conn -> prepare($query2);
			$stmt2 -> execute();
		}
		
		if (isset($_POST["businessCheck"])) //if business checkbox is checked add relevant record to user_feed table
		{
			$query = "INSERT IGNORE INTO user_feed (user_id,feed_id) VALUES ('$user_id','3')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		else //if business checkbox is unchecked delete relevant record from user_feed table
		{
			$query = "DELETE FROM user_feed WHERE (user_id = '$user_id' AND feed_id = '3')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		
		if (isset($_POST["technologyCheck"])) //if technology checkbox is checked add relevant record to user_feed table
		{
			$query = "INSERT IGNORE INTO user_feed (user_id,feed_id) VALUES ('$user_id','4')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		else //if technology checkbox is unchecked delete relevant record from user_feed table
		{
			$query = "DELETE FROM user_feed WHERE (user_id = '$user_id' AND feed_id = '4')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		
		if (isset($_POST["sportsCheck"])) //if sports checkbox is checked add relevant record to user_feed table
		{
			$query = "INSERT IGNORE INTO user_feed (user_id,feed_id) VALUES ('$user_id','6')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		else //if sports checkbox is unchecked delete relevant record from user_feed table
		{
			$query = "DELETE FROM user_feed WHERE (user_id = '$user_id' AND feed_id = '6')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		
		if (isset($_POST["entertainmentCheck"])) //if entertainment checkbox is checked add relevant record to user_feed table
		{
			$query = "INSERT IGNORE INTO user_feed (user_id,feed_id) VALUES ('$user_id','5')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
		else //if entertainment checkbox is unchecked delete relevant record from user_feed table
		{
			$query = "DELETE FROM user_feed WHERE (user_id = '$user_id' AND feed_id = '5')";
			$stmt = $conn -> prepare($query);
			$stmt -> execute();
		}
	}
	
	header("Location: userPage.php"); //redirect to user page
?>