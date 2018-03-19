<?php


echo "test";

date_default_timezone_set('Europe/Paris');
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=GPS;charset=utf8', 'root', 'ssi');
	}
	catch(Exception $e)
	{	
        die('Erreur : '.$e->getMessage());
	}

	$req = $bdd->prepare('SELECT latitude, longitude FROM coords');
	$req->execute();
	if ($donnees = $req->fetch() == 0){
		$status = "erreur aucune donnée";
		}
	else{
		//$status = "données recupéres avec succès";
		}
	$req->execute();    
	while($row = $req->fetch()) 
		{  

			echo $row["latitude"];
			echo $row["longitude"];

		}      
    echo $status;
?>
