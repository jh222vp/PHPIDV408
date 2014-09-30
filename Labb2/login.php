<?php
//Session
session_start();

require_once ('./common/HTMLView.php');
require_once ('./LoginController.php');


$c = new loginController();
$htmlBody = $c->authenticate();

$view = new HTMLView(); 
$view->echoHTML($htmlBody);
