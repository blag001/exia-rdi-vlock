<?php
/**
 * fichier de declaration du model de Location
 */

	/**
	 * class de gestion BDD des Locations
	 */
class OdbLocation
{

		/**
		 * contruteur du model
		 */
	public function __construct()
	{
	}

		/**
		 * test si un id a une location en bdd
		 * @param  int $id_user id du user
		 * @return bool       un vrai/faux de l'existance
		 */
	public function haveALockedNow($id_user=null)
	{
		if(!empty($id_user))
		{
			$req = 'SELECT COUNT(*) AS nb
					FROM location
					WHERE id = :id_user';

			$data = $_SESSION['bdd']->query($req , array('id_user'=>$id_user), Bdd::SINGLE_RES);

			return (bool) $data->nb;
		}

		return false;
	}

		/**
		 * test si un socket est libre
		 * @param  int $id_socket id du socket
		 * @return bool       un vrai/faux de l'existance
		 */
	public function isFreeSocket($id_socket=null)
	{
		if(!empty($id_socket))
		{
			$req = 'SELECT emp_used
					FROM emplacement
					WHERE id = :id_socket';

			$data = $_SESSION['bdd']->query($req , array('id_socket'=>$id_socket), Bdd::SINGLE_RES);

			return (bool) !$data->emp_used;
		}

		return false;
	}

		/**
		 * retournes le socket liee au compte
		 * @return int le socket
		 */
	public function getSocket($id_user=null)
	{
		$req = "SELECT *
				FROM location
				WHERE id = :id_user";

		$leSocket = $_SESSION['bdd']->query($req , array('id_user'=>$id_user), Bdd::SINGLE_RES);

		return $leSocket->id_emplacement;
	}

		/**
		 * retournes les socket libre
		 * @return int le socket
		 */
	public function getFreeSocket()
	{
		$req = "SELECT id
				FROM emplacement
				WHERE emp_used = '0'";

		$lesSocket = $_SESSION['bdd']->query($req);

		return $lesSocket;
	}

		/**
		 * cree une location
		 * @param  int $id_user       id du user
		 * @param  int $socket id du socket
		 * @return int                nombre de ligne inseree
		 */
	public function lock($id_user=null, $socket=null)
	{
		$req = 'INSERT INTO location (
					 `id`,
					 `id_emplacement`
					)
				VALUES (
					 :id_user,
					 :socket
				 	)';

		$out = $_SESSION['bdd']->exec($req, array(
				 'id_user'=>$id_user,
				 'socket'=>$socket,
				));
		return $out;
	}

		/**
		 * cloture une location
		 * @param  int $id_user       id du user
		 * @return int                nombre de ligne delete
		 */
	public function unlock($id_user=null)
	{
		$req = 'DELETE FROM location
				WHERE `id` = :id_user';

		$out = $_SESSION['bdd']->exec($req, array(
				 'id_user'=>$id_user,
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

		$lesBonsInter = $_SESSION['bdd']->query($req, null, Bdd::SINGLE_RES);

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

		$lesDemandesInter = $_SESSION['bdd']->query($req,
			array(
				'valeur'=>'%'.$valeur.'%',
				 'techCode'=>$techCode,
				 ));

		return $lesDemandesInter;
	}

}
