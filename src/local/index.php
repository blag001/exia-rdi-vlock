<?php
// declaration du fichier d'interface software/hardware

	// constante globale de la station
define('STATION', 'ROUEN-02');
	// constante de l'URL de l'app
define('WEB_APP', 'http://10.42.0.1/www/exia-rdi-vlock/src/server'); // en prod : 'http://api.vlock.com'

	// si on demande un delock
if(
	!empty($_GET['action'])
	and $_GET['action'] == 'unlock'
	and !empty($_GET['value'])
	)
{
		// mode GPIO output
	exec('gpio mode '.$_GET['value'].' out');
		// on demande au GPIO d'ouvrir l'emplacement
	exec('gpio write '.$_GET['value']. ' 1');
		// retour sur l'API avec un message de validation
	header('Location: '.WEB_APP.'/index.php?action=unlock_success');
}
elseif(
	!empty($_GET['action'])
	and $_GET['action'] == 'lock'
	and !empty($_GET['value'])
	) // si on lock
{
		// mode GPIO output
	exec('gpio mode '.$_GET['value'].' out');
		// on demande au GPIO d'ouvrir l'emplacement
	exec('gpio write '.$_GET['value']. ' 0');
		// retour sur l'API avec un message de validation
	header('Location: '.WEB_APP.'/index.php?action=lock_success');
}
else // en cas d'erreur, on repart sur l'API
	header('Location: '.WEB_APP.'/index.php');
