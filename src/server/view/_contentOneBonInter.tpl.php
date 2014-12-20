<div class="container">
	<h1>Informations sur l'intervention</h1>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Id</th>
				<th>Velo</th>
				<th>N° Demande</th>
				<th>Date Début</th>
				<th>Date Fin</th>
				<th>Durée</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['unBonInter']))
			{
				?>
				<tr>
					<td>
						<a href="index.php?page=intervention&amp;action=monbonintervention&amp;valeur=<?php echo $arg['unBonInter']->BI_Num;?>">
							<?php echo $arg['unBonInter']->BI_Num;?>
						</a>
					</td>
					<td>
						<a href="index.php?page=velo&amp;action=unvelo&amp;valeur=<?php echo $arg['unBonInter']->BI_Velo;?>">
							<?php echo $arg['unBonInter']->BI_Velo;?>
						</a>
					</td>
					<td>
						<a href="index.php?page=intervention&amp;action=unedemandeinter&amp;valeur=<?php echo $arg['unBonInter']->BI_Demande;?>">
							<?php echo $arg['unBonInter']->BI_Demande;?>
						</a>
					</td>
					<td><?php echo $arg['unBonInter']->BI_DatDebut;?></td>
					<td><?php echo $arg['unBonInter']->BI_DatFin;?></td>
					<td><?php echo $arg['unBonInter']->BI_Duree;?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Sur place</th>
				<th>Réparable</th>
				<th>Compte rendu</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['unBonInter']))
			{
				?>
				<tr>
					<td><?php if(!empty($arg['unBonInter']->BI_SurPlace)) echo 'oui';else echo 'non';?></td>
					<td><?php if(!empty($arg['unBonInter']->BI_Reparable)) echo 'oui';else echo 'non';?></td>
					<td><?php echo $arg['unBonInter']->BI_CpteRendu;?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

</div><!-- /.container -->
