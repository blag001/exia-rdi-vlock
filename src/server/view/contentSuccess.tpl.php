<div class="container">
	<div class="has-success">
		<div class="input-group-addon">
			<?php
			if(!empty($_SESSION['tampon']['success']) and is_array($_SESSION['tampon']['success']))
			{
				foreach ($_SESSION['tampon']['success'] as $value) {
					echo $value .'<br />';
				}
				$_SESSION['tampon']['success'] = null;
			}
			?>
		</div>
	</div>
</div><!-- /.container -->
