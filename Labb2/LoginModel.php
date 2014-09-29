<?php

class LoginModel{

	/*
	 * Check if LoginModel.txt has the kombination of username and password
	 * @return success:bool
	 */
	public function loginUser($user, $pass, $clientId){
		
				
		$success = false;
		
		//Specificerar uppgifter för anslutning mot önskad datorbas samt SQL-Query
		$myConnection = new mysqli("127.0.0.1", "root", "", "labb4");
		$sqlCommand = "SELECT * FROM members WHERE username='$user' AND password='$pass'";
	
		//Sparar undan resultatet i variabler
		$result = mysqli_query($myConnection, $sqlCommand);
		$row_count = mysqli_num_rows($result);
		
		//Kontroll för om lösenord och användarnamn var korrekt
				if($row_count > 0){
		
					$success = true; // success
					
					// save session
					$_SESSION["logged"] = $clientId;
					$_SESSION["loggedUser"] = $user;
					
					return $success;	
			} 
		
		return $success;
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
