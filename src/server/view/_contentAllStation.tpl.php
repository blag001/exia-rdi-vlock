<?php
if(empty($arg['isAjax'])) { ?>
<div class="container" id="allStation">
<?php } ?>
	<h1>Les Stations</h1>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Id</th>
				<th>Nom</th>
				<th>Nb Attaches</th>
				<th>Nb Velo Dispo</th>
				<th>Nb Attaches Dispo</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['lesStations']) and is_array($arg['lesStations']))
			{
				foreach ($arg['lesStations'] as $value) {
					?>
					<tr>
						<td>
							<a href="index.php?page=station&amp;action=unestation&amp;valeur=<?php echo $value->Sta_Code;?>">
								<?php echo $value->Sta_Code;?>
							</a>
						</td>
						<td><?php echo $value->Sta_Nom;?></td>
						<td><?php echo $value->Sta_NbAttaches;?></td>
						<td><?php echo $value->Sta_NbVelos;?></td>
						<td><?php echo $value->Sta_NbAttacDispo;?></td>
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
