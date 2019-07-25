<?php
	session_start();
	if(!isset($_SESSION['fastLoginSuccess']) || $_SESSION['fastLoginSuccess'] !== true){
		header('location: login.php');
		die();
	}
?>
<!DOCTYPE html><html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Rokkitt&display=swap" rel="stylesheet">
	<title>Pivate</title>
	<style>
		html,body{
			background-color: #000;
			color: #d0d0d0;
			font-family: 'Rokkitt', serif;
		}
	</style>
</head>
<body>
	<h2>This page is Pivate!</h2>
</body>
</html>