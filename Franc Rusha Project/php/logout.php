<?php
	session_start();
	//remove user id from $_SESSION and redirect to homepage
	unset($_SESSION['user_id']);
	header("Location: mainPage.php");
?>