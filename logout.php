<?php

/* 
 *             2017-2018
 * Author : Silas riacourt <silasdu22@gmail.com>
 * 
 */
session_start();
setcookie('remember', NULL, -1);//on enlève le cookie qui garde en mémoire la connexion
unset($_SESSION['auth']);//destruction de la session
$_SESSION['flash']['success'] = 'Vous êtes maintenant déconnecté';
header('Location: login.php');//redirection