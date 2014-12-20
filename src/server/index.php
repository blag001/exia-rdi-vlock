<?php

//////////////////////////////////
// chargement et initialisation //
//////////////////////////////////

// fonctions pour auto-charger les Class.ctr et les OdbClass.mdl
function load_controller($class)
{
	if(is_file('controller/'.$class .'.ctr.php'))
		require_once('controller/'.$class .'.ctr.php');
}
function load_model($class)
{
	if(is_file('model/'.$class .'.mdl.php'))
		require_once('model/'.$class .'.mdl.php');
}
spl_autoload_register('load_controller');
spl_autoload_register('load_model');


// on load les class de gestion des BDD
require_once ('toolSql/Bdd.class.php');
// on lance la session
session_start();

// on set les obj de connexion SQL et NoSql
// require_once 'inc/connexion.inc.php';
// fonction pour afficher les template
require_once 'inc/function.inc.php';

//////////////////////////
// Fin d'initialisation //
//////////////////////////

///////////////////////////////////////////////
// Controleur prncipale de gestion des pages //
///////////////////////////////////////////////

// on evite les error de variable !isset
if (!isset($_GET['page']))
	$_GET['page'] = null;

/**
 * switch principale
 *
 * doit gerer les routes pour lancer une instance
 * du controleur correspondent a la demande
 *
 * @param string $_GET['page'] contient la page demandee
 * @author benoit <benoitelie1@gmail.com>
 */
switch ($_GET['page']) {
	case 'velo':
		// new Velo();
		// break;

	case 'auto':

	default:
		new Auto();
		break;
}

///////////////////////
// on vide le tampon //
///////////////////////
$_SESSION['tampon'] = null;
