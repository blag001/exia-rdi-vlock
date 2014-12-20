<div class="container">
	<form class="form-add" role="form" action="index.php?page=velo&amp;action=modifiervelo<?php
				if (!empty($arg['leVelo']))
					echo '&amp;valeur='.$arg['leVelo']->Vel_Num;
			?>" method="POST" >
		<h1 class="form-add-heading">Modifier un V&eacute;lo</h1>
		<?php
		if(!empty($_SESSION['tampon']['error']) and is_array($_SESSION['tampon']['error']))
		{
			?>
			<div class="has-error">
				<div class="input-group-addon">
					<?php
					foreach ($_SESSION['tampon']['error'] as $value) {
						echo $value .'<br />';
					}
					$_SESSION['tampon']['error'] = null;
					?>
				</div>
			</div>
			<?php
		}
		?>
		<div class="form-group">
			<label >No du v&eacute;lo</label>
			<p class="form-control-static">
				<?php
					if (!empty($arg['leVelo']))
						echo $arg['leVelo']->Vel_Num;
				?>
			</p>
		</div>
		<div class="form-group">
			<label for="vel_station">Station du v&eacute;lo</label>
			<select class="form-control" id="vel_station" name="stationVelo" >
				<?php
				if(
					!empty($arg['lesStations'])
					and is_array($arg['lesStations'])
					and !empty($arg['leVelo'])
					)
				{
					foreach ($arg['lesStations'] as $value)
					{
						echo '<option value="'.$value->Sta_Code.'"';
						if ($value->Sta_Code == $arg['leVelo']->Vel_Station)
							echo ' selected="selected"';
						echo ' >'.$value->Sta_Code.'</option>';
					}
				}
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="vel_etat">&Eacute;tat du v&eacute;lo</label>
			<select class="form-control" id="vel_etat" name="etatVelo" >
				<?php
				if(
					!empty($arg['lesEtats'])
					and is_array($arg['lesEtats'])
					and !empty($arg['leVelo'])
					)
				{
					foreach ($arg['lesEtats'] as $value)
					{
						echo '<option value="'.$value->Eta_Code.'"';
						if ($value->Eta_Code == $arg['leVelo']->Vel_Etat)
							echo ' selected="selected"';
						echo ' >'.$value->Eta_Libelle.'</option>';
					}
				}
				?>
			</select>
		</div>
		<div class="form-group">
			<label >Type du v&eacute;lo</label>
			<p class="form-control-static">
				<?php
				if(!empty($arg['leVelo'])){
					echo $arg['leVelo']->Pdt_Libelle;
				}
				?>
			</p>
		</div>
		<div class="form-group">
			<label for="vel_accessoire">Accessoire(s)</label>
			<input class="form-control" id="vel_accessoire" type="text" name="accessoireVelo"<?php
				if (!empty($arg['leVelo']))
					echo ' value="'.$arg['leVelo']->Vel_Accessoire.'"';
			?> />
		</div>
		<div class="checkbox">
			<label for="vel_casse">
				<input type="checkbox" value="1" id="vel_casse" name="veloCasse" disabled />
				V&eacute;lo Cass&eacute;.
			</label>
		</div>
        <button type="submit" class="btn btn-lg btn-primary btn-block" >Modifier</button>
	</form>
</div>
