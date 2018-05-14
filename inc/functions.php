<?php
/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */
function debug($variable){

	echo '<pre>' . print_r($variable, true) . '</pre>';
}
function get_départ($date){
    require("bdd.php");
        $req = $bdd->prepare('SELECT * FROM coords WHERE (DATE(date) = :date) ORDER BY ID ASC LIMIT 1'); 
       // SELECT * FROM `coords` WHERE `date` = '2018-05-14' ORDER BY ID ASC LIMIT 1
        $req->bindParam(':date', $date);
        $req->execute();
        $donnees = $req->fetch();
	if ($donnees === 0){
		$status = "erreur aucune donnée";
		}
	else{
		$status = "données recupéres avec succès";
		}
                
	$req->execute();
        return $donnees;
}
function get_arriver($date){
    require("bdd.php");
        $req = $bdd->prepare('SELECT * FROM coords WHERE (DATE(date) = :date) ORDER BY ID DESC LIMIT 1'); 
       // SELECT * FROM `coords` WHERE `date` = '2018-05-14' ORDER BY ID ASC LIMIT 1
        $req->bindParam(':date', $date);
        $req->execute();
        $donnees = $req->fetch();
	if ($donnees === 0){
		$status = "erreur aucune donnée";
		}
	else{
		$status = "données recupéres avec succès";
		}
                
	$req->execute();
        return $donnees;
}
function str_random($length){
	$alphabet ="0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
	return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);

}
function get_url(){
	$url ="localhost/GPSTracker";
	return $url;

}
function logged_only(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
        header('Location: login.php');
        exit();
    }
}
function reconnect_from_cookie(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(isset($_COOKIE['remember']) && !isset($_SESSION['auth']) ){
        require_once 'bdd.php';
        if(!isset($pdo)){
            global $pdo;
        }
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();
        if($user){
            $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs');
            if($expected == $remember_token){
                session_start();
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            } else{
                setcookie('remember', null, -1);
            }
        }else{
            setcookie('remember', null, -1);
        }
    }
}