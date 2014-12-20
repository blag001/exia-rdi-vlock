<?php
if(empty($arg['isAjax'])) { ?>
<div class="container" id="allVelo">
<?php } ?>
	<h1>Les V&eacute;los</h1>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Num</th>
				<th>&Eacute;tat</th>
				<th>Type</th>
				<th>Accessoires</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(!empty($arg['lesVelos']) and is_array($arg['lesVelos']))
			{
				foreach ($arg['lesVelos'] as $value) {
				?>
					<tr>
						<td>
							<a href="index.php?page=velo&amp;action=unvelo&amp;valeur=<?php echo $value->Vel_Num;?>">
								<?php echo $value->Vel_Num;?>
							</a>
						</td>
						<td><?php echo $value->Eta_Libelle;?></td>
						<td><?php echo $value->Pdt_Libelle;?></td>
						<td><?php echo $value->Vel_Accessoire;?></td>
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
