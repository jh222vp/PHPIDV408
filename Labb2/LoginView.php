<?php

require_once("./common/CookieStorage.php");

class LoginView{
	
	private $cookieMessage = "cookieMessage";
	private $cookieUser = "cookieUser";
	private $message;
	private $model;
	private $cookiePass;
	private $svDay = array("Mon"=>"Måndag", "Tue"=>"Tisdag", "Wed"=>"Onsdag", "Thu"=>"Torsdag", "Fri"=>"Fredag", "Sat"=>"Lördag", "Sun"=>"Söndag");
	private $svMonth = array("01"=>"Januari", "02"=>"Februari", "03"=>"Mars", "04"=>"April", "05"=>"Maj", "06"=>"Juni", "07"=>"Juli", "08"=>"Augusti", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"December");
	
	
	public function __construct(LoginModel $model){
		$this->model = $model;
		$this->message = new CookieStorage();
	}
	
	/* INPUT START */
	
	public function getClientIdentifier() {
		return $_SERVER["HTTP_USER_AGENT"];
	}
	
	// did user press "log in"
	public function userTryLogin(){	
		if(isset($_GET["login"])){
			return true;
		} else {
			return false;
		}
	}
	
	// did user press "log out"
	public function userTryQuit(){
		if(isset($_GET["logout"])){
			return true;
		} else {
			return false;
		}
	}
	
	// retrieve user input name or stored username
	public function getInputName($stored){
		if($stored){
			return $_COOKIE['loginUser'];
		} else {
		
			if(isset($_POST["LoginView::usrNameId"]) and strlen(trim($_POST["LoginView::usrNameId"])) !== 0){
				$name = $_POST["LoginView::usrNameId"];
				return $name;
			} else {
			
				$this->storeMessage("Användarnamn saknas");
				die();
			}
		}
	}
	
	// retrieve user input password or stored
	public function getInputPassword($stored){
		
		if($stored){
			return $_COOKIE['loginPassword'];
		} else {
			
			if(isset($_POST["LoginView::passwordId"]) and strlen(trim($_POST["LoginView::passwordId"])) !== 0){	
				
				
				$pass = ($_POST["LoginView::passwordId"]);
				return $pass;
			} else {
				$this->storeMessage("Lösenord saknas");
				$this->storeUserInput($_POST["LoginView::usrNameId"]);
				die();
			}
		}
	}
	
	/* INPUT END */
	
	/* CREDENTIALS START */
	
	// does user have stored credentials
	public function hasStoredCredentials(){ 
		if(isset($_COOKIE["loginUser"]) and isset($_COOKIE["loginPassword"])){
			return true;
		} else {
			return false;
		}
	}
	
	// did user check to keep credentials
	public function keepCredentials(){
		if(isset($_POST["Logged"])){
			return true;
		} else {
			return false;
		}
	}
	public function getCookiePass(){
	
	return $this->cookiePass;
	}
	
	public function setCookiePass($cookiepassword){
		$this->cookiePass = $cookiepassword;
	}
	// store credentials
	public function storeCredentials($name, $pass){

		setcookie("loginUser", $name, time()+3600);
		setcookie("loginPassword", $this->getCookiePass(), time()+3600);
		return 3600;
	}
	
	// remove credentials
	public function removeCredentials(){ 
		setcookie("loginUser", "", 1);
		setcookie("loginPassword", "", 1);
		return true;
	}
	
	/* CREDENTIALS END */
	
	/* COOKIE START */
	
	public function storeMessage($msg){		
		$this->message->save($this->cookieMessage, $msg);
		header('Location: ' . $_SERVER['PHP_SELF']);
	}
	
	public function storeUserInput($userName){		
		$this->message->save($this->cookieUser, $userName);
		//header('Location: ' . $_SERVER['PHP_SELF']);
	}
	
	/* COOKIE END */
	
	/* SHOW START */
		
	// show login
	public function showLogin(){
		
		$ret = "";
		
		$msg = $this->message->load($this->cookieMessage);
		
		$userInput = $this->message->load($this->cookieUser);
		
		$usrLogged = $this->model->isUserLogged($this->getClientIdentifier());
		
		if($usrLogged){
			
			$user = $this->model->getUserName();
			
			$ret .= "<h1>Welcome " . $user . "</h1>
			<p>" . $msg . "</p>
			<a href='?logout'>Log out</a>";
		
		} else {
		
			$ret .= '<h1>Ej inloggad</h1>
			<form action="?login" method="post">
			<a href="?Register">Registrera ny användare</a>
				<fieldset>
					<legend>Login - Input username and password</legend>
					<p>' . $msg . '</p>
					<label for="usrNameId">Username:</label>
					<input type="text" name="LoginView::usrNameId" id="usrNameId" value="' . $userInput . '">
					<label for="passwordId">Password:</label>
					<input type="password" name="LoginView::passwordId" id="passwordId">
					<label for="keepLoggedId">Save credentials:</label>
					<input type="checkbox" name="Logged" id="keepLoggedId">
					<input type="submit" value="Log in">
				</fieldset>
			</form>';
			
		}
		
		$ret .= $this->showDate();
		return $ret;	
	}


	
	// show Date-message in swedish
	public function showDate(){
		return "<p>" . $this->svDay[date("D")] . ", den " . date("d") . " " . $this->svMonth[date("m")]  . " år " . date("Y") . ". Klockan är " . date("H:i:s") . ".</p>";
	}
	
	/* SHOW END */
	
}
