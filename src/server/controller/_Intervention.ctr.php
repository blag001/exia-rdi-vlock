<?php
/**
 * fichier de declaration de la class controller d'interventions/demande
 */

	/**
	 * class controller Intervention
	 *
	 * controleur pour les Interventions et demandes d'interventions
	 * Charger d'appeller les methodes suivant les demandes
	 */
class Intervention
{
		/** @var OdbDemandeInter model de gestion Bdd */
	private $odbDemandeInter;
		/** @var OdbBonIntervention model de gestion Bdd */
	private $odbBonIntervention;

		/** @var OdbVelo model de gestion Velo en Bdd */
	private $odbVelo;

		/**
		 * contructeur du controller Intervention
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
		$this->odbDemandeInter = new OdbDemandeInter();
		$this->odbBonIntervention = new OdbBonIntervention();
		$this->odbVelo = new OdbVelo();

			// page actuelle
		$_SESSION['tampon']['menu'][0]['current'] = 'Intervention';
			// liste des sous menus
		$_SESSION['tampon']['menu'][1]['list'] =
			array(
					'Demandes Non trait&eacute;es' => 'index.php?page=intervention&amp;action=interventions_nt',
					'Intervenir'                   => 'index.php?page=intervention&amp;action=creerbonintervention' ,
					'Mes interventions'            => 'index.php?page=intervention&amp;action=mesinterventions' ,
					'Rechercher intervention'      => 'index.php?page=intervention&amp;action=rechercherbonintervention' ,
					'Une intervention'             => 'index.php?page=intervention&amp;action=monbonintervention',
					'Demander'                     => 'index.php?page=intervention&amp;action=creerdemandeinter' ,
					'Une demande'                  => 'index.php?page=intervention&amp;action=unedemandeinter' ,
				);

			// si on est superviseur, on ajoute "les Interv"
		if($_SESSION['user']->estSuperviseur())
			$_SESSION['tampon']['menu'][1]['list']['Les interventions'] = 'index.php?page=intervention&amp;action=lesinterventions';

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

			case 'lesinterventions':
				$this->afficherLesInter();
				break;

			case 'monbonintervention':
				$this->afficherMonBonInter();
				break;

			case 'rechercherbonintervention':
				$this->rechercherUnBonInter();
				break;

			case 'ajaxrechercherbonintervention':
				$this->ajaxRechercherUnBonInter();
				break;

			case 'creerbonintervention':
				$this->creerUnBonIntervention();
				break;

			case 'creerdemandeinter':
				$this->creerUneDemandeInter();
				break;

			// case 'rechercherdemandeinter':
			// 	$this->rechercherUneDemandeInter();
			// 	break;

			case 'interventions_nt':

			default:
				$this->afficherLesDemandesInter();
				break;
		}
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

		/**
		 * affiche une demande d'interventions
		 *
		 * elle doit etre :
		 * soit non traitee
		 * soit etre a moi
		 * soit je doit etre responcable achat
		 *
		 * @return void
		 */
	protected function afficherUneDemandeInter()
	{
			// si la demande existe
		if (
				!empty($_GET['valeur'])
				and $this->odbDemandeInter->estDemandeInter($_GET['valeur']))
		{
				// si on est technicien on recup le matricule
			if($_SESSION['user']->estTechnicien())
				$techCode = $_SESSION['user']->getMatricule();
			else
				$techCode = -1; // -1 = responcable achat = passe-partout

			$uneDemandeInter = $this->odbDemandeInter->getUneDemandeInter($_GET['valeur'], $techCode);

			$_SESSION['tampon']['html']['title'] = 'Demande Intervention - '.$uneDemandeInter->DemI_Num;
			$_SESSION['tampon']['menu'][1]['current'] = 'Une demande';

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentOneDemI', array('uneDemandeInter'=>$uneDemandeInter));
			view('htmlFooter');
		}
		else
		{
			$_SESSION['tampon']['html']['title'] = 'Demande Intervention - ERREUR';
			$_SESSION['tampon']['menu'][1]['current'] = 'Une demande';

			throwError('La Demande d\'Intervention ne semble pas exister...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentError');
			view('htmlFooter');
		}
	}

		/**
		 * affiche ses interv quand on est technicien
		 * @return void
		 */
	protected function afficherMesInter()
	{
			// si le compte est bien celui d'un tech
		if ($_SESSION['user']->estTechnicien())
		{
			$mesInterventions = $this->odbBonIntervention->getMesInterventions($_SESSION['user']->getMatricule());

			$_SESSION['tampon']['html']['title'] = 'Toutes mes interventions';
			$_SESSION['tampon']['menu'][1]['current'] = 'Mes interventions';

			if (empty($mesInterventions))
				throwError('Pas d\'Intervention...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentMesInterventions', array('mesInterventions'=>$mesInterventions));
			view('htmlFooter');
		}
		else
		{
			$_SESSION['tampon']['html']['title'] = 'Toutes mes interventions - ERREUR';
			$_SESSION['tampon']['menu'][1]['current'] = 'Mes interventions';

			throwError('Vous ne semblez pas &ecirc;tre Technicien...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentError');
			view('htmlFooter');
		}

	}

		/**
		 * affiche les interv quand on est technicien-superviseur
		 * @return void
		 */
	protected function afficherLesInter()
	{
			// si le compte est bien celui d'un tech
		if ($_SESSION['user']->estSuperviseur())
		{
			$lesInterventions = $this->odbBonIntervention->getLesBonsInter();

			$_SESSION['tampon']['html']['title'] = 'Toutes les interventions';
			$_SESSION['tampon']['menu'][1]['current'] = 'Les interventions';

			if (empty($lesInterventions))
				throwError('Pas d\'Intervention...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentAllInterventions', array('lesInterventions'=>$lesInterventions));
			view('htmlFooter');
		}
		else
		{
			$_SESSION['tampon']['html']['title'] = 'Toutes les interventions - ERREUR';
			$_SESSION['tampon']['menu'][1]['current'] = 'Les interventions';

			throwError('Vous ne semblez pas &ecirc;tre Superviseur...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentError');
			view('htmlFooter');
		}

	}

		/**
		 * affiche un bon d'interv quand on est technicien
		 * @return void
		 */
	protected function afficherMonBonInter()
	{
			// si le bon existe
		if (
				!empty($_GET['valeur'])
				and $_SESSION['user']->estTechnicien()
				and $this->odbBonIntervention->estMonBonInter($_GET['valeur'], $_SESSION['user']->getMatricule())
			)
		{
			$unBonInter = $this->odbBonIntervention->getMonBonInter($_GET['valeur'], $_SESSION['user']->getMatricule());

			$_SESSION['tampon']['html']['title'] = 'Bon Intervention - '.$unBonInter->BI_Num;
			$_SESSION['tampon']['menu'][1]['current'] = 'Une intervention';

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			if (!empty($_SESSION['tampon']['success']))
				view('contentSuccess');
			view('contentOneBonInter', array('unBonInter'=>$unBonInter));
			view('htmlFooter');
		}
		elseif(!empty($_GET['valeur']))
		{
			$_SESSION['tampon']['html']['title'] = 'Bon Intervention - ERREUR';
			$_SESSION['tampon']['menu'][1]['current'] = 'Une intervention';

			throwError('Le bon d\'Intervention ne semble pas exister...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentError');
			view('htmlFooter');
		}
		else
			$this->rechercherUnBonInter();

	}

		/**
		 * permet une recherche dans ses bons d'interventions
		 * @return void
		 */
	protected function rechercherUnBonInter()
	{
		if ($_SESSION['user']->estTechnicien())
		{
				// si une valeur, on lance la recherche
			if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
				$lesBonsInter = $this->odbBonIntervention->searchMesBonIntervention($_GET['valeur'], $_SESSION['user']->getMatricule());
			else // par def on charge tout mes bons
				$lesBonsInter = $this->odbBonIntervention->getMesInterventions($_SESSION['user']->getMatricule());

			$_SESSION['tampon']['html']['title'] = 'Rechercher un bon d\'intervention';
			$_SESSION['tampon']['menu'][1]['current'] = 'Rechercher intervention';

				// rien en retour ? une erreur
			if (empty($lesBonsInter))
				throwError('Pas de bon...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentSearchBonIntervention');
			view('contentMesInterventions', array('mesInterventions'=>$lesBonsInter));
			view('htmlFooter');
		}
		else
		{
			$_SESSION['tampon']['html']['title'] = 'Rechercher un bon d\'intervention - ERREUR';
			$_SESSION['tampon']['menu'][1]['current'] = 'Mes interventions';

			throwError('Vous ne semblez pas &ecirc;tre Technicien...');

				/**
				 * Load des vues
				 */
			view('htmlHeader');
			view('contentMenu');
			view('contentError');
			view('htmlFooter');
		}
	}

		/**
		 * permet une recherche ajax dans ses bons d'interventions
		 * @return void
		 */
	protected function ajaxRechercherUnBonInter()
	{
		if ($_SESSION['user']->estTechnicien())
		{
				// si une valeur, on lance la recherche
			if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
				$lesBonsInter = $this->odbBonIntervention->searchMesBonIntervention($_GET['valeur'], $_SESSION['user']->getMatricule());
			else // par def on charge tout mes bons
				$lesBonsInter = $this->odbBonIntervention->getMesInterventions($_SESSION['user']->getMatricule());

				// rien en retour ? une erreur
			if (empty($lesBonsInter))
				throwError('Pas de bon...');

				/**
				 * Load des vues
				 */
			view('contentMesInterventions', array('mesInterventions'=>$lesBonsInter, 'isAjax'=>true));
		}
		else
		{
			throwError('Vous ne semblez pas &ecirc;tre Technicien...');

				/**
				 * Load des vues
				 */
			view('contentError');
		}
	}

		/**
		 * gere la sauvegarde des bon d'intervention
		 * @return void affiche les vues
		 */
	protected function creerUnBonIntervention()
	{
		if (isset($_POST['sbmtMkBon']))
			$this->_saveSubmitBonI();

			/**
			 * On regarde si on a deja des valeurs pour pre-remplir
			 *
			 * soit un code de demande d'intervention
			 * soit un code de velo
			 * sinon on met des null
			 */
		if(
			isset($_GET['code_demande'])
			and $this->odbDemandeInter->estDemandeInter($_GET['code_demande'])
			)
		{
			$leVeloNum = $this->odbDemandeInter->getIdVeloByIdDemandeInter($_GET['code_demande']);
			$laDemandeInterNum = $_GET['code_demande'];
		}
		elseif(
			isset($_GET['code_velo'])
			and $this->odbVelo->estVelo($_GET['code_velo'])
			)
		{
				$leVeloNum = $_GET['code_velo'];
				$laDemandeInterNum = null;
			}
		else{
				$leVeloNum = null;
				$laDemandeInterNum = null;
		}


		//on recupere tous les codes velos pour la liste deroulante
		$lesVelos = $this->odbVelo->getLesVelos();


		$_SESSION['tampon']['html']['title'] = 'Cr&eacute;er un bon d\'intervention';

		$_SESSION['tampon']['menu'][1]['current'] = 'Intervenir';

			/**
			 * Load des vues
			 */
		view('htmlHeader');
		view('contentMenu');
		if(!empty($_SESSION['tampon']['error']))
			view('contentError');
		if(!empty($_SESSION['tampon']['success']))
			view('contentSuccess');
		view('contentCreerUnBon', array(
			'leVeloNum'=>$leVeloNum,
			'laDemandeInterNum'=>$laDemandeInterNum,
			'lesVelos'=>$lesVelos,
			));
		view('htmlFooter');

	}

		/**
		 * methode privee charge de formater les data et de sauver l'intervention
		 * @return void redirection avec message d'error/success charge en _SESSION
		 */
	private function _saveSubmitBonI()
	{
			// si on a un envois valide, on lance la sauvegarde
		if( $this->_checkSubmitBonI())
		{
			if (empty($_POST['code_demande']))
				$_POST['code_demande'] = null;

				// on traite les dates
			$dateDebut = date_create($_POST['dateDebut']);
			if(!empty($_POST['surPlace']) or !empty($_POST['code_demande'])){

				$dateFin = date_create($_POST['dateFin']);
				$dureeAbs = $dateDebut->diff($dateFin, true);
				$_POST['duree'] = $dureeAbs->format('%a') + 1;
				$_POST['dateFin'] = $dateFin->format('Y-m-d');
			}

			$_POST['dateDebut'] = $dateDebut->format('Y-m-d');
				// on regarde si reparable ou non
			$_POST['veloReparable'] = !empty($_POST['veloNonReparable'])? 0 : 1;
			$_POST['surPlace'] = !empty($_POST['surPlace'])? 1 : 0;

				/** si on a un nombre de ligne >0 et donc TRUE */
			$unNouveauBon = $this->odbBonIntervention->creerUnBonInter(
				$_POST['vel_num'],
				$_POST['dateDebut'],
				$_POST['dateFin'],
				$_POST['cpteRendu'],
				$_POST['veloReparable'],
				$_POST['code_demande'],
				$_SESSION['user']->getMatricule(),
				$_POST['surPlace'],
				$_POST['duree']
				);

			if ($unNouveauBon)
			{
				$idIntervention = $this->odbBonIntervention->getIdLastIntervention();
				$_SESSION['tampon']['success'][] =
					'Ajout de l\'intervention n°'.$idIntervention.' r&eacute;ussie !';
					// on redirige vers la page d'affiche des intervention
				header('Location:index.php?page=intervention&action=monbonintervention&valeur='.$idIntervention);
				die; // on stop le chargement de la page
			}
			else // sinon on charge une erreur
				throwError('Erreur avec l\'ajout de l\'intervention sur le v&eacute; n°'.$_POST['vel_num']);
		}

	}

		/**
		 * methode privee chargee de verifier les donnees
		 * @return bool si erreur (FALSE) ou non
		 */
	private function _checkSubmitBonI()
	{
			// var pour la validatation des envois
		$noError = true;
			// on traite les dates en Fr pour les avoir un YYYY-MM-DD
		if (substr_count($_POST['dateDebut'], '/'))
			$_POST['dateDebut'] = preg_replace('#([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})#', '$3-$2-$1', $_POST['dateDebut']);
		if (substr_count($_POST['dateFin'], '/'))
			$_POST['dateFin'] = preg_replace('#([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})#', '$3-$2-$1', $_POST['dateFin']);

			// si on a une demande
		if(!empty($_POST['code_demande']))
		{
				// si la demande n'existe pas
			if(!$this->odbDemandeInter->estDemandeInter($_POST['code_demande']))
				$noError = throwError('La demande ne semble pas exister...', 'code_demande');
				// sinon si elle n'a pas de velo liee
			elseif ( !($code_velo = $this->odbDemandeInter->getIdVeloByIdDemandeInter($_POST['code_demande'])) )
				$noError = throwError('Pas de velo li&eacute; &agrave; cette demande...', 'code_demande');
				// sinon si le velo liee est introuvable
			elseif ( !$this->odbVelo->estVelo($code_velo) )
				$noError = throwError('Le velo ne semble pas exister...', 'code_demande');
				// sinon si on a bidouiller pour changer l'idVelo dans le html
			elseif ( $code_velo != $_POST['vel_num'] )
				$noError = throwError('On ne hack pas l\'application SVP...', 'vel_num');
		}
			// sinon si on a pas de velo
		elseif (empty($_POST['vel_num']))
			$noError = throwError('Merci de selectionner un v&eacute;lo...', 'vel_num');
			// sinon si le velo est introuvable
		elseif( !$this->odbVelo->estVelo($_POST['vel_num']) )
			$noError = throwError('Le velo ne semble pas exister...', 'vel_num');

			// si pas de date debut
		if (empty($_POST['dateDebut']))
			$noError = throwError('Merci de remplir une date de d&eacute;but...', 'dateDebut');

			// si pas de date de fin
		if (empty($_POST['dateFin']))
			$noError = throwError('Merci de remplir une date de fin...', 'dateFin');

			// si on peut pas exploiter la date debut
		if(!date_create($_POST['dateDebut']))
			$noError = throwError('Erreur avec la date, merci d\'utiliser le format JJ/AAAA/MM', 'dateDebut');
		else
		{
			$today = date_create();
			$dateDebut = date_create($_POST['dateDebut']);
			$diff = $today->diff($dateDebut);
			if(intval($diff->format('%R%a')) < -7) // on autorise -7 jours d'antidatage
				$noError = throwError('La date ne doit pas être plus vielle qu\'une semaine !', 'dateDebut');
			if(intval($diff->format('%R%a')) > 2) // on autorise +2 jours d'antidatage
				$noError = throwError('La date ne doit pas être dans le future !', 'dateDebut');
		}

			// si on peut pas exploiter la date de fin
		if(!date_create($_POST['dateFin']))
			$noError = throwError('Erreur avec la date, merci d\'utiliser le format JJ/MM/AAAA', 'dateFin');
		else
		{
			$dateDebut = date_create($_POST['dateDebut']);
			$dateFin = date_create($_POST['dateFin']);
			$diff = $dateDebut->diff($dateFin);
			if(intval($diff->format('%R%a')) < 0) // pas de fin avant debut
				$noError = throwError('La date de fin ne peut être avant celle de début !', 'dateFin');
			// if(intval($diff->format('%R%a')) > 30) // on autorise +2 jours d'antidatage
			// 	$noError = throwError('La date ne doit pas être dans le future !', 'dateFin');
			// 	non pertient
		}
			// si pas de compte rendu
		if (empty($_POST['cpteRendu']))
			$noError = throwError('Le compte-rendu doit être remplis !', 'cpteRendu');

		return $noError;
	}

		/**
		 * permet de creer une demande d'intervention
		 * @return void afficge des vues
		 */
	protected function creerUneDemandeInter()
	{

		if (isset($_POST['sbmtMkDemande']))
			$this->_saveSubmitDemI();

			/**
			 * On regarde si on a deja une valeur pour pre-remplir
			 *
			 * soit un code de velo
			 * sinon on met des null
			 */
		if(
			isset($_GET['code_velo'])
			and $this->odbVelo->estVelo($_GET['code_velo'])
			)
		{
				$leVeloNum = $_GET['code_velo'];
			}
		else{
				$leVeloNum = null;
		}


		//on recupere tous les codes velos pour la liste deroulante
		$lesVelos = $this->odbVelo->getLesVelos();


		$_SESSION['tampon']['html']['title'] = 'Cr&eacute;er une demande d\'intervention';

		$_SESSION['tampon']['menu'][1]['current'] = 'Demander';

			/**
			 * Load des vues
			 */
		view('htmlHeader');
		view('contentMenu');
		if(!empty($_SESSION['tampon']['error']))
			view('contentError');
		if(!empty($_SESSION['tampon']['success']))
			view('contentSuccess');
		view('contentCreerUneDemande', array(
			'leVeloNum'=>$leVeloNum,
			'lesVelos'=>$lesVelos,
			));
		view('htmlFooter');
	}

		/**
		 * methode privee charge de formater les data et de sauver la demande
		 * @return void redirection avec message d'error/success charge en _SESSION
		 */
	private function _saveSubmitDemI()
	{
			// si on a un envois valide, on lance la sauvegarde
		if( $this->_checkSubmitDemI())
		{
				// on traite la date
			$dateDemande = date_create();

			$_POST['dateDemande'] = $dateDemande->format('Y-m-d');

			$uneNewDemande = $this->odbDemandeInter->creerUneDemande(
				$_POST['vel_num'],
				$_POST['dateDemande'],
				$_SESSION['user']->getMatricule(),
				$_POST['cpteRendu']
				);

				/** si on a un nombre de ligne >0 et donc TRUE */
			if ($uneNewDemande)
			{
				$idDemande = $this->odbDemandeInter->getIdLastDemandeInter();
				$_SESSION['tampon']['success'][] =
					'Ajout de la demande n°'.$idDemande.' r&eacute;ussie !';
					// on redirige vers la page d'affichage des demandes
				header('Location:index.php?page=intervention&action=unedemandeinter&valeur='.$idDemande);
				die; // on stop le chargement de la page
			}
			else // sinon on charge une erreur
				throwError('Erreur avec l\'ajout de la demande sur le v&eacute; n°'.$_POST['vel_num']);

		}

	}

		/**
		 * methode privee chargee de verifier les donnees
		 * @return bool si erreur (FALSE) ou non
		 */
	private function _checkSubmitDemI()
	{
			// var pour la validatation des envois
		$noError = true;

			// si on a pas de velo
		if (empty($_POST['vel_num']))
			$noError = throwError('Merci de selectionner un v&eacute;lo...', 'vel_num');
			// sinon si le velo est introuvable
		elseif( !$this->odbVelo->estVelo($_POST['vel_num']) )
			$noError = throwError('Le velo ne semble pas exister...', 'vel_num');

			// si pas de compte rendu
		if (empty($_POST['cpteRendu']))
			$noError = throwError('Le compte-rendu doit être remplis !', 'cpteRendu');

		return $noError;
	}

}
