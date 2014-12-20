<div class="container">
	<form class="form-search" role="form" action="index.php" method="GET" >
		<h1 class="form-search-heading">Rechercher une Station</h1>
		<input type="hidden" name="page" class="form-control" value="station" required >
		<input type="hidden" name="action" class="form-control" value="rechercherstation" required >
		<input type="search" name="valeur" class="form-control" placeholder="Code, nom ou rue" autofocus autocomplete="off"
			onkeyup="ajax('GET', 'index.php', 'page=station&amp;action=ajaxrechercherstation&amp;valeur='+this.value, 'allStation')"
			<?php
			if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
				echo 'value="'.$_GET['valeur'].'" ';
			?>>
        <button type="submit" class="btn btn-lg btn-primary btn-block" >Rechercher</button>
	</form>
</div>
