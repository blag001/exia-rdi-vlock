<?php
// declaration du fichier d'interface software/hardware

	// constante globale de la station
define('STATION', 'ROUEN-02');
	// constante de l'URL de l'app
define('WEB_APP', 'http:///10.42.0.1/www/exia-rdi-vlock/src/server'); // http://api.vlock.com
	// si en test
$test = true;

function _csv($in=null){
	return str_replace('"', '""', $in);
}

function log_file($action=null, $where=null)
{
	if(!is_dir('log/'))
		mkdir('log/', 0700);

		//enregistrement de l'entree
	file_put_contents('log/log_' .date('y-m').'.csv' ,
		'"'.date('Y-m-d-H:i:s').'","'.STATION.'","'._csv($action).'","'._csv($where)."\"\n",
		FILE_APPEND);
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
	if($test)
		echo 'unlock 1';
	else
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
	if($test)
		echo 'lock 0';
	else
		header('Location: '.WEB_APP.'/index.php?action=lock_success');
}
else // en cas d'erreur, on repart sur l'API
	if($test)
		echo 'error~';
	else
		header('Location: '.WEB_APP.'/index.php');
