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
    
    $req->bindParam(':date', $date);
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
<!DOCTYPE html>

<?php require 'inc/header.php';?>
    </style> 
<div class="container">
  <h1>Vos trajets</h1>
    <?php if(isset($_SESSION['flash'])): ?>
      <?php foreach($_SESSION['flash'] as $type => $message):?>
        <div class="alert alert-<?= $type; ?>">
          <strong>Info!</strong> <?= $message; ?>
        </div>
      <?php endforeach; ?>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>    
  <form class="form-horizontal">
    <fieldset>

        <legend>Rechercher un trajet</legend>
        <?php 
        
                   while ($row = get_départ("2018-05-14")->fetch){
                   echo $row['latitude'];
                   echo $row['longitude'];
            }
        
        ?>
        <div class="form-group">
            
            <label class="col-md-4 control-label" for="Date">Date</label>  
            <div class="col-md-4">
                <input type="text" class="form-control" name="date" id="calendrier" placeholder="Sélectionner une date" required="1">
    
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label" for="checkboxes">Transport</label>
            <div class="col-md-4">
                <div class="checkbox">
                    <label for="checkboxes-0">
                        <input name="cb" id="checkboxes" value="0" type="checkbox">
                            Voiture
                    </label>
                </div>
                 <div class="checkbox">
                     <label for="checkboxes-1">
                         <input name="cb" id="checkboxes" value="1" type="checkbox">
                            Vélo
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label" for="button1"></label>
            <div class="col-md-4">
                <button id="button1" name="" class="btn btn-primary">Rechercher</button>
            </div>
        </div>
    </fieldset>
  </form>
</div>
<?php 
if(isset($_POST['date']))
{
  $date_sel = $_POST['date'];
}
else{
  $date_sel = date("Y-m-d");

}
?>
    <br></br>

    <div id="map"></div>
    <script>
          downloadUrl('http://localhost/GPSTracker/inc/convert.php', function(data) {
              
            var xml = data.responseXML;
            var polylinePlanCoordinates  = [];
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var date = markerElem.getAttribute('date');
              var capteur = markerElem.getAttribute('capteur_id');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));
            });
          });
      doNothing();

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: {lat: 48.1843903, lng: -2.762291},
          mapTypeId: 'terrain'
        });
         var image_arriver = 'inc/drapeau_a.png';
         var image_départ = 'inc/drapeau_d.png';
        var départ = new google.maps.Marker({
          position: {lat: 48.1772994, lng:  	-2.6017738},
          map: map,
          icon: image_arriver
        });         
        var arriver = new google.maps.Marker({
          position: {lat: 48.1836724, lng: -2.7463238},
          map: map,
          icon: image_départ
        });
        var test = [
            <?php

             while ($row = $req->fetch()){
                   $lat = $row['latitude'];
                     $lon = $row['longitude'];
                   echo 'new google.maps.LatLng('.$lat.', '.$lon.'),';
            }
             ?>
                         
        ];
        var flightPath = new google.maps.Polyline({
          path: test,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
      }


    </script>
        <script>
        var infoWindow = new google.maps.InfoWindow;
          downloadUrl('http://localhost/GPSTracker/inc/convert.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var date = markerElem.getAttribute('date');
              var capteur = markerElem.getAttribute('capteur_id');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

            });
          });
        }
      doNothing();
    </script>
      </script>
           <script type="text/javascript">
            $(document).ready(function () {
                
                $('#calendrier').datepicker({
                    autoclose: true,  
                    weekStart: 1,
                    format: "yyyy-mm-dd"
                });  
            
            });
        </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANFCjBuEsUO1o49ZVkXdukdZ2OLUfnajg&callback=initMap"></script>
</body>

<script>
    $('.datepicker').datepicker();
</script>
</html> 