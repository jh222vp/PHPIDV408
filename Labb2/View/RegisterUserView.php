<?php 
	
	class RegisterUserView{

	private $username;
	private $password;
	private $message;
	private $message2;
	

		
	public function ViewLogin(){
	$date = "dagen datum är..";
	$ret =  "<a href='?'>Tillbaka</a>
					<h3>Ej inloggad, Registrerar användare</h3>
						<form method='POST'>
						<fieldset>
							<p>$this->message</p>
							<p>$this->message2</p>
							<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
							<label for='usernamename'>Namn</label>
							<input type='text' id='username' name='username' value='$this->username'>
					<label for='pass'>Lösenord</label>
					<input type='password' id='pass' name='password'>
					<label for='repeatpass'>Bekräfta lösenord</label>
					<input type='password' id='repeatpass' name='repeatpass'>
					</fieldset>
					<input type='submit' value='Registrera' name='submit'>
					</form>
					<p>$date</p>
					";
							
	return $ret;
	}
	
	public function getRegisterInformation(){
			if(isset($_POST["username"])){
			$this->username = $_POST["username"];
			}
			if(isset($_POST["password"])){
			$this->password = $_POST["password"];
			}
			if(isset($_POST["repeatpass"])){
			$this->repeatPass = $_POST["repeatpass"];
		}
		}
	
		
	public function getRepeatPass(){
			
			if(isset($_POST["repeatpass"]) and strlen(trim($_POST["repeatpass"])) !== 0){
				$repeatPassword = $_POST["repeatpass"];
				return $repeatPassword;
			} else {
				$this->message("Användarnamn saknas");
				
				die();
			}
	}
	
	public function getInputName(){
			
			if(isset($_POST["username"]) and strlen(trim($_POST["username"])) !== 0){
				$name = $_POST["username"];
				return $name;
			} else {
				//$this->message("Användarnamn saknas");
				
				die();
			}
	}
	
	
	
	
	public function usernameIsOccupied(){
		$this->message = ("Användarnamnet är upptaget..");
	}
	
	public function usernameToShort(){
		$this->message = ("Användarnamnet är för kort!!!!!!!!!!..");
	}
	
	public function passwordIsToShort(){
		$this->message = ("Lösenordet är för kort..");
	}
	
	public function passwordsDontMatchEachOther(){
		$this->message = ("Lösenorden matchar inte varandraaaaaa..");
	}
	public function usernameAndPasswordToShortMessage(){
		$this->message = "Användarnamnet har för få tecken. Minst 3 tecken";
		$this->message2 = "Lösenorden har för få tecken. Minst 6 tecken";
	}
	
	
	
	
	
	
	
	public function getInputPassword(){
			
			if(isset($_POST["password"]) and strlen(trim($_POST["password"])) !== 0){
				$pass = $_POST["password"];
				return $pass;
			} else {
			
				//$this->storeMessage("password saknas");
				die();
			}
	}
	// did user press "Register new user"
	public function registerNewUser(){	
		if(isset($_GET["Register"])){
			return true;
		} else {
			return false;
		}
	}
	// did user press "submit new user"
	public function submitNewUser(){	
		if(isset($_POST["submit"])){
		
			return true;
		} else {
			return false;
		}
	}
 }