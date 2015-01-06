<div class="container">
	<form id="form_inter" class="form-add" role="form" action="index.php" method="POST" >
		<h1 class="form-add-heading" id="idTitle" >Sélectionnez l'emplacement à vérrouiller</h1>

		<div class="form-group">
			<input type="hidden" name="id_user" class="form-control" value="<?php echo $arg['id_user'];?>" >
			<label for="socket">Emplacement :</label>
				<?php
				if(
					!empty($arg['lesSocketFree'])
					and is_array($arg['lesSocketFree'])
					)
				{
					?>
					<select class="form-control" id="socket" name="socket" >
					<?php
					foreach ($arg['lesSocketFree'] as $unSocket)
					{
						echo '<option value="'.$unSocket->id.'" >'.$unSocket->id.'</option>';
					}
					?>
					</select>
					<?php
				}
				?>
        	<button type="submit" class="btn btn-lg btn-primary btn-block" name="sbmtLockSocket" id="idSubmit" >Vérrouiller</button>
		</div>
	</form>
</div>
