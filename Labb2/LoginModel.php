<?php


require_once("./View/RegisterUserView.php");

class LoginModel{

	private $regex = '/[^a-z0-9\-_\.]/i';
	private $registerUserView;
	private $cookiePassword;
	
	public function __construct(){
		$this->registerUserView = new RegisterUserView();
	}
	
	public function loginUser($user, $pass, $clientId){
				
		$success = false;
		$enrypt = md5($pass);
		
		
		//Specificerar uppgifter för anslutning mot önskad datorbas samt SQL-Query
		$myConnection = new mysqli("127.0.0.1", "root", "", "labb4");
		$sqlCommand = "SELECT * FROM members WHERE username='$user' AND password='$enrypt'";
	
		//Sparar undan resultatet i variabler
		$result = mysqli_query($myConnection, $sqlCommand);
		$row_count = mysqli_num_rows($result);

			
			if($row_count > 0){ // if input is same as excisting user

					$success = true; // success
					
					// save session
					$_SESSION["logged"] = $clientId;
					$_SESSION["loggedUser"] = $user;

					return $success;
			} 
		
		return $success;
	}
	
	public function registerUser($newUsername, $newPassword){
	
		$enrypt = md5($newPassword);
		
		$connection = new mysqli("127.0.0.1", "root", "", "labb4");
		$query = "INSERT INTO members (username, password) VALUES ('$newUsername', '$enrypt')";
		
		$result = mysqli_query($connection, $query);	
	}
	public function usernameIsAlreadyTaken($username)
	{
		$myConnection = new mysqli("127.0.0.1", "root", "", "labb4");
		$sqlCommand = "SELECT * FROM members WHERE username='$username'";
	
		//Sparar undan resultatet i variabler
		$result = mysqli_query($myConnection, $sqlCommand);
		$row_count = mysqli_num_rows($result);
		
			
			if($row_count > 0)
			{
				return true;
			}else{return false;}
	}
	
	public function validateUserRegistration($username, $password1, $password2){

		if(mb_strlen($username) < 3 && mb_strlen($password1) < 6 && mb_strlen($password2) < 6)
		{	
			return 1;
		}
		else if(mb_strlen($username) < 3)
		{
			return 2;
		}
		else if(mb_strlen($password1) < 6 || mb_strlen($password2) < 6)
		{
			return 3;
		}
		else if($password1 != $password2)
		{
			return 4;
		}
		else if(preg_match($this->regex, $username))
		{
			preg_replace($this->regex, "", $username);
			return 5;
		}
		else
		{
			return false;
		}
		}
	
	public function loginCredentialsUser($user, $pass, $clientId){
		
		$lines = @file("LoginDates.txt");
		
		$now = time();
		
		foreach ($lines as $userLine) {
			
			$line = explode("-", $userLine);			
			$lineUser = $line[0];
			$lineExp = $line[1];	
	
			if($lineUser === $user){
				$interval = $lineExp - $now;
				
				if($interval > 0){
					return $this->loginUser($user, $pass, $clientId);
				}
			}
		}
		return false;
	}
	
	public function storeCookieDate($user, $expSeconds){
		
		$expTime = time() + $expSeconds;
		
		file_put_contents("LoginDates.txt", $user . "-" . $expTime . "\n");
			
	}
	
	public function getCryptetCookiePass(){
		return $this->cookiePassword;
	}
	public function setCryptedCookiePass($cookiePass){
		$this->cookiePassword = md5($cookiePass);
	}
	
	public function getUserName(){
		return $_SESSION["loggedUser"];
	}
	
	public function isUserLogged($client){
		
		if(isset($_SESSION["logged"])){
			if($_SESSION["logged"] == $client){
				return $_SESSION["logged"];
			}
		} 
		return false;
	}
	
	public function logoutUser(){ 
		
		unset($_SESSION["logged"]);
		unset($_SESSION["loggedUser"]);
		return true;
	}
	
}
