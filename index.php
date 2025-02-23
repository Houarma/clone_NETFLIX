<?php
	
	session_start();
	require_once("src/option.php");
	//faire plutot une connexion a la base de donnee pour connecter nos utilisateur
	//connecter notre user
	if (!empty($_POST["email"]) && !empty($_POST["password"])) {
		$email=htmlspecialchars($_POST["email"]);
		$password=htmlspecialchars($_POST["password"]);
		$password="aq1".sha1($password."123")."125";
		if ($email=htmlspecialchars($_SESSION["email"]) && $password=htmlspecialchars($_SESSION["password"])) {
			$_SESSION["email"]=$email;
			$_SESSION["password"]=$password;
			$_SESSION["connect"]=1;
			if (isset($_POST["auto"])) {
				setcookie('auth',$_SESSION['secret'],time()+3600*24*365,'/',"",false,false);
			}
			header("location:./index.php?connect=true&success=true&message=Vous etes connectez felicitations !");
			exit();
		} else {
			header("location:./index.php?error=true&message= adresse mail ou mot de passe invalide merci de réessayer !");
			exit();
		}
		
	}


?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Netflix|haphiz</title>
		<link
			rel="stylesheet"
			type="text/css" 
			href="styles/default.css"
		/>
		<link rel="icon" type="image/pngn" href="/images/favicon.png" />
	
	</head>
	<body>
		<?php require('src/header.php')?>

		<section>
			<div id="login-body">
				<?php if(isset($_GET['connect'])) { 
					$time=time();
					if ($time<=(3600*12)) {
						echo "<h1>Bonjour !</h1>";
					}else {
						echo"<h1>Bonsoir !</h1>";
					}
					?> 

				
				<?php
					if(isset($_GET['success'])){
						if(isset($_GET["message"])){

							echo'<div class="alert success">'.htmlspecialchars($_GET["message"]).'</div>';
						}
					} ?>
				<p>Qu'allez-vous regarder aujourd'hui ?</p>
				<small><a href="logout.php">Déconnexion</a></small>

				 <?php } else { ?>
					<h1>S'identifier</h1>

					<?php if(isset($_GET['error'])) {

						if(isset($_GET['message'])) {
							echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
						}

					} ?> 

				<form method="post" action="index.php">
					<input
						type="email"
						name="email"
						placeholder="Votre adresse email"
						required
					/>
					<input
						type="password"
						name="password"
						placeholder="Mot de passe"
						required
					/>
					<button type="submit">S'identifier</button>
					<label id="option"
						><input type="checkbox" name="auto" checked />Se souvenir
						de moi</label
					>
				</form>

				<p class="grey">
					Première visite sur Netflix ?
					<a href="inscription.php">Inscrivez-vous</a>.
				</p>
				<?php } ?>
			</div>
		</section>

		<?php require('src/footer.php'); ?>
	</body>
</html>
