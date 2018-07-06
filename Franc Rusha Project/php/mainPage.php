<?php  
	session_start();
	//if a user is logged in redirect to user page
	if (!empty($_SESSION['user_id']))
	{
		header("Location: userPage.php");
	}
	//get userManager class
	require('userHandler.php');
	$manager = new manageUser();
	
	$login_error_message = '';
	$register_error_message = '';
	 
	// check if there's a Login request
	if (!empty($_POST['btn_login'])) {
	 
		$username = trim($_POST['login_username']);
		$password = trim($_POST['login_pwd']);
	 
		if ($username == "") {
			$login_error_message = 'Username field is required';
		} else if ($password == "") {
			$login_error_message = 'Password field is required';
		} else {
			$user_id = $manager -> loginUser($username, $password); // check user login credentials
			if($user_id > 0)
			{
				// set session and redirect user to userPage.php
				$_SESSION['user_id'] = $user_id;
				header("Location: userPage.php");
			}
			else
			{
				$login_error_message = 'Invalid credentials!';
			}
		}
	}
	
	// check if there's a Signup request
	if (!empty($_POST['btn_signup'])) {
		if ($_POST['username'] == "") {
			$register_error_message = 'Username field is required';
		} else if ($_POST['email'] == "") {
			$register_error_message = 'Email field is required';
		} else if ($_POST['password'] == "") {
			$register_error_message = 'Password field is required';
		} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$register_error_message = 'Invalid email address!';
		} else if ($manager -> isValidEmail($_POST['email'])) {
			$register_error_message = 'Email is already taken';
		} else if ($manager -> isValidUsername($_POST['username'])) {
			$register_error_message = 'Username is already taken';
		} else {
			$user_id = $manager -> registerUser($_POST['username'], $_POST['email'], $_POST['password']);
			// set session and redirect user to userPage.php
			$_SESSION['user_id'] = $user_id;
			header("Location: userPage.php");
		}
	}
	
?>

<!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
	</head>
	<body>
		<!--Navbar with logo, login and signup butons-->
		<nav class="navbar navbar-expand-md bg-light">
			<a class="navbar-brand" href="mainPage.php">
				<img src="logo.png" alt="Logo" style="width:60px;">
			</a>
			<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#LoginModal">Login</button>
					</li>
					&nbsp
					<li class="nav-item">
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#SignupModal">Sign Up</button>
					</li>
				</ul>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon"></span>
			</button>
		</nav>
		
		<!--Jumbotron-->
		<div class="jumbotron jumbotron-fluid" style="background-image: url(banner.jpg); background-size: cover;">
			<div class="container">
				<h1 class="display-3 text-light">InstaNews</h1>
				<p class="lead text-light">Welcome to my news feed aggregator</p>
			</div>
		</div>
		
		<!--Signup Modal Form-->
		<div class="modal fade" id="SignupModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Create a New Account</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<?php
							//display error message in case of incorrect input
							if ($register_error_message != "") {
								echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $register_error_message . '</div>';
							}
						?>
						<form id="signupForm" action="mainPage.php" method="post">
							<div class="form-group">
								<label for="email">Email address:</label>
								<input type="email" class="form-control" id="email" name="email">
							</div>
							<div class="form-group">
								<label for="username">Username:</label>
								<input type="username" class="form-control" id="username" name="username">
							</div>
							<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" class="form-control" id="password" name="password">
							</div>
							<div class="form-group">
								<input id="btn_signup" name="btn_signup" type="submit" class="btn btn-success" value="Sign Up">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!--Validate signup form input -->
		<script>
			$(function() {
				$("#signupForm").validate( {
					rules: {
						email: "required",
						username: "required",
						password: {
							required: true,
							minlength: 8
						}
					},
					messages: {
						email: "Enter a valid email address",
						username: "Enter a valid username",
						password: {
							required: "Enter a valid password",
							minlength: "Password must be at least 8 characters"
						}
					}
				});
			});
		</script>
		
		<!--Login Modal Form-->
		<div class="modal fade" id="LoginModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Enter your credentials</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
					<?php
						if ($login_error_message != "") {
							echo '<div class="alert alert-danger"><strong>Error: </strong> ' . $login_error_message . '</div>';
						}
					?>
						<form id="loginForm" action="mainPage.php" method="post">
							<div class="form-group">
								<label for="username">Username:</label>
								<input type="username" class="form-control" id="login_username" name="login_username">
							</div>
							<div class="form-group">
								<label for="pwd">Password:</label>
								<input type="password" class="form-control" id="login_pwd" name="login_pwd">
							</div>
							<div class="form-group">
								<input id="btn_login" name="btn_login" type="submit" class="btn btn-success" value="Login">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!--Validate login form input -->
		<script>
			$(function() {
				$("#loginForm").validate( {
					rules: {
						login_username: "required",
						login_pwd: "required"
					},
					messages: {
						username: "Enter a valid username",
						password: "Enter a valid password"
					}
				});
			});
		</script>
		
		<div class="container">
		<!--Nav tabs, one per category -->
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
			</div>
		</div>
	</body>
</html>