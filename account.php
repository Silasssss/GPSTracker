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
	require 'inc/header.php'; 
?>
    <div class="container">
    <?php if(isset($_SESSION['flash'])): ?>
      <?php foreach($_SESSION['flash'] as $type => $message):?>
        <div class="alert alert-<?= $type; ?>">
          <strong>Info!</strong> <?= $message; ?>
        </div>
      <?php endforeach; ?>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?> 
  		<h1>Bonjour <?= $_SESSION['auth']->username; ?></h1>

  		<form action="" method="post">
  			<div class="form-group">
  				<input class="form-control" type="password" name="password" placeholder="Changer de mot de passe"/>
  			</div>
   			<div class="form-group">
  				<input class="form-control" type="password" name="password_confirm" placeholder="Confirmation du mot de passe"/>
  			</div>
  			<button class="btn btn-primary">Changer mon mot de passe</button>
		</form>
 
    </div>
	<?php require 'inc/footer.php'?>
