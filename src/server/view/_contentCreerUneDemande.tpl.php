<div class="container">
	<form class="form-add" role="form" action="index.php?page=intervention&amp;action=creerdemandeintervention" method="POST" >
		<h1 class="form-add-heading">Cr&eacute;er une demande d'intervention</h1>

		<div class="form-group">
			<label for="vel_num">V&eacute;lo concern&eacute;</label>
				<?php
				if(!empty($_POST['vel_num']))
				{
					echo '<p class="form-control-static">'.$_POST['vel_num'].'</p>';
					echo '<input type="hidden" class="form-control" name="vel_num" value="'.$_POST['vel_num'].'" />';
				}
				elseif(
					!empty($arg['lesVelos'])
					and is_array($arg['lesVelos'])
					)
				{
					echo '<select class="form-control" id="vel_num" name="vel_num" >';
					foreach ($arg['lesVelos'] as $unVelo)
					{
						echo '<option value="'.$unVelo->Vel_Num.'" ';
						if ($unVelo->Vel_Num == $arg['leVeloNum'])
							echo ' selected="selected" ';
						echo '>'.$unVelo->Vel_Num.'</option>';
					}
					echo '</select>';
				}
				?>

			<label for="cpteRendu">Comtpe rendu</label>
			<input type="text" class="form-control"  id="cpteRendu" name="cpteRendu" placeholder="Motif du probleme"
				<?php if(!empty($_POST['cpteRendu'])) echo 'value="'.$_POST['cpteRendu'].'"'; ?>
				 />



        	<button type="submit" class="btn btn-lg btn-primary btn-block" >Ajouter la demande</button>
		</div>
	</form>
</div>
