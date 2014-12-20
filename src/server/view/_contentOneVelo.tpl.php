<div class="container">
	<h1>Informations V&eacute;lo</h1>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Num</th>
				<th>Station</th>
				<th>&Eacute;tat</th>
				<th>Type</th>
				<th>Accessoires</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['unVelo']))
			{
			?>
				<tr>
					<td>
						<a href="index.php?page=velo&amp;action=unvelo&amp;valeur=<?php echo $arg['unVelo']->Vel_Num;?>">
							<?php echo $arg['unVelo']->Vel_Num;?>
						</a>
					</td>
					<td>
						<a href="index.php?page=station&amp;action=unestation&amp;valeur=<?php echo $arg['unVelo']->Vel_Station;?>">
							<?php echo $arg['unVelo']->Vel_Station;?>
						</a>
					</td>
					<td><?php echo $arg['unVelo']->Eta_Libelle;?></td>
					<td><?php echo $arg['unVelo']->Pdt_Libelle;?></td>
					<td><?php echo $arg['unVelo']->Vel_Accessoire;?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<form class="form-add" role="form" action="index.php" method="GET" >

		<input type="hidden" name="page" class="form-control" value="velo" required >
		<input type="hidden" name="action" class="form-control" value="modifiervelo" required >
		<input type="hidden" name="valeur" class="form-control" <?php
			if(!empty($arg['unVelo']))
				echo 'value="'.$arg['unVelo']->Vel_Num.'" ';
			?> required >
        <button type="submit" class="btn btn-lg btn-primary btn-block" >Modifier</button>
	</form>
	<br />
	<form class="form-add" role="form" action="index.php" method="GET" >
		<input type="hidden" name="page" class="form-control" value="intervention" required >
		<input type="hidden" name="action" class="form-control" value="creerbonintervention" required >
		<input type="hidden" name="code_velo" class="form-control" <?php
			if(!empty($arg['unVelo']))
				echo 'value="'.$arg['unVelo']->Vel_Num.'" ';
			?> required >
        <button type="submit" class="btn btn-lg btn-primary btn-block" >Intervenir</button>
	</form>
</div><!-- /.container -->
