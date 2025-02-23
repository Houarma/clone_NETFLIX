<?php
    session_start();
    require_once("connexion.php");
    if (isset($_COOKIE["auth"]) && !isset($_SESSION['connect'])) {
        $secret=htmlspecialchars($_COOKIE['auth']);
        $req=$bdd->prepare("SELECT COUNT(*) AS secretNumber FROM users WHERE secret=:secret");
        $req->execute([
            "secret"=>$secret
        ]);
        while ($response=$req->fetch()) {
          if ($response["secretNumber"]==1) {
            $information=$bdd->prepare("SELECT * FROM users WHERE secret=:secret");
            $information->execute([
                "secret"=>$secret
            ]);
            while ($userInformation=$information->fetch()) {
                $_SESSION["email"]=$userInformation["email"];
			
			    $_SESSION["connect"]=1;
            }
          }
        }
    }