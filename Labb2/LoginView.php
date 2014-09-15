<?php

require_once("common/CookieStorage.php");

class LoginView{
	
	private $cookieMessage = "cookieMessage";
	private $message;
	private $model;
	private $svDay = array("Mon"=>"Måndag", "Tue"=>"Tisdag", "Wed"=>"Onsdag", "Thu"=>"Torsdag", "Fri"=>"Fredag", "Sat"=>"Lördag", "Sun"=>"Söndag");
	private $svMonth = array("01"=>"Januari", "02"=>"Februari", "03"=>"Mars", "04"=>"April", "05"=>"Maj", "06"=>"Juni", "07"=>"Juli", "08"=>"Augusti", "09"=>"September", "10"=>"Oktober", "11"=>"November", "12"=>"December");
	
	
	public function __construct(LoginModel $model){
		$this->model = $model;
		$this->message = new CookieStorage();
	}
	
	/* INPUT START */
	
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
			$name = "";
				
			if(isset($_POST["LoginView::usrNameId"]) and strlen(trim($_POST["LoginView::usrNameId"])) !== 0){
				$name =$_POST["LoginView::usrNameId"];
			}
			return $name;
		}
	}
	
	// retrieve user input password
	public function getInputPassword($stored){
		if($stored){
			return $_COOKIE['loginPassword'];
		} else {
			$pass = "";
			
			if(isset($_POST["LoginView::passwordId"]) and strlen(trim($_POST["LoginView::passwordId"])) !== 0){	
		
				$pass = md5($_POST["LoginView::passwordId"]);
			} 
			return $pass;
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
		if(isset($_POST["LoginView::Logged"])){
			return true;
		} else {
			return false;
		}
	}
	
	// store credentials
	public function storeCredentials($name, $pass){
		setcookie("loginUser", $name, time()+3600);
		setcookie("loginPassword", $pass, time()+3600);
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
	
	/* COOKIE END */
	
	/* SHOW START */
		
	// show login
	public function showLogin(){
		
		$ret;
		
		$msg = $this->message->load($this->cookieMessage);

		if($this->model->isUserLogged()){
			
			$user = "temporary user";
			
			$ret = "<h1>Welcome " . $user . "</h1>
			<p>" . $msg . "</p>
			<a href='?logout'>Log out</a>";
		
		} else {
		
			$ret = '<form action="?login" method="post">
			<fieldset>
			<legend>Login - Input username and password</legend>
			<p>' . $msg . '</p>
			<label for="usrNameId">Username:</label>
			<input type="text" name="LoginView::usrNameId" id="usrNameId">
			<label for="passwordId">Password:</label>
			<input type="password" name="LoginView::passwordId" id="passwordId">
			<label for="keepLoggedId">Save credentials:</label>
			<input type="checkbox" name="LoginView::Logged" id="keepLoggedId">
			<input type="submit" value="Log in">';
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
