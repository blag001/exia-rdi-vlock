<?php
/**
 * fichier de declaration du model de Demande d'interventions
 */

	/**
	 * class de gestion BDD des demandes d'intervention
	 */
class OdbDemandeInter
{
		/** @var object objet Bdd */
	private $oBdd;

		/**
		 * contruteur du model
		 */
	public function __construct()
	{
		$this->oBdd = $_SESSION['bdd'];
	}

		/**
		 * test si un id correspond bien a une demande
		 * @param  int $code id de la demande à tester
		 * @return bool       un vrai/faux de l'existance
		 */
	public function estDemandeInter($code=null)
	{
		if(!empty($code))
		{
			$req = 'SELECT COUNT(*) AS nb
					FROM demandeinter
					WHERE DemI_Num = :code';

			$data = $this->oBdd->query($req , array('code'=>$code), Bdd::SINGLE_RES);

			return (bool) $data->nb;
		}

		return false;
	}

		/**
		 * retournes les demandes d'intervention
		 * @return array les demandes
		 */
	public function getLesDemandesInter()
	{
		$req = "SELECT *, DATE_FORMAT(DemI_Date, '%d/%m/%Y') AS DemI_Date
				FROM demandeinter";

		$lesDemandesInter = $this->oBdd->query($req);

		return $lesDemandesInter;
	}

		/**
		 * retourne une demande suivant un id
		 * @param  int  $id       l'id de la demande
		 * @param  integer $techCode le matricule du technicien ou -1
		 * @return object            une demande
		 */
	public function getUneDemandeInter($id=null, $techCode = -2)
	{
		$req = "SELECT
					demandeinter.*,
					velo.*,
					station.*,
					technicien.*,
					DATE_FORMAT(DemI_Date, '%d/%m/%Y') AS DemI_Date
				FROM demandeinter
				INNER JOIN velo
					ON demandeinter.DemI_Velo = velo.Vel_Num
				INNER JOIN station
					ON velo.Vel_Station = station.Sta_Code
				INNER JOIN technicien
					ON demandeinter.DemI_Technicien = technicien.Tec_Matricule
				LEFT OUTER JOIN boninterv
					ON demandeinter.DemI_Num = boninterv.BI_Demande
				WHERE DemI_Num = :id
					AND  (
						DemI_Traite = 0
						OR DemI_Technicien = :techCode
						OR BI_Technicien = :techCode
						OR -1 = :techCode
						)";

		$laDemandeInter = $this->oBdd->query($req,
			array(
				'id'=>$id,
				'techCode'=>$techCode,
				),
			Bdd::SINGLE_RES);

		return $laDemandeInter;
	}

		/**
		 * retourne un id de velo lié à une demande
		 * @param  int $id id de la demande
		 * @return int     id du velo
		 */
	public function getIdVeloByIdDemandeInter($id=null)
	{
		$req = "SELECT DemI_Velo AS Vel_Num
				FROM demandeinter
				WHERE DemI_Num = :id";

		$laDemandeInter = $this->oBdd->query($req, array('id'=>$id), Bdd::SINGLE_RES);

		return $laDemandeInter->Vel_Num;
	}

		/**
		 * toutes les demandes d'intervention non traitees
		 * @return array tableau des demandes
		 */
	public function getLesDemandesNT()
	{
		$req = "SELECT *, DATE_FORMAT(DemI_Date, '%d/%m/%Y') AS DemI_Date
				FROM demandeinter
				INNER JOIN velo
					ON demandeinter.DemI_Velo = velo.Vel_Num
				INNER JOIN station
					ON velo.Vel_Station = station.Sta_Code
				WHERE DemI_Traite = 0";

		$lesDemandesInter = $this->oBdd->query($req);

		return $lesDemandesInter;
	}

		/**
		 * cree une demande d'inter avec les param
		 * @param  int $Vel_Num       l'id du velo
		 * @param  string $dateDebut     la date de debut
		 * @param  int $techMatricule le matricule du technicien
		 * @param  string $cpteRendu     le compte rendu
		 * @return int                nombre de ligne inseree
		 */
	public function creerUneDemande($Vel_Num=null, $dateDebut=null, $techMatricule=null, $cpteRendu=null)
	{
		$req = 'INSERT INTO demandeinter (
					 `DemI_Velo`,
					 `DemI_Date`,
					 `DemI_Technicien`,
					 `DemI_Motif`,
					 `DemI_Traite`
					)
				VALUES (
					 :Vel_Num,
					 :dateDemande,
					 :technicienCode,
					 :cpteRendu,
					 0
				 	)';

		$out = $this->oBdd->exec($req, array(
				 'Vel_Num'=>$Vel_Num,
				 'dateDemande'=>$dateDebut,
				 'technicienCode'=>$techMatricule,
				 'cpteRendu'=>$cpteRendu,
				));
		return $out;
	}

		/**
		 * on va chercher l'id de la derniere demande d'intervention
		 * @return int id de la derniere demande
		 */
	public function getIdLastDemandeInter()
	{
		$req = "SELECT DemI_Num
				FROM demandeinter
				ORDER BY DemI_Num DESC
				LIMIT 1";

		$lesBonsInter = $this->oBdd->query($req, null, Bdd::SINGLE_RES);

		return $lesBonsInter->DemI_Num;
	}

		/**
		 * cherche les demande d'intervention suivant valeur
		 * @param  string  $valeur   la valeur à chercher dans idDemI/demIVel/demIStat
		 * @param  integer $techCode l'id du technicien qui recherche ou -1
		 * @return array            les demande d'inter qui match
		 */
	public function searchLesDemandesInter($valeur=null, $techCode = -2)
	{
		$req = "SELECT *, DATE_FORMAT(`DemI_Date`, '%d/%m/%Y') AS `DemI_Date`
				FROM demandeinter
				INNER JOIN velo
					ON demandeinter.DemI_Velo = velo.Vel_Num
				INNER JOIN station
					ON velo.Vel_Station = station.Sta_Code
				INNER JOIN technicien
					ON demandeinter.DemI_Technicien = technicien.Tec_Matricule
				WHERE (
						`DemI_Num` LIKE :valeur
						OR DemI_Velo LIKE :valeur
						OR Vel_Station LIKE :valeur
						)
					AND  (
						`DemI_Traite` = 0
						OR `DemI_Technicien` = :techCode
						OR -1 = :techCode
						)"
					;

		$lesDemandesInter = $this->oBdd->query($req,
			array(
				'valeur'=>'%'.$valeur.'%',
				 'techCode'=>$techCode,
				 ));

		return $lesDemandesInter;
	}

}
