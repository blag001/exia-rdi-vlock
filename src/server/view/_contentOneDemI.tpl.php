<div class="container">
	<h1>Informations sur la demande d'intervention</h1>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Id</th>
				<th>Date</th>
				<th>Demandeur</th>
				<th>No Velo</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['uneDemandeInter']))
			{
				?>
				<tr>
					<td>
						<a href="index.php?page=intervention&amp;action=unedemandeinter&amp;valeur=<?php echo $arg['uneDemandeInter']->DemI_Num;?>">
							<?php echo $arg['uneDemandeInter']->DemI_Num;?>
						</a>
					</td>
					<td><?php echo $arg['uneDemandeInter']->DemI_Date;?></td>
					<td><?php echo $arg['uneDemandeInter']->Tec_Nom;?></td>
					<td>
						<a href="index.php?page=velo&amp;action=unvelo&amp;valeur=<?php echo $arg['uneDemandeInter']->DemI_Velo;?>">
							<?php echo $arg['uneDemandeInter']->DemI_Velo;?>
						</a>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>No Station</th>
				<th>Station</th>
				<th>Motif</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['uneDemandeInter']))
			{
				?>
				<tr>
					<td>
						<a href="index.php?page=station&amp;action=unestation&amp;valeur=<?php echo $arg['uneDemandeInter']->Sta_Code;?>">
							<?php echo $arg['uneDemandeInter']->Sta_Code;?>
						</a>
					</td>
					<td><?php echo $arg['uneDemandeInter']->Sta_Nom;?></td>
					<td><?php echo $arg['uneDemandeInter']->DemI_Motif;?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
	if(empty($arg['uneDemandeInter']->DemI_Traite))
	{
		?>
		<form class="form-add" role="form" action="index.php" method="GET" >
			<input type="hidden" name="page" class="form-control" value="intervention" />
			<input type="hidden" name="action" class="form-control" value="creerbonintervention" />
			<input type="hidden" name="code_demande" class="form-control" <?php
				if(!empty($arg['uneDemandeInter']))
					echo 'value="'.$arg['uneDemandeInter']->DemI_Num.'" ';
				?> />
	        <button type="submit" class="btn btn-lg btn-primary btn-block" >Intervenir</button>
		</form>
		<?php
	}
	?>

</div><!-- /.container -->
