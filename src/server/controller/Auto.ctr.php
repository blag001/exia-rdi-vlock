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
		// $this->odbDemandeInter = new OdbDemandeInter();

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
				$this->displayForm();
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
	public function displayForm()
	{
		view('htmlHeader');
		if(!empty($_SESSION['tampon']['error']))
			view('contentError');
		if(!empty($_SESSION['tampon']['success']))
			view('contentSuccess');
		view('contentForm');
		view('htmlFooter');
	}


	//////////////////////
	// Methodes privee //
	//////////////////////

		/**
		 * check si le n° correspond a un verouillage actuel
		 * @return bool true si on peut le connecter
		 */
	private function _haveALockedNow($key=null)
	{

		if(!empty($key) and false)
			return true;

		return false;
	}

		/**
		 * affiche toutes les demandes d'interventions non traitees
		 * @return void
		 */
	protected function action()
	{
		if(isset($_POST['id_user']) and $_POST['id_user']!=='')
		{
			if($this->_haveALockedNow($_POST['id_user']))
			{
				$socket = $this->odbLocation->getSoket($_POST['id_user']);
				$_SESSION['tampon']['success'] = 'Emplacement n°'.$socket. ' déverrouillé !';

				header('Location: '.LOCAL_APP.'/index.php?action=unlock&value='.$socket);
				die();
			}
			elseif(isset($_POST['socket']) and $_POST['socket'] !=='')
			{
				if($this->odbLocation->isFreeSocket($_POST['socket']))
				{
					$_SESSION['tampon']['success'] = 'Emplacement n°'.$socket. ' verrouillé !';

					header('Location: '.LOCAL_APP.'/index.php?action=lock&value='.$socket);
					die();
				}
				else
					$_SESSION['tampon']['error'] = 'Emplacement n°'.$_POST['socket']. 'déjà verrouillé !';
			}
		}
	}

}
