<?php
/**
 * fichier de declaration du model de Bon d'interventions
 */

	/**
	 * class de gestion BDD des Bons d'intervention
	 */
class OdbBonIntervention
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
		 * test si le code coresponde a un bon d'intervention
		 * @param  int $code le code a tester
		 * @return bool       true/false si est ou non un bon Inter
		 */
	public function estBonInter($code=null)
	{
		if(!empty($code))
		{
			$req = "SELECT COUNT(*) AS nb
					FROM boninterv
					WHERE BI_Num = :code";

			$data = $this->oBdd->query($req , array('code'=>$code), Bdd::SINGLE_RES);

			return (bool) $data->nb;
		}

		return false;
	}

		/**
		 * test si est le bon d'intervention de l'utilisateur
		 * @param  int $code     le code du bon
		 * @param  int $techCode le code du technicien utilisateur
		 * @return bool           si est ou non le bon de l'utilisateur
		 */
	public function estMonBonInter($code=null, $techCode=null)
	{
		if(!empty($code) and !empty($techCode))
		{
			$req = "SELECT COUNT(*) AS nb
					FROM boninterv
					WHERE BI_Num = :code
						AND BI_Technicien = :techCode";

			$data = $this->oBdd->query($req , array('code'=>$code, 'techCode'=>$techCode), Bdd::SINGLE_RES);

			return (bool) $data->nb;
		}

		return false;
	}

		/**
		 * retourne tous les bon d'interventions
		 * @return array tableau des bon d'intervention
		 */
	public function getLesBonsInter()
	{
		$req = "SELECT *,
					DATE_FORMAT(BI_DatDebut, '%d/%m/%Y') AS BI_DatDebut,
					DATE_FORMAT(BI_DatFin, '%d/%m/%Y') AS BI_DatFin
				FROM boninterv
				INNER JOIN technicien
					ON boninterv.BI_Technicien = technicien.Tec_Matricule";

		$lesBonsInter = $this->oBdd->query($req);

		return $lesBonsInter;
	}

		/**
		 * retourne le bon demande si il est a l'utilisateur
		 * @param  int $code     le code de l'interv a retourner
		 * @param  int $techCode le code du technicien
		 * @return object           le bon d'intervention
		 */
	public function getMonBonInter($code=null, $techCode=null)
	{
		$req = "SELECT *,
					DATE_FORMAT(BI_DatDebut, '%d/%m/%Y') AS BI_DatDebut,
					DATE_FORMAT(BI_DatFin, '%d/%m/%Y') AS BI_DatFin
				FROM boninterv
				WHERE BI_Num = :code
					AND BI_Technicien = :techCode";

		$leBonInter = $this->oBdd->query($req, array('code'=>$code, 'techCode'=>$techCode), Bdd::SINGLE_RES);

		return $leBonInter;
	}

		/**
		 * on visualise les interventions effectuees par un technicien grace à son matricule
		 * @param  int $techCode matricule du technincien
		 * @return array           tableau d'objets
		 */
	public function getMesInterventions($techCode=null)
	{
		$req = "SELECT *,
					DATE_FORMAT(BI_DatDebut, '%d/%m/%Y') AS BI_DatDebut,
					DATE_FORMAT(BI_DatFin, '%d/%m/%Y') AS BI_DatFin
				FROM boninterv
				INNER JOIN velo
					ON boninterv.BI_Velo = velo.Vel_Num
				WHERE BI_Technicien = :techCode";

		$lesBonsInter = $this->oBdd->query($req, array('techCode'=>$techCode));

		return $lesBonsInter;
	}

		/**
		 * on va chercher l'id de la derniere intervention
		 * @return int id de la der intervention
		 */
	public function getIdLastIntervention()
	{
		$req = "SELECT BI_Num
				FROM boninterv
				ORDER BY BI_Num DESC
				LIMIT 1";

		$lesBonsInter = $this->oBdd->query($req, null, Bdd::SINGLE_RES);

		return $lesBonsInter->BI_Num;
	}

		/**
		 * on cree une intervention
		 * @param  int $Vel_Num      id du velo
		 * @param  string $dateDebut    date de debut de l'intervention
		 * @param  string $dateFin      date de fin de l'inter
		 * @param  string $cpteRendu    le compte rendus de l'inter
		 * @param  bool $reparable    0/1 si velo reparable ou non
		 * @param  int $code_demande le code de la demande si il y en a un
		 * @param  int $matTech      le matricule du technicien
		 * @param  bool $surPlace     0/1 si realisee sur place ou non
		 * @param  int $duree        la duree de l'intervention en jour entier
		 * @return int               nombre de ligne inseree
		 */
	public function creerUnBonInter($Vel_Num=null, $dateDebut=null, $dateFin=null, $cpteRendu=null, $reparable=null, $code_demande=null, $matTech=null, $surPlace=null, $duree=null)
	{
		$req = 'INSERT INTO boninterv (
					 `BI_Velo`,
					 `BI_DatDebut`,
					 `BI_DatFin`,
					 `BI_CpteRendu`,
					 `BI_Reparable`,
					 `BI_Demande`,
					 `BI_Technicien`,
					 `BI_SurPlace`,
					 `BI_Duree`
					)
				VALUES (
					 :Vel_Num,
					 :dateDebut,
					 :dateFin,
					 :cpteRendu,
					 :reparable,
					 :demande,
					 :technicienCode,
					 :surPlace,
					 :duree
				 	)';

		$out = $this->oBdd->exec($req, array(
				 'Vel_Num'=>$Vel_Num,
				 'dateDebut'=>$dateDebut,
				 'dateFin'=>$dateFin,
				 'cpteRendu'=>$cpteRendu,
				 'reparable'=>$reparable,
				 'demande'=>$code_demande,
				 'technicienCode'=>$matTech,
				 'surPlace'=>$surPlace,
				 'duree'=>$duree,
				));
		return $out;
	}

		/**
		 * recherche les bon d'interv de l'utilisateur technicien
		 * @param  string $valeur   la string a chercher
		 * @param  int $techCode le matricule du technicien
		 * @return array           array d'object interv
		 */
	public function searchMesBonIntervention($valeur=null, $techCode=null)
	{
		$req = "SELECT *
				FROM `boninterv`
				WHERE
					(
						`BI_Num` LIKE :valeur
						OR `BI_Velo` LIKE :valeur
						OR `BI_Demande` LIKE :valeur
					)
					AND BI_Technicien = :techCode"
					;

		$lesBonsInter = $this->oBdd->query($req, array('valeur'=>'%'.$valeur.'%', 'techCode'=>$techCode));

		return $lesBonsInter;
	}
}
