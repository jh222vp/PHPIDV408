<?php

require_once ('./common/HTMLView.php');
require_once("./LoginView.php");
require_once("./LoginModel.php");
require_once("./View/RegisterUserView.php");

class LoginController{
	private $model;
	private $view;
	private $messageStorage = "cookieMessage";
	private $registeruserView;
	private $htmlView;
	private $con;
	private $password;
	
	public function __construct(){
	
		$this->htmlView = new HTMLView();
		$this->model = new LoginModel();
		$this->view = new LoginView($this->model);
		$this->registerUserView = new RegisterUserView();
		$this->con = new mysqli("127.0.0.1", "root", "", "labb4");
	}
	
	public function authenticate(){
		
		// if user is logged
		if($this->model->isUserLogged($this->view->getClientIdentifier())){
			
			/* Use Case 2 Logging out an authenticated user */
			return $this->logoutUser();
	
		// if user is out logged and...
		} else {
			
			// ...has stored credentials
			if($this->view->hasStoredCredentials()){
				
				/* Use Case 3 Authentication with saved credentials */
				return $this->authCredUser();
				
			// ...does not have stored credentials
			} else {
				
				/* Use Case 1 Authenticate user */
				return $this->authUser();
			}
		}
	}
	
	public function sendCookie(){
	
		$collectpass = $this->view->getInputPassword(false);

		$this->model->setCryptedCookiePass($collectpass);
		$this->view->setCookiePass($this->model->getCryptetCookiePass());
		}

	/* Use Case 1 Authenticate user */
	public function authUser(){
			
		$didUserClickRegister = $this->registerUserView->registerNewUser();
		
		if($didUserClickRegister)
		{
			if($this->registerUserView->submitNewUser())
			{
				//Samlar in användarnamn och lösenord som skrivs in av användaren..
				$registerUsername = $this->registerUserView->getInputName();
				$registerPassword = $this->registerUserView->getInputPassword();
				$repeatpassword = $this->registerUserView->getRepeatPass();
				
				//Tvättar strängarna
				$safeCollectedUsername = mysqli_real_escape_string($this->con ,$registerUsername);
				$safeCollectedPassword = mysqli_real_escape_string($this->con ,$registerPassword);
				$safeCollectedPassword2 = mysqli_real_escape_string($this->con ,$repeatpassword);
				
				
			
				$validAddUser = false;
				

				
				$returner = $this->registerUserView->ViewLogin();
				$ValidateLength = $this->model->validateUserRegistration($safeCollectedUsername, $safeCollectedPassword, $safeCollectedPassword2);

				$addUser = true;
				
				//Validerar så att lösenorden är korrekt

				switch($ValidateLength){
					
					case 1: {$this->registerUserView->usernameAndPasswordToShortMessage(); $this->registerUserView->ViewLogin();break;}
					case 2: {$this->registerUserView->usernameToShort();$this->registerUserView->ViewLogin(); break;}
					case 3: {$this->registerUserView->passwordIsToShort();$this->registerUserView->ViewLogin();break;}
					case 4: {$this->registerUserView->passwordsDontMatchEachOther(); $this->registerUserView->ViewLogin();break;}
					case 5: {$this->registerUserView->notAllowedsymbolsMessage(); $this->registerUserView->viewLogin();break;}
					
				}
				
					if($this->model->usernameIsAlreadyTaken($registerUsername))
					{
						
						$this->registerUserView->usernameIsOccupied();
					}
					if($ValidateLength)
					{
						$this->registerUserView->ViewLogin();
					}
					else
					{
						$this->model->registerUser($registerUsername, $registerPassword);
						echo "registrering lyckades $registerUsername";
					}
				
			}

			return $this->registerUserView->ViewLogin();
		}
	
		$hej = $this->view->userTryLogin();
		
		if($hej){
			
		 	// UC 1 3: user provides username and password
			$inpName = $this->view->getInputName(false);
			$inpPass = $this->view->getInputPassword(false);
			
			
			
			
			// UC 1 3a: user wants system to keep user credentials for easier login
			
			
			
			
			
			
			
			
			// UC 1 4: authenticate user...
			$answer = $this->model->loginUser($inpName, $inpPass, $this->view->getClientIdentifier());
			
			// UC 1 4a: user could not be authenticated
			if($answer === false){
				
				// 1. System presents an error message
				// 2. Step 2 in main scenario
				
				$this->view->storeUserInput($inpName);
				
				// redirects to self
				
				//$this->view->storeMessage("Felaktigt användarnamn och/eller lösenord");
				
				
			} else {
				
				$keepCreds = $this->view->keepCredentials();

				if($keepCreds){
					
					$this->sendCookie();
					// UC 1 3a-1: ...system presents that...the user credentials were saved
					$expTime = $this->view->storeCredentials($inpName, $inpPass);
					$this->model->storeCookieDate($inpName, $expTime);
					
					/* redirects to self */
					$this->view->storeMessage("Inloggning lyckades och vi kommer ihåg dig nästa gång");
				} else {
					
					// ...and present success message
					
					/* redirects to self */
					$this->view->storeMessage("Inloggningen lyckades");
				}
			}
		} else {
			
			// UC 1 1: user wants to authenticate
		 	// UC 1 2: system asks for username, password and if system should save the user credentials
			return $this->view->showLogin();
		}
	}
	
	/* Use Case 2 Logging out an authenticated user */
	public function logoutUser(){
		 
		// UC 2 3: User tells the system he wants to log out
		if($this->view->userTryQuit()){
			
			// UC 2 4: The system logs the user out and presents a feedback message
			$this->model->logoutUser();
			$this->view->removeCredentials();
			
			/* redirects to self */
			$this->view->storeMessage("Du har blivit utloggad");
			
		// UC 2 1: The system presents a logout choice	
		} else {
			return $this->view->showLogin();
		}
	}
	
	/* Use Case 3 Authentication with saved credentials */
	private function authCredUser(){
		
		// UC 3 1: User wants to authenticate with saved credentials
			// - System authenticates the user and presents that the authentication succeeded and that it happened with saved credentials
		$inpName = $this->view->getInputName(true);
		$inpPass = $this->view->getInputPassword(true);
		$answer = $this->model->loginCredentialsUser($inpName, $inpPass, $this->view->getClientIdentifier());
		
		if($answer){
			
			/* redirects to self */
			$this->view->storeMessage("Inloggning lyckades via cookies");
		} else {
			
			// 2a. The user could not be authenticated (too old credentials > 30 days) (Wrong credentials) Manipulated credentials.
				// 1. System presents error message
				// Step 2 in UC 1
				
			$this->view->removeCredentials();
				
			/* redirects to self */
			$this->view->storeMessage("Felaktig information i cookie");
		}
	}	
}