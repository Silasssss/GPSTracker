<?php
/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 * 
 * BUT :  Pouvoir visualiser un  trajet réalisé en voiture ou à pieds
 *        données préalablement récupérés par un shield GPS
 *        Architecture du site : - visualisation du dernier trajet sur carte
 *                               - Affichage de statistique sur ce trajet (durée , vitesse , heure départ - heure arrivée)
 *                               - Possibilitée de selectionner un trajet
 * 
 * 
 * 
 */
require("inc/bdd.php");

	$req = $bdd->prepare('SELECT latitude, longitude FROM coords');
	$req->execute();
	if ($donnees = $req->fetch() == 0){
		$status = "erreur aucune donnée";
		}
	else{
		$status = "données recupéres avec succès";
		}
	$req->execute();    
	while($row = $req->fetch()) 
		{  

			echo $row["latitude"];
			echo $row["longitude"];

		}      
    echo $status;
?>
<h3></h3>
<div id="map"></div>
#map {
  height: 400px;
  width: 100%;
 }
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANFCjBuEsUO1o49ZVkXdukdZ2OLUfnajg&callback=initMap">
</script>
<script src="inc/function.js"></script>
<!DOCTYPE html>
<html>
  <head>
    <style>
       #map {
        height: 400px;
        width: 50%;
       }
    </style>
  </head>
  <body>
    <h3></h3>
    <div id="map"></div>
    <script>
      function initMap() {
        var uluru = {lat: 48.1843903, lng: -2.762291};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }

    </script>
  </body>
</html>
