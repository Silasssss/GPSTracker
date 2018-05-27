<?php
/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */
  require 'inc/functions.php';
  logged_only();
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

    $départ = (get_départ($date));
    $arriver = (get_arriver($date));
    $d = strtotime($départ['time']);
    $a = strtotime($arriver['time']);
    $temps = gmdate("H:i:s", $a-$d);
    $km = distanceCalculation($départ['latitude'], $départ['longitude'], $arriver['latitude'], $arriver['longitude']);
?>
<!DOCTYPE html>

<?php require 'inc/header.php';?>
    </style> 
<div class="container">

  <section id="stats">
    <div class="container-fluid">
      <h2 class="section-title mb-2 h2">Trajet du <?php echo $_GET['date']?></h2>
      <div class="row mt-5">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          <div class="card">
              <h3 class="card-title">Durée du trajet : <span class="value"><?php echo $temps?></span></h3>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          <div class="card">
              <h3 class="card-title">Distance : <span class="value"><?php echo $km?> km</span></h3>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
          <div class="card">
              <h3 class="card-title">Vitesse moyenne : 00 km/h</h3>
          </div>
        </div>
      </div>
    </div>  
  </section>
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
    <br></br>
    <br></br>
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
          zoom: 11,
          center: {lat: 48.1642816, lng: -2.5977877},
          mapTypeId: 'terrain'
        });
         var image_arriver = 'inc/drapeau_a.png';
         var image_départ = 'inc/drapeau_d.png';

        var départ = new google.maps.Marker({
          position: {lat:<?php echo $départ['latitude'];?>, lng:<?php echo $départ['longitude'];?>},
          map: map,
          icon: image_départ
        });         
        var arriver = new google.maps.Marker({
          position: {lat:<?php echo $arriver['latitude'];?>, lng:<?php echo $arriver['longitude'];?>},
          map: map,
          icon: image_arriver
        });
        var trajet = [
            <?php
             while ($row = $req->fetch()){
                   $lat = $row['latitude'];
                     $lon = $row['longitude'];
                   echo 'new google.maps.LatLng('.$lat.', '.$lon.'),';
            }
             ?>         
        ];
        var flightPath = new google.maps.Polyline({
          path: trajet,
          geodesic: true,
          strokeColor: '#2700ff',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });
        flightPath.setMap(map);
      }


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
<?php require 'inc/footer.php'?>