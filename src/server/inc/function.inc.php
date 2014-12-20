<?php

	/**
	 * charge les vues
	 * @param  string $name nom de la vue a charger
	 * @param  array $arg  les arguments à passer à  la vue
	 * @return void       juste un include
	 */
function view($name = null, array $arg = null)
{
	if(empty($name))
		return;
	// inclusion du template
	include 'view/'.$name.'.tpl.php';
}

	/**
	 * place une erreur dans le tableau superGlobal d'erreur et retourne false
	 * @param  string  $errorString le message d'erreur à charger
	 * @param  string $errorName   facult. si on veux un index particulier
	 * @return bool               toujours false
	 */
function throwError($errorString='', $errorName = false)
{
	if ($errorName === false)
		$_SESSION['tampon']['error'][] = $errorString;
	else
		$_SESSION['tampon']['error'][$errorName] = $errorString;

	return false;
}
