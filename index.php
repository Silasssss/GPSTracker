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
    <?php
    if ($donnees = $req->fetch() == 0){
            $status = "aucun trajet n'a été trouvé pour cette date ($date)";
            ?>
            <div class="alert alert-danger" role="alert">
              <strong>ERREUR : </strong> <?php echo $status; ?>.
            </div>
    <?php
    }
    else{
        
        $_SESSION['flash']['info'] = "1 trajet à été trouvé le ($date)";?>
        <div class="alert alert-success" role="alert">
          <strong>Succès!</strong> 1 trajet à été trouvé le (<?php echo $date; ?>) <a href="view.php?date=<?php echo $date; ?>" class="alert-link">cliquer ici pour le voir</a>.
        </div>
    <?php
    }
    $count = $req->fetchColumn(0);
    $req->execute();
  ?>
  <form class="form-horizontal">
    <fieldset>

        <legend>Rechercher un trajet</legend>
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

           <script type="text/javascript">
            $(document).ready(function () {
                
                $('#calendrier').datepicker({
                    autoclose: true,  
                    weekStart: 1,
                    format: "yyyy-mm-dd"
                });  
            
            });
        </script>
</body>
<?php require 'inc/footer.php'?>
<script>
    $('.datepicker').datepicker();
</script>
</html> 