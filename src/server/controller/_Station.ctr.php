<?php
/**
 * fichier de declaration de la class controller des stations
 */

	/**
	 * class controller Station
	 *
	 * controleur pour les Stations
	 * Charger d'appeller les methodes suivant les demandes
	 */
class Station
{
		/** @var OdbStation model de gestion Bdd */
	private $odbStation;
		/** @var OdbVelo model de gestion Bdd */
	private $odbVelo;

		/**
		 * constructeur du controlleur de station
		 */
	public function __construct()
	{
			/**
			 * On regarde si le user est connecte,
			 * si non, on lui affiche le formulaire de coo,
			 * et on termine le script
			 */
		if (!($_SESSION['user']->estUser())) {
			$_SESSION['user']->displayForm();
			die;
		}

			// si il est connecte
			// on instancie les model (lien avec la BDD)
		$this->odbStation = new OdbStation();
		$this->odbVelo = new OdbVelo();

			// page actuelle
		$_SESSION['tampon']['menu'][0]['current'] = 'Station';
			// liste des sous menus
		$_SESSION['tampon']['menu'][1]['list'] =
			array(
					'Les stations'       => 'index.php?page=station&amp;action=lesstations',
					'Une station'        => 'index.php?page=station&amp;action=unestation',
					'Rechercher station' => 'index.php?page=station&amp;action=rechercherstation' ,
				);

		if (empty($_GET['action']))
			$_GET['action'] = null;

			/**
			 * Switch de gestion des actions de Station
			 *
			 * @param string $_GET['action'] contient l'action demmandee
			 */
		switch ($_GET['action']) {
			case 'rechercherstation':
				$this->rechercherUneStation();
				break;
			case 'ajaxrechercherstation':
				$this->ajaxRechercherUneStation();
				break;
			case 'unestation':
				$this->afficherUneStation();
				break;

			case 'lesstations':

			default:
				$this->afficherLesStations();
				break;
		}
	}

		/**
		 * affiche les stations
		 * @return void
		 */
	protected function afficherLesStations()
	{
		$lesStations = $this->odbStation->getLesStations();

		$_SESSION['tampon']['html']['title'] = 'Toutes Les Stations';
		$_SESSION['tampon']['menu'][1]['current'] = 'Les stations';

		if (empty($lesStations))
			$_SESSION['tampon']['error'][] = 'Pas de station...';

			/**
			 * Load des vues
			 */
		view('htmlHeader');
		view('contentMenu');
		view('contentAllStation', array('lesStations'=>$lesStations));
		view('htmlFooter');
	}

		/**
		 * affiche une station et ses velos lies
		 * @return void
		 */
	protected function afficherUneStation()
	{
			// si on a bien a faire a une station valide
		if (
				!empty($_GET['valeur'])
				and $this->odbStation->estStationById($_GET['valeur']))
		{
			$uneStation = $this->odbStation->getUneStation($_GET['valeur']);
			$lesVelosByStation = $this->odbVelo->getLesVelosDeStation($_GET['valeur']);

			$_SESSION['tampon']['html']['title'] = 'Station - '.$uneStation->Sta_Nom;
			$_SESSION['tampon']['menu'][1]['current'] = 'Une station';

			if (empty($lesVelosByStation))
				$_SESSION['tampon']['error'][] = 'Pas de v&eacute;lo pour cette station...';

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentOneStation', array('uneStation'=>$uneStation,
				'lesVelos'=>$lesVelosByStation));
			view('htmlFooter');
		}
		elseif(!empty($_GET['valeur']))
		{
			$_SESSION['tampon']['html']['title'] = 'Station - ERREUR';
			$_SESSION['tampon']['menu'][1]['current'] = 'Une station';

			$_SESSION['tampon']['error'][] = 'La station ne semble pas exister...';

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentError');
			view('htmlFooter');
		}
		else
			$this->rechercherUneStation();
	}

		/**
		 * recherche une station et ses velos lies
		 * @return void
		 */
	protected function rechercherUneStation()
	{
		if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
			$lesStations = $this->odbStation->searchStations($_GET['valeur']);
		else
			$lesStations = $this->odbStation->getLesStations();

		$_SESSION['tampon']['html']['title'] = 'Rechercher Une Station';
		$_SESSION['tampon']['menu'][1]['current'] = 'Rechercher station';

		if (empty($lesStations))
			$_SESSION['tampon']['error'][] = 'Pas de station...';

			/**
			 * Load des vues
			 */
		view('htmlHeader');
		view('contentMenu');
		view('contentSearchStation');
		view('contentAllStation', array('lesStations'=>$lesStations));
		view('htmlFooter');
	}

		/**
		 * recher une station et ses velos lies via AJAX
		 * @return void
		 */
	protected function ajaxRechercherUneStation()
	{
		if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
			$lesStations = $this->odbStation->searchStations($_GET['valeur']);
		else
			$lesStations = $this->odbStation->getLesStations();

		if (empty($lesStations))
			$_SESSION['tampon']['error'][] = 'Pas de station...';

			/**
			 * Load des vues
			 */
		view('contentAllStation', array('lesStations'=>$lesStations, 'isAjax'=>true));
	}
}
