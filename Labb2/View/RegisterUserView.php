<?php 
	
	class RegisterUserView{

	private $username;
	private $password;
	private $message;
	private $message2;
	private $svDay = array("Mon"=>"Måndag", "Tue"=>"Tisdag", "Wed"=>"Onsdag", "Thu"=>"Torsdag", "Fri"=>"Fredag", "Sat"=>"Lördag", "Sun"=>"Söndag");
	private $svMonth = array("01"=>"Januari", "02"=>"Februari", "03"=>"Mars", "04"=>"April", "05"=>"Maj", "06"=>"Juni", "07"=>"Juli", "08"=>"Augusti", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"December");
	
	public function ViewLogin(){
	

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
					";
		$ret .= $this->showDate();
		return $ret;	
	}
	
	// show Date-message in swedish
	public function showDate(){
		return "<p>" . $this->svDay[date("D")] . ", den " . date("d") . " " . $this->svMonth[date("m")]  . " år " . date("Y") . ". Klockan är " . date("H:i:s") . ".</p>";
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
			}
	}
	
	public function getInputName(){
			
			if(isset($_POST["username"]) and strlen(trim($_POST["username"])) !== 0){
				$name = $_POST["username"];
				return $name;
			}
	}
	
	public function usernameIsOccupied(){
		$this->message = ("Användarnamnet är upptaget..");
	}
	
	public function userIsRegisterComplete($registeredUsername){
		$this->message = ("Användaren $registeredUsername lades till..");
	}
	
	public function usernameToShort(){
		$this->message = ("Användarnamnet är för kort! Minst 3 tecken");
	}
	
	public function passwordIsToShort(){
		$this->message = ("Lösenordet är för kort! Minst 6 tecken");
	}
	
	public function passwordsDontMatchEachOther(){
		$this->message = ("Lösenorden matchar inte varandra!");
	}
	public function usernameAndPasswordToShortMessage(){
		$this->message = "Användarnamnet har för få tecken. Minst 3 tecken";
		$this->message2 = "Lösenorden har för få tecken. Minst 6 tecken";
	}
		public function notAllowedsymbolsMessage(){
		$this->message = "Användarnamnet innehåller otillåtna tecken..";
	}
	
	public function getInputPassword(){
			
			if(isset($_POST["password"]) and strlen(trim($_POST["password"])) !== 0){
				$pass = $_POST["password"];
				return $pass;
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