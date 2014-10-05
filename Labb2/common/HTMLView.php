<?php

class HTMLView {

		public function echoHTML($body) {
			echo "
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset='utf-8'>
					<title>LoginPHP ja222qm</title>
				</head>
				
				<body>
				<h2>Welcome to Juhani's World of Success! - ja222qm - Jh222vp Edit!!</h2>
					$body
				</body>
				</html>";
		}
}