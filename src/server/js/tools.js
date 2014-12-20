// fonction JS perso

	/**
	 * gere un objet de connexion http
	 *
	 * @return {object} objet de conexion suivant le navigateur
	 */
function _getReqHttp()
{
	var reqHttp = null;

	if(window.XMLHttpRequest){
		// Firefox (Mozilla) et autres
		reqHttp = new XMLHttpRequest();
	}
	else{
		if(window.ActiveXObject){
			// Internet Explorer
			try{
				reqHttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e){
				reqHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else{
			// XMLHttpRequest non supporté par le navigateur
			reqHttp = false;
		}
	}

	return reqHttp;
}

	/**
	 * envois le(s) argument(s) sur l'url cible et met le retour dans l'idTarget
	 *
	 * @param  {string} methode   POST ou GET
	 * @param  {string} urlTarget l'url visee par la requete AJAX
	 * @param  {string} arg       le(s) arguement(s) a passer
	 * @param  {string} idTarget  l'id de reception du retour
	 * @return {void}           pas de retour
	 */
function ajax(methode, urlTarget, arg, idTarget )
{
	// récupération d'un objet XMLHttpRequest
	var reqHttp1 = _getReqHttp() ;

	if ( reqHttp1 == null){
		alert("Impossible d'utiliser Ajax sur ce navigateur");
	}
	else
	{
		reqHttp1.onreadystatechange = function()
		{
			if (reqHttp1.readyState == 4 && reqHttp1.status == 200)
			{
				leChampsTarget = document.getElementById(idTarget);
				leChampsTarget.innerHTML = reqHttp1.responseText;
			}
		}

		if ( methode == "POST")
		{
			// ouverture de la connexion avec le serveur
			reqHttp1.open("POST", encodeURI(urlTarget), true) ;
			reqHttp1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// envoi de la requête
			reqHttp1.send(arg);
		}
		else if (methode == "GET")
		{
			// ouverture de la connexion avec le serveur - méthode GET - asynchrone
			reqHttp1.open("GET", encodeURI(urlTarget) + "?" + encodeURI(arg), true);
			// envoi de la requête
			reqHttp1.send(null);
		}
	}
}

	/**
	 * change le contenu de idTarget par valOnTrue ou valOnFalse,
	 * suivant la valeur boolean de value
	 *
	 * @param  {boolean} value      la valeur à tester
	 * @param  {string} idTarget   l'id de reception du retour
	 * @param  {string} valOnTrue  texte que doit valoir idTarget si value = true
	 * @param  {string} valOnFalse texte que doit valoir idTarget si value = false
	 * @return {void}            pas de retour
	 */
function switcher(value, idTarget, valOnTrue, valOnFalse) {
	if(value)
		document.getElementById(idTarget).innerHTML = valOnTrue;
	else
		document.getElementById(idTarget).innerHTML = valOnFalse;
}

// function local
	/**
	 * change le formulaire entre une intervention et une demande
	 * @param  {bool} value valeur a tester TRUE, =>bon
	 * @return {void}       fait les modif dans le DOM
	 */
function switchBonEtDemInter (value) {
		// change name title
	switcher(value, 'idTitle', 'Cr&eacute;er un bon d\'intervention', 'Cr&eacute;er une demande d\'intervention');

		// display or not le 2eme input date
	if(value){
		document.getElementById('idBonInter').style.display = 'inline';
		document.getElementById('idSubmit').setAttribute('name', 'sbmtMkBon');
		document.getElementById('form_inter').setAttribute('action', 'index.php?page=intervention&action=creerbonintervention');
	}
	else{
		document.getElementById('idBonInter').style.display = 'none';
		document.getElementById('idSubmit').setAttribute('name', 'sbmtMkDemande');
		document.getElementById('form_inter').setAttribute('action', 'index.php?page=intervention&action=creerdemandeinter');
	}

		// change name dateStart
	switcher(value, 'idStartDate', 'Date de d&eacute;but de l\'intervention', 'Date de la demande');

		// change la value du submit
	switcher(value, 'idSubmit', 'Ajouter le bon', 'Ajouter la demande');

}
