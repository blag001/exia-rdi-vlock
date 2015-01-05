<?php
// declaration du fichier d'interface software/hardware

	// constante globale de la station
define('STATION', 'ROUEN-02');
	// constante de l'URL de l'app
define('WEB_APP', 'http://localhost/vlock'); // http://api.vlock.com

function _csv($in=null){
	return _csv('"', '""', $in);
}

function log_file($action=null, $where=null)
{
	if(!is_dir('log/'))
		mkdir('log/', 0700);
		//ouverture ou creation du fichier de log
	$fichier_log = fopen('log/log_' .date('y-m') , 'a+');
	// $ip = $_SERVER['REMOTE_ADDR']; // @todo on en fait quoi?

		//enregistrement de l'entree
	fputs($fichier_log ,
		'"'.date('Y-m-d-H:i:s').'","'.STATION.'","'._csv($action).'","'._csv($where)."\"\n");

		//fermeture du fichier
	fclose($fichier_log);
}

	// si on demande un delock
if(
	!empty($_GET['action'])
	and $_GET['action'] == 'unlock'
	and !empty($_GET['value'])
	)
{
		// on log les actions sur la borne
	log_file('unlock', $_GET['value']);

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
		// on log les actions sur la borne
	log_file('lock', $_GET['value']);

		// mode GPIO output
	exec('gpio mode '.$_GET['value'].' out');
		// on demande au GPIO d'ouvrir l'emplacement
	exec('gpio write '.$_GET['value']. ' 0');
		// retour sur l'API avec un message de validation
	header('Location: '.WEB_APP.'/index.php?action=lock_success');
}
else // en cas d'erreur, on repart sur l'API
	header('Location: '.WEB_APP.'/index.php');
