<?php
	
	session_start();
	require('userHandler.php');
	
	//if no user id is set, redirect to homepage
	if (empty($_SESSION['user_id']))
	{
		header("Location: mainPage.php");
	}
	
	$manager = new manageUser();//get manageUser class
	$user = $manager -> getUserDetails($_SESSION['user_id']); //get logged user's details
	
?>

<!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
		<!--styling used for user interest checkboxes -->
		<style>
			.custom-control-label::before, 
			.custom-control-label::after {
			top: .8rem;
			width: 1.25rem;
			height: 1.25rem;
			}
		</style>
		
	</head>
	
	<body>
		<!--navbar with logo, "choose interests" button, username and logout link -->
		<nav class="navbar navbar-expand-md bg-light">
			<a class="navbar-brand" href="userPage.php">
				<img src="logo.png" alt="Logo" style="width:60px;">
			</a>
			&nbsp
			&nbsp
			<ul class="navbar-nav">
				<li class="nav-item">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#InterestModal">Choose your interests</button>
				</li>
			</ul>
			<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<h4>Hello, <?php echo $user -> username?></h4>
					</li>
					&nbsp
					<li class="nav-item">
						<a href="logout.php" class="btn btn-default" >Logout</a>
					</li>
				</ul>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon"></span>
			</button>
		</nav>
		
		<!--Choose Interests Modal Form-->
		<div class="modal fade" id="InterestModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Choose your topics of interest</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form id="interestForm" action="populateInterests.php" method="post">
							<div class="form-group">
								<div class="custom-control custom-checkbox form-control-lg">
									<input type="checkbox" class="custom-control-input" id="newsCheck" name="newsCheck">
									<label class="custom-control-label" for="newsCheck">News</label>
								</div>
								<div class="custom-control custom-checkbox form-control-lg">
									<input type="checkbox" class="custom-control-input" id="businessCheck" name="businessCheck">
									<label class="custom-control-label" for="businessCheck">Business</label>
								</div>
								<div class="custom-control custom-checkbox form-control-lg">
									<input type="checkbox" class="custom-control-input" id="technologyCheck" name="technologyCheck">
									<label class="custom-control-label" for="technologyCheck">Technology</label>
								</div>
								<div class="custom-control custom-checkbox form-control-lg">
									<input type="checkbox" class="custom-control-input" id="sportsCheck"  name="sportsCheck">
									<label class="custom-control-label" for="sportsCheck">Sports</label>
								</div>
								<div class="custom-control custom-checkbox form-control-lg">
									<input type="checkbox" class="custom-control-input" id="entertainmentCheck"  name="entertainmentCheck">
									<label class="custom-control-label" for="entertainmentCheck">Entertainment</label>
								</div>
							</div>
							<div class="form-group">
								<input class="btn btn-success" type="submit" value="Save" id="saveInterests" name="saveInterests">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!--Restore checkboxes as in the user's saved state-->
		<?php
			include_once('dbconn.php');
			$conn = getDB();
			$user_id = $user -> id;
			
			$query1 = "SELECT * FROM user_feed WHERE user_id = '$user_id' AND feed_id = '1'";
			$result = $conn -> query($query1);
			if ($result -> rowCount() > 0)
			{?>
				<script>$('#newsCheck').prop("checked",true);</script>
		<?php
			}
			
			$query2 = "SELECT * FROM user_feed WHERE user_id = '$user_id' AND feed_id = '3'";
			$result = $conn -> query($query2);
			if ($result -> rowCount() > 0)
			{?>
				<script>$('#businessCheck').prop("checked",true);</script>
		<?php
			}
			
			$query3 = "SELECT * FROM user_feed WHERE user_id = '$user_id' AND feed_id = '4'";
			$result = $conn -> query($query3);
			if ($result -> rowCount() > 0)
			{?>
				<script>$('#technologyCheck').prop("checked",true);</script>
		<?php
			}
			
			$query4 = "SELECT * FROM user_feed WHERE user_id = '$user_id' AND feed_id = '5'";
			$result = $conn -> query($query4);
			if ($result -> rowCount() > 0)
			{?>
				<script>$('#entertainmentCheck').prop("checked",true);</script>
		<?php
			}
			
			$query5 = "SELECT * FROM user_feed WHERE user_id = '$user_id' AND feed_id = '6'";
			$result = $conn -> query($query5);
			if ($result -> rowCount() > 0)
			{?>
				<script>$('#sportsCheck').prop("checked",true);</script>
		<?php
			}
		?>
		
		<!--Jumbotron-->
		<div class="jumbotron jumbotron-fluid" style="background-image: url(banner.jpg); background-size: cover;">
			<div class="container">
				<h1 class="display-3 text-light">InstaNews</h1>
				<p class="lead text-light">Welcome to my news feed aggregator</p>
			</div>
		</div>
		
		<!--One nav tab per category, plus one to be populated with articles from user's interests -->
		<div class="container">
			<ul class="nav nav-tabs nav-justified" id="categoryTab" role="tablist">
			  <li class="nav-item">
				<a class="nav-link active" id="news-tab" data-toggle="tab" href="#news" role="tab" aria-controls="news" aria-selected="true">News</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="business-tab" data-toggle="tab" href="#business" role="tab" aria-controls="business" aria-selected="false">Business</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="technology-tab" data-toggle="tab" href="#technology" role="tab" aria-controls="technology" aria-selected="false">Tech</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="sports-tab" data-toggle="tab" href="#sports" role="tab" aria-controls="sports" aria-selected="false">Sports</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="entertainment-tab" data-toggle="tab" href="#entertainment" role="tab" aria-controls="entertainment" aria-selected="false">Entertainment</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" id="userInterest-tab" data-toggle="tab" href="#userInterest" role="tab" aria-controls="userInterest" aria-selected="false">My Interests</a>
			  </li>
			</ul>
			<div class="tab-content" id="categoryContent">
			  <div class="tab-pane fade show active" id="news" role="tabpanel" aria-labelledby="news-tab">
				<br/>
				<?php include 'getNewsContent.php';?>
			  </div>
			  <div class="tab-pane fade" id="business" role="tabpanel" aria-labelledby="business-tab">
				<br/>
				<?php include 'getBusinessContent.php';?>
			  </div>
			  <div class="tab-pane fade" id="technology" role="tabpanel" aria-labelledby="technology-tab">
				<br/>
				<?php include 'getTechContent.php';?>
			  </div>
			  <div class="tab-pane fade" id="sports" role="tabpanel" aria-labelledby="sports-tab">
				<br/>
				<?php include 'getSportsContent.php';?>
			  </div>
			  <div class="tab-pane fade" id="entertainment" role="tabpanel" aria-labelledby="entertainment-tab">
				<br/>
				<?php include 'getEntertainmentContent.php';?>
			  </div>
			  <div class="tab-pane fade" id="userInterest" role="tabpanel" aria-labelledby="userInterest-tab">
				<br/>
				<?php include 'getCustomizedContent.php';?> <!--Tab will be empty if no user interests are specified -->
			  </div>
			</div>
		</div>
</html>