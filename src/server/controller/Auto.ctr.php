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

		/** @var odbUser model de gestion Bdd */
	private $odbUser;

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
			case 'unedemandeinter':
				$this->afficherUneDemandeInter();
				break;

			case 'mesinterventions':
				$this->afficherMesInter();
				break;

			case 'creerdemandeinter':
				$this->creerUneDemandeInter();
				break;

			case 'interventions_nt':

			default:
				$this->afficherLesDemandesInter();
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
		 * check si le n° correspond a un verouillage actuel
		 * @return bool true si on peut le connecter
		 */
	public function aUnLockedNow($key=null)
	{

		if(!empty($key) and )
			return true;

		return false;
	}

		/**
		 * affiche le formulaire de login
		 * @return void affiche les vues
		 */
	public function displayForm()
	{
		view('htmlHeader');
		if(!empty($_SESSION['tampon']['error']))
			view('contentError');
		view('contentLogin');
		view('htmlFooter');
	}

		/**
		 * check si est technicien (et donc pas resp achat)
		 * @return boot est ou non technicien
		 */
	public function estTechnicien()
	{
		if(!empty($this->matricule))
			if(!($this->respAchat))
				return true;

		return false;
	}

		/**
		 * va chercher le matricule
		 * @return int matricule de l'utilisateur
		 */
	public function getMatricule()
	{
		if(!empty($this->matricule))
				return $this->matricule;

		return false;
	}

	//////////////////////
	// Methodes privee //
	//////////////////////

		/**
		 * va verifier si le mdp/is passe est bien prensent en bdd
		 *
		 * hash est une metode pour obtenir une emprinte unique
		 * ca nous evite de garder en clair les mdp, comme ca en
		 * cas de piratage, les mdp ne sont pas retrouvable simplement
		 *
		 * @return bool vrai si compte existe avec ce mdp/id, false sinon
		 */
	private function login()
	{
			// si on envois un name et un mdp, alors on va faire les verif en bdd
		if(!empty($_POST['name']) and isset($_POST['mdp']))
		{
			if($this->odbUser->checkHashUser($_POST['name'],
				hash('sha512',
					$_POST['name'].$_POST['mdp'].$_POST['name'])))
			{
				$user = $this->odbUser->getUser($_POST['name']);

				$this->id = $user->Use_Num;
				$this->matricule = $user->Use_Technicien;
				$this->name = $user->Use_Nom;
				$this->respAchat = $user->Use_RespAchat;
				$this->role = $user->Tec_Role;

				if(!empty($_POST['remember_me']))
				{
						/** @var string un jeton qui servira de mot de passe (seed)*/
					$seed = bin2hex(openssl_random_pseudo_bytes(256));
						/** @var string le token cree avec la seed et le pseudo */
					$token = hash('sha512', $_POST['name'].$seed.$_POST['name']);

					if($this->odbUser->saveToken($_POST['name'], $token))
					{
							// un cookie qui contient le name pour 3 mois
						setcookie('name', $_POST['name'], time()+7776000, null, null, false, true);
							// un cookie qui contient la seed pour 3 mois
						setcookie('remember_me', $seed, time()+7776000, null, null, false, true);
					}
				}
				header('Location:'.$_SERVER['PHP_SELF']);
				return true;
			}
			elseif ($this->odbUser->estUser($_POST['name']))
				$_SESSION['tampon']['error'][] = 'Erreur sur le mot de passe.';
			else
				$_SESSION['tampon']['error'][] = 'Erreur sur l\'identifiant.';
		}
			/** Si on a un cookie pour se souvenir de l'utilisateur */
		elseif(!empty($_COOKIE['remember_me']) and isset($_COOKIE['name']))
		{
			$hash = hash('sha512', $_COOKIE['name'].$_COOKIE['remember_me'].$_COOKIE['name']);

			if($trueHash = $this->odbUser->getToken($_COOKIE['name']))
				if(strcmp($hash, $trueHash) === 0)
					return true;
		}

		return false;
	}

		/**
		 * permet au user de se deconnecter
		 * @return void
		 */
	private function logout()
	{
			/** on detruit le cookie */
		if(isset($_COOKIE['remember_me']))
			setcookie('remember_me', '', time()-1);
			/** on supprime le ficher de stockage du token */
		if(isset($_COOKIE['name'])){
			$this->odbUser->forgetToken($_COOKIE['name']);
			setcookie('name', '', time()-1);
		}

		$this->id = null;
		$this->matricule = null;
		$this->name = null;
		$this->respAchat = null;
		$this->role = null;
		session_destroy();
	}

		/**
		 * affiche toutes les demandes d'interventions non traitees
		 * @return void
		 */
	protected function afficherLesDemandesInter()
	{
		$lesDemandesINT = $this->odbDemandeInter->getLesDemandesNT();
		$_SESSION['tampon']['html']['title'] = 'Demandes d\'interventions non trait&eacute;es';
		$_SESSION['tampon']['menu'][1]['current'] = 'Demandes Non trait&eacute;es';

			/**
			 * Load des vues
			 */
		view('htmlHeader');
		view('contentMenu');
		view('contentAllDINT', array('lesDemandesINT'=>$lesDemandesINT));
		view('htmlFooter');
	}

}
