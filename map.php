<?php
/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */
  require 'inc/functions.php';
  logged_only();
  if(!empty($_POST)){

    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
        $_SESSION['flash']['danger'] = "Les mots de passes ne correspondent pas";
    }else{
        $user_id = $_SESSION['auth']->id;
        $password= password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once 'inc/bdd.php';
        $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$password,$user_id]);
        $_SESSION['flash']['success'] = "Votre mot de passe a bien été mis à jour";
    }

}
    if (isset($_GET['date']))
    {
 
        $date = $_GET['date'];

    }
    else
    {
        date_default_timezone_set('Europe/Paris');
        $date = date("Y-m-d");
    }
    require 'inc/bdd.php';
    

    if(isset($_GET['cb']))
    {
        $type_trajet = $_GET['cb'];
    }
    else
    {
        $type_trajet = 0;    
    }
    $req = $bdd->prepare('SELECT * FROM coords WHERE type_trajet = :trajet AND (DATE(date) = :date)');
    
    $req->bindParam(':date', $_GET['date']);
    $req->bindParam(':trajet', $type_trajet);
    $req->execute();
    if ($donnees = $req->fetch() == 0){
            $status = "Aucune donnée pour cette période ($date)";
            $_SESSION['flash']['danger'] = "Aucune donnée pour cette période <strong>($date)</strong>";
    }
    else{
        
        $_SESSION['flash']['info'] = "1 trajet à été trouvé le ($date)";
    }
    $count = $req->fetchColumn(0);
    $req->execute();
?>
<!DOCTYPE html >
<!---

	BUT : Afficher un trajet grace aux coordonnées GPS en BDD
	Méthode : Utilisation du format XML afin de travailler avec Javascript
			: Une fois les coordonées récupérés création d'une carte avec différents marquers
			: Initialisation de la carte (centre = calcul du point central du trajet)
			: Demande création fichier XML "convert.php"
			: Récupération des marquers nécéssaire Lat,lng,id,alt,datetime
			: Création 

-->

<?php require 'inc/header.php';?>
    </style> 

	  	<h3 id="dev"></h3>
    <div id="map"></div>
	
    <script>
      var customLabel = {
        1: {
          label: '1'
        },
        2: {
          label: '2'
        }
      };
        //initialisaiton de la map
        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(48.1843903, -2.762291),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;
        //récupération des infos au format xml
        downloadUrl('http://localhost/GPSTracker/inc/convert.php?date=<?php echo $date;?>', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var date = markerElem.getAttribute('date');
              var capteur = markerElem.getAttribute('capteur_id');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));
            //création boite qui contient les infos sur le point
		//div & test
              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = capteur
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));
              var text = document.createElement('text');
              text.textContent = date
              infowincontent.appendChild(text);
              var icon = customLabel[capteur] || {};
              //Affichage des markers
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
                                document.getElementById("dev").innerHTML = point;
              //évenement pour détection le click sur le marker et afficher les infos sur celui-ci
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }
      doNothing();
      
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANFCjBuEsUO1o49ZVkXdukdZ2OLUfnajg&callback=initMap">
    </script>
  </body>
</html>
