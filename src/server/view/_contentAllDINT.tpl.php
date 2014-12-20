<div class="container">
	<h1>Demandes non trait&eacute;es</h1>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Station</th>
				<th>No Velo</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['lesDemandesINT']) and is_array($arg['lesDemandesINT']))
			{
				foreach ($arg['lesDemandesINT'] as $value) {
					?>
					<tr>
						<td>
							<a href="index.php?page=intervention&amp;action=unedemandeinter&amp;valeur=<?php echo $value->DemI_Num;?>">
								<?php echo $value->DemI_Num;?>
							</a>
						</td>
						<td><?php echo $value->DemI_Date;?></td>
						<td><?php echo $value->Sta_Nom;?></td>
						<td>
							<a href="index.php?page=velo&amp;action=unvelo&amp;valeur=<?php echo $value->DemI_Velo;?>">
								<?php echo $value->DemI_Velo;?>
							</a>
						</td>
						<td>
							<form class="form-add" role="form" action="index.php" method="GET" >
								<input type="hidden" name="page" class="form-control" value="intervention" />
								<input type="hidden" name="action" class="form-control" value="creerbonintervention" />
								<input type="hidden" name="code_demande" class="form-control" <?php
										echo 'value="'.$value->DemI_Num.'" ';
									?> />
								<button type="submit" class="btn btn-xs btn-primary btn-block" >Intervenir</button>
							</form>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>

</div><!-- /.container -->
