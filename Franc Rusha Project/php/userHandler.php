<?php
	include_once('dbconn.php');
	//class used to manage user registration, login, and return user details
	class manageUser {
		
		public function registerUser($username, $email, $password) //add record to users table with given credentials
		{
			try {
				$conn = getDB();
				$query = $conn ->prepare("INSERT INTO users(username,email,password) VALUES (:username,:email,:password)");
				$query -> bindParam(':username',$username);
				$query -> bindParam(':email',$email);
				$hashed_pwd = hash('sha256',$password); //hash password before inserting into database
				$query -> bindParam(':password',$hashed_pwd);
				$query -> execute();
				return $conn -> lastInsertId();
			} catch (PDOException $e) {
				exit($e -> getMessage());
			}
		}
		
		public function isValidUsername($username) //check if a given username is already in use
		{
			try {
				$conn = getDB();
				$query = $conn -> prepare("SELECT id FROM users WHERE username=:username");
				$query -> bindParam(':username',$username);
				$query -> execute();
				if ($query -> rowCount() > 0) {
					return true;
				} else {
					return false;
				}
			} catch (PDOException $e) {
				exit($e -> getMessage());
			}
		}
		
		public function isValidEmail($email) //check if a given email is already in use
		{
			try {
				$conn = getDB();
				$query = $conn->prepare("SELECT id FROM users WHERE email=:email");
				$query->bindParam(':email', $email);
				$query->execute();
				if ($query->rowCount() > 0) {
					return true;
				} else {
					return false;
				}
			} catch (PDOException $e) {
				exit($e->getMessage());
			}
		}
		
		public function loginUser($username, $password) //user validation: if given username and password exist, return that user's id
		{
			try {
				$conn = getDB();
				$query = $conn->prepare("SELECT id FROM users WHERE username=:username AND password=:password");
				$query->bindParam(':username', $username);
				$hashed_pwd = hash('sha256', $password);
				$query->bindParam(':password', $hashed_pwd);
				$query->execute();
				if ($query->rowCount() > 0) {
					$result = $query->fetch(PDO::FETCH_OBJ);
					return $result->id;
				} else {
					return false;
				}
			} catch (PDOException $e) {
				exit($e->getMessage());
			}
		}
		
		public function getUserDetails($user_id) //return a given user's credentials
		{
			try {
				$conn = getDB();
				$query = $conn->prepare("SELECT id, username, email FROM users WHERE id=:user_id");
				$query->bindParam(':user_id', $user_id);
				$query->execute();
				if ($query->rowCount() > 0) {
					return $query->fetch(PDO::FETCH_OBJ);
				}
			} catch (PDOException $e) {
				exit($e->getMessage());
			}
		}
	}
	
?>