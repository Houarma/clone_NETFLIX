<?php 
	//connexion a la bdd
	require("src/connexion.php");
	require_once("src/option.php");
	if (isset($_SESSION["connect"])) {
		header('location:index.php?');
		exit();
	}else {
		
		if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty("password_two")) {
			//declaration des var
	
			$email=htmlspecialchars($_POST["email"]);
			$password=htmlspecialchars($_POST["password"]);
			$password="aq1".sha1($password."123")."125";
			$passwordTwo=htmlspecialchars($_POST["password_two"]);
			$passwordTwo="aq1".sha1($passwordTwo."123")."125";
			$secret=time().rand().rand();
			
			//verification des deux pssword
			if ($password!=$passwordTwo) {
	
				$quest=$bdd->query("SELECT * FROM users");
				while ($id=$quest->fetch()) {
					if (1) {
						
						//suppression  dans la bdd
						$delete=$bdd->prepare("DELETE FROM users WHERE secret=:secret");
						$delete->execute([
							"secret"=>$secret
						]);
						//redirection
						header("location:inscription.php?error=true&message=Les deux mots de passe ne sont pas identiques.");
						exit();
					}
				}
				
			}
			else {
				
				//verification de la validite de l email
				if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
					$quest=$bdd->query("SELECT * FROM users");
					while ($id=$quest->fetch()) {
						if (1) {
							
							//suppression  dans la bdd
							$delete=$bdd->prepare("DELETE FROM users WHERE secret=:secret");
							$delete->execute([
								"secret"=>$secret
							]);
							//redirection
							
							header("location:inscription.php?error=true&message=Votre adresse email est invalide.");
							exit();
						}
				}
				}
				else{
		
					//insertion des donnees receuilli
				
					$quest=$bdd->prepare("INSERT INTO users(email,password,secret) VALUES(:email,:password,:secret)");
					$quest->execute([
						
						
						"email"=>$email,
						"password"=>$password,
						"secret"=>$secret
					
					
					]);
		
					//verification des doublons
					$double=$bdd->query("SELECT * FROM users ORDER BY id DESC");
					
			
					$request=$bdd->prepare("SELECT COUNT(*) as numberEmail FROM users WHERE email=:email ");
					$request->execute([
						  "email"=> $email,
						  
					]);
					
					while ($resultat = $request->fetch()) {
						if ($resultat["numberEmail"]!=1) {
							
							//suppression u doublon dans la bdd
							$delete=$bdd->prepare("DELETE FROM users WHERE secret=:secret");
							$delete->execute([
								"secret"=>$secret
							]);
							//redirection
							header("location: ./inscription.php?error=true&message=cette email est deja utilsée!");
							exit();
						}
					}
					 //il ya un erreur ici sa fonctionne pas comme il se doit dans la bdd verifie cette erreur
					$request=$bdd->prepare("SELECT COUNT(*) as numberPassword FROM users WHERE password=:password");
					$request->execute([
						  "password"=> $password
					]);
					while ($resultat = $request->fetch()) {
						if ($resultat["numberPassword"]!=1) {
							
							//suppression u doublon dans la bdd
							$delete=$bdd->prepare("DELETE FROM users WHERE secret=:secret");
							$delete->execute([
								"secret"=>$secret
							]);
							//redirection
							header("location: ./inscription.php?error=true&message=veuillez modifier votre mot de passe!");
							exit();
						}
					}
					
				  
				
				
				}
				
		
				//creation de la sesssion
				if (!empty($email) && !empty($password)) {
					
					session_start();
					$_SESSION["email"]=$email;
					$_SESSION["password"]=$password;
					$_SESSION['connect']=1;
					$_SESSION['secret']=$secret;
					header("location:./index.php?");
					exit();
				}
			}
			
	
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link
			rel="stylesheet"
			type="text/css" 
			href="styles/default.css"
		/>
		<link rel="icon" type="image/pngn" href="/images/favicon.png" />
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>
			<?php if(isset($_GET['error'])) {

						if(isset($_GET['message'])) {
							echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
						}

					} 
			?> 

			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>
	