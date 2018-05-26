<?php
/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */
#BUT    : récupérer les données en BDD afin de les exporter au format XML
#		: - connection à la BDD - récupération de la table entière (période sélectionable)
#		: - utiliser la fonction DOM de php pour exporter les données en un fichier XML
#		: - création d'un fichier XML


require("bdd.php");
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);
    if (isset($_GET['date']))
    {
        $date = $_GET['date'];
    }
    else
    {
        date_default_timezone_set('Europe/Paris');
        $date = date("Y-m-d");
    }
    if(isset($_GET['cb']))
    {
        $type_trajet = $_GET['cb'];
    }
    else
    {
        $type_trajet = 0;    
    }
        $req = $bdd->prepare('SELECT * FROM coords WHERE type_trajet = :trajet AND (DATE(date) = :date)'); 
        $req->bindParam(':date', $date);
        $req->bindParam(':trajet', $type_trajet);
        $req->execute();
        $donnees = $req->fetch();
	if ($donnees === 0){
		$status = "erreur aucune donnée";
		}
	else{
		$status = "données recupéres avec succès";
		}
	$req->execute();   
header("Content-type: text/xml");
while ($row = $req->fetch()){
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("capteur_id",$row['capteur_id']);
  $newnode->setAttribute("date", $row['date']);
  $newnode->setAttribute("lat", $row['latitude']);
  $newnode->setAttribute("lng", $row['longitude']);
  $newnode->setAttribute("alt", $row['altitude']);
  $newnode->setAttribute("type_trajet", $row['type_trajet']);
  $newnode->setAttribute("id", $row['id']);
}
echo $dom->saveXML();
?>

