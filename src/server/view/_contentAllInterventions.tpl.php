<?php
if(empty($arg['isAjax'])) { ?>
<div class="container" id="allBonIntervention">
<?php } ?>
	<h1>Les Interventions</h1>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>N°</th>
				<th>Date début</th> <!-- pas de précision donc on affiche date de début-->
				<th>N° Velo</th>
				<th>Demande</th>
				<th>Interventant</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['lesInterventions']) and is_array($arg['lesInterventions']))
			{
				foreach ($arg['lesInterventions'] as $uneInter) {
					?>
					<tr>
						<td>
							<!-- <a href="index.php?page=intervention&amp;action=monbonintervention&amp;valeur=<?php echo $uneInter->BI_Num;?>"> -->
								<?php echo $uneInter->BI_Num;?>
							<!-- </a> -->
						</td>
						<td><?php echo $uneInter->BI_DatDebut;?></td>
						<td>
							<a href="index.php?page=velo&amp;action=unvelo&amp;valeur=<?php echo $uneInter->BI_Velo;?>">
								<?php echo $uneInter->BI_Velo;?>
							</a>
						</td>
						<td>
							<a href="index.php?page=intervention&amp;action=unedemandeinter&amp;valeur=<?php echo $uneInter->BI_Demande;?>">
								<?php echo $uneInter->BI_Demande;?>
							</a>
						</td>
						<td>
							<?php echo $uneInter->Tec_Nom;?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
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
				?>
			</div>
		</div>
		<?php
		$_SESSION['tampon']['error'] = null;
	}

if(empty($arg['isAjax'])) { ?>
</div><!-- /.container -->
<?php
}
