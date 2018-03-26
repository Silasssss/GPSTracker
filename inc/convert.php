<?php
#BUT    : récupérer les données en BDD afin de les exporter au format XML
#		: - connection à la BDD - récupération de la table entière (période sélectionable)
#		: - utiliser la fonction DOM de php pour exporter les données en un fichier XML
#		: - création d'un fichier XML

require("bdd.php");


$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);


$connection=mysql_connect ('localhost', $username, $password);
if (!$connection) {  die('erreur de connexion : ' . mysql_error());}


$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('' . mysql_error());
}



$query = "SELECT * FROM coords WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('' . mysql_error());
}

header("Content-type: text/xml");



while ($row = @mysql_fetch_assoc($result)){

  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("capteur_id",$row['capteur_id']);
  $newnode->setAttribute("date", $row['datetime']);
  $newnode->setAttribute("lat", $row['latitude']);
  $newnode->setAttribute("lng", $row['longitude']);
  $newnode->setAttribute("alt", $row['altitude']);
}

echo $dom->saveXML();

?>

