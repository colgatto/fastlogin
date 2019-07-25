<?php	
	session_start();
	if(isset($_SESSION['fastLoginSuccess']) && $_SESSION['fastLoginSuccess'] === true){
		if(isset($_REQUEST["submit"])){
			exit('index');
		}
		header('location: index.php');
		die();
	}

	require_once('config.php');

	function send($userId, $text){
		file_get_contents(TELEGRAM_API_URL . 'sendMessage?chat_id=' . $userId . '&text=' . $text);
	}
	function codeGenerator(){
		$consonant = ['b','c','d','f','g','h','j','k','l','m','n','p','q','r','s','t','v','z'];
		$vowel = ['a','e','i','o','u'];
		$c_l = count($consonant) - 1;
		$v_l = count($vowel) - 1;
		return $consonant[rand(0,$c_l)] . $vowel[rand(0,$v_l)] . $consonant[rand(0,$c_l)] . $vowel[rand(0,$v_l)] . rand(0, 9) . rand(0, 9) . rand(0,9);
	}

	if(isset($_REQUEST["username"])){
		if(!isset($userList[$_REQUEST["username"]])){
			exit('username');
		}
		$code = codeGenerator();
		$_SESSION['lastCode'] = [
			'code' => $code,
			'time' => time()
		];
		send($userList[$_REQUEST["username"].''], $code);
		exit('code');
	}
	if( isset($_REQUEST["code"]) && isset($_SESSION['lastCode']) && isset($_SESSION['lastCode']['code']) && isset($_SESSION['lastCode']['time']) ){	
		if($_SESSION['lastCode']['time'] > time() + 30){
			unset($_SESSION['lastCode']);
			exit('username');
		}
		if($_REQUEST["code"] === $_SESSION['lastCode']['code']){
			unset($_SESSION['lastCode']);
			$_SESSION['fastLoginSuccess'] = true;
			exit('index');
		}else{
			exit('code');
		}
	}
?>
<!doctype html><html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Rokkitt&display=swap" rel="stylesheet"> 
	<link href="background.css" rel="stylesheet"> 
	<title>Login</title>
	<style>
		.login_wrapper{
			width: 350px;
			margin: 300px auto;
		}
		.login_wrapper form{
			text-align: center;
			color: #ddd;
			font-family: 'Rokkitt', sans-serif;
		}
		.login_wrapper input{
			margin: 0;
			border: 0;
			background: transparent;
			border-bottom: 1px solid #00ff42;
			border-bottom: linear-gradient(90deg, #00ff42 40%, #020024 100%);
			width: calc(100% - 40px);
		}
		.login_wrapper button{
			margin: 0;
			padding: 2px;
			width: 30px;
			border: 1px solid #414141;
			background-color: #414141;
			border-radius: 4px;
			color: #ddd;
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
	<!-- BACKGROUD -->
	<div id='stars'></div>
	<div id='stars2'></div>
	<div id='stars3'></div>
	<!-- LOGIN -->
	<div class="login_wrapper">
		<form id="login_form">
			<h2>Login</h2>
			<input placeholder="Username" autofocus="" type="text" name="username">
			<input name="ask" type="hidden" value="username">
			<button type="submit">Go</button>
		</form>
	</div>
	<script>
		$('#login_form').submit(function(e){
			e.preventDefault();
			let f = $(this);
			let i_ask = f.find('input[name=ask]');			
			let ask = i_ask.val();
			let i_val = f.find('input[name='+ask+']');
			let val = i_val.val();
			let param = {};
			param[ask] = val;
			$.post('#', param, (data) => {
				switch (data) {
					case 'code':
						i_val.val('');
						i_val.attr('placeholder','Code');
						i_val.attr('name','code');
						i_ask.val('code');
						break;
					case 'index':
						window.location.replace("index.php");
						break;
					case 'username':
						i_val.val('');
						i_val.attr('placeholder','Username');
						i_val.attr('name','username');
						i_ask.val('username');
						break;
					default:
						console.log(data);
						break;
				}
			});
		});
	</script>
</body>
</html>