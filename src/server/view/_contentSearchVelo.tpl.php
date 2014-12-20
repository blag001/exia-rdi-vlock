<div class="container">
	<form class="form-search" role="form" action="index.php" method="GET" >
		<h1 class="form-search-heading">Rechercher un Velo</h1>
		<input type="hidden" name="page" class="form-control" value="velo" required >
		<input type="hidden" name="action" class="form-control" value="recherchervelo" required >
		<input type="search" name="valeur" class="form-control" placeholder="Code v&eacute;lo ou code station" autofocus  autocomplete="off"
			onkeyup="ajax('GET', 'index.php', 'page=velo&amp;action=ajaxrecherchervelo&amp;valeur='+this.value, 'allVelo')"
			<?php
			if(isset($_GET['valeur']) and $_GET['valeur'] !== '')
				echo 'value="'.$_GET['valeur'].'" ';
			?>>
        <button type="submit" class="btn btn-lg btn-primary btn-block" >Rechercher</button>
	</form>
</div>
