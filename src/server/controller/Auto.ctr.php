<?php
/**
 * gestion de l'auto input et du lock/unlock
 */

	/**
	 * class controller Auto
	 *
	 * controleur pour la detection de la carte
	 */
class Auto
{
		/** @var int l'id de l'utilisateur */
	private $id;

		/** @var odbLocation model de gestion Bdd */
	private $odbLocation;

		/**
		 * contrusteur de la class auto
		 */
	public function __construct()
	{


			// si il est connecte
			// on instancie les model (lien avec la BDD)
		$this->odbLocation = new OdbLocation();

		if (empty($_GET['action']))
			$_GET['action'] = null;

			/**
			 * Switch de gestion des actions de Intervention
			 *
			 * @param string $_GET['action'] contient l'action demandee
			 */
		switch ($_GET['action']) {
			case 'base':

			default:
				$this->action();
				break;
		}

	}

		/**
		 * methode magic a la serialization de l'object
		 * @return array variable a concerver
		 */
	public function __sleep()
	{
		return array();
	}
		/**
		 * methode magic a appeller a la deserialization de l'object
		 */
	public function __wakeup()
	{
		// $this->odbUser = new OdbUser();
	}

	////////////////////////////////
	// Methodes public du compte  //
	////////////////////////////////

		/**
		 * affiche le formulaire de login
		 * @return void affiche les vues
		 */
	public function displayForm($data=null)
	{
		view('htmlHeader');

		if(!empty($_SESSION['tampon']['error']))
			view('contentError');

		if(!empty($_SESSION['tampon']['success']))
			view('contentSuccess');

		if(!empty($data['lesSocketFree']) and isset($data['id_user']))
		{
			view('contentSocket', array(
				'lesSocketFree'=>$data['lesSocketFree'],
				'id_user'=>$data['id_user']));
		}
		else
			view('contentForm');

		view('htmlFooter');
	}


	//////////////////////
	// Methodes privee //
	//////////////////////


		/**
		 * affiche toutes les demandes d'interventions non traitees
		 * @return void
		 */
	protected function action()
	{
		$data =array();

		if(isset($_POST['id_user']) and $_POST['id_user']!=='')
		{
			if($this->odbLocation->haveALockedNow($_POST['id_user']))
			{
				$socket = $this->odbLocation->getSocket($_POST['id_user']);
				if($this->odbLocation->unlock($_POST['id_user']))
				{
					$_SESSION['tampon']['success'][] = 'Emplacement n°'.$socket. ' déverrouillé !';

					header('Location: '.LOCAL_APP.'/index.php?action=unlock&value='.$socket);
					die('unlock&value='.$socket);
				}
				else
					$_SESSION['tampon']['error'][] = 'Erreur lors du déverrouillage';
			}
			elseif(isset($_POST['socket']) and $_POST['socket'] !=='')
			{
				if($this->odbLocation->isFreeSocket($_POST['socket']))
				{
						// on save en bdd
					if($this->odbLocation->lock($_POST['id_user'], $_POST['socket']))
					{
						$_SESSION['tampon']['success'][] = 'Emplacement n°'.$_POST['socket']. ' verrouillé !';
							// on demande a la station de faire le lock
						header('Location: '.LOCAL_APP.'/index.php?action=lock&value='.$_POST['socket']);
						die('lock&value='.$_POST['socket']);
					}
					else
						$_SESSION['tampon']['error'][] = 'Erreur lors du verrouillage';

				}
				else
					$_SESSION['tampon']['error'][] = 'Emplacement n°'.$_POST['socket']. ' déjà verrouillé !';
			}
			else
			{
				$data['lesSocketFree'] = $this->odbLocation->getFreeSocket();
				$data['id_user'] = $_POST['id_user'];

				if(empty($data['lesSocketFree']))
					$_SESSION['tampon']['error'][] = 'Il n\'y a plus d\'emplacement libre !';
			}
		}

		$this->displayForm($data);
	}

}
