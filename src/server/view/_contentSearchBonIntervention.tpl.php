<div class="container">
	<form class="form-search" role="form" action="index.php" method="GET" >
		<h1 class="form-search-heading">Rechercher un bon</h1>
		<input type="hidden" name="page" class="form-control" value="intervention" required >
		<input type="hidden" name="action" class="form-control" value="rechercherbonintervention" required >
		<input type="search" name="valeur" class="form-control" placeholder="Code du bon, velo..." autofocus  autocomplete="off"
			onkeyup="ajax('GET', 'index.php', 'page=intervention&amp;action=ajaxrechercherbonintervention&amp;valeur='+this.value, 'allBonIntervention')"
			<?php
			if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
				echo 'value="'.$_GET['valeur'].'" ';
			?>>
        <button type="submit" class="btn btn-lg btn-primary btn-block" >Rechercher</button>
	</form>
</div>
