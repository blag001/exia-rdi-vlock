<div class="container">
	<form id="form_inter" class="form-add" role="form" action="index.php?page=intervention&amp;action=creerbonintervention" method="POST" >
		<h1 class="form-add-heading" id="idTitle" >Cr&eacute;er un bon d'intervention</h1>

		<div class="form-group">
			<?php
			if(!empty($arg['laDemandeInterNum']) or !empty($_POST['code_demande']))
			{ ?>
				<label for="code_demande">Demande concern&eacute;e</label>
				<p class="form-control-static">
					<?php
					if (!empty($arg['laDemandeInterNum']))
						echo $arg['laDemandeInterNum'];
					elseif(!empty($_POST['code_demande']))
						echo $_POST['code_demande'];
					?>
				</p>

				<input type="hidden" class="form-control" name="code_demande"
					<?php
					if (!empty($arg['laDemandeInterNum']))
						echo ' value="'.$arg['laDemandeInterNum'].'" ';
					elseif(!empty($_POST['code_demande']))
						echo ' value="'.$_POST['code_demande'].'" ';
					?>
					/>
				<?php
			}
			?>
			<label for="vel_num">V&eacute;lo concern&eacute;</label>
				<?php
				if(!empty($arg['leVeloNum']) or !empty($_POST['vel_num'])){
					?>
					<p class="form-control-static">
						<?php
						if(!empty($arg['leVeloNum']))
							echo $arg['leVeloNum'];
						elseif(!empty($_POST['vel_num']))
							echo $_POST['vel_num'];
						?>
					</p>
					<input type="hidden" class="form-control" name="vel_num"
					<?php
						if(!empty($arg['leVeloNum']))
							echo ' value="'.$arg['leVeloNum'].'" />';
						elseif(!empty($_POST['vel_num']))
							echo ' value="'.$_POST['vel_num'].'" />';
				}
				elseif(
					!empty($arg['lesVelos'])
					and is_array($arg['lesVelos'])
					)
				{
					?>
					<select class="form-control" id="vel_num" name="vel_num" >
					<?php
					foreach ($arg['lesVelos'] as $unVelo)
					{
						echo '<option';
						echo ' value="'.$unVelo->Vel_Num.'" ';
						if ($unVelo->Vel_Num == $arg['leVeloNum'])
							echo ' selected="selected" ';
						echo '>'.$unVelo->Vel_Num.'</option>';
					}
					?>
					</select>
					<?php
				}
				?>

			<label for="cpteRendu">Compte rendu</label>
			<input type="text" class="form-control"  id="cpteRendu" name="cpteRendu" placeholder="Motif du probleme">

			<div id="idBonInter">
				<label for="dateDebut" id="idStartDate" >Date de d&eacute;but de l'intervention</label>
				<input type="date" class="form-control"  id="dateDebut" name="dateDebut" placeholder="Date de debut" value="<?php echo date('d/m/Y');?>" />

				<label for="dateFin">Date de fin de l'intervention</label>
				<input type="date" class="form-control"  id="dateFin" name="dateFin" placeholder="Date de fin" value="<?php echo date('d/m/Y');?>" />

				<div class="checkbox">
					<label for="vel_non_reparable">
					<input type="checkbox" id="vel_non_reparable" name="veloNonReparable">
					V&eacute;lo NON r&eacute;parable
					</label>
				</div>
			</div>

			<div class="checkbox">
				<label for="vel_surPlace">
				<input type="checkbox" id="vel_surPlace" name="surPlace" checked="checked"
					<?php
					if(empty($arg['laDemandeInterNum']) and empty($_POST['code_demande']))
						echo ' onchange="switchBonEtDemInter(this.checked)"';
					?>
					/>
				R&eacute;parable sur place
				</label>
			</div>



        	<button type="submit" class="btn btn-lg btn-primary btn-block" name="sbmtMkBon" id="idSubmit" >Ajouter le bon</button>
		</div>
	</form>
</div>
