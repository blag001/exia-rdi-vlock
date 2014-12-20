<div class="container">
	<div class="has-error">
		<div class="input-group-addon">
			<?php
			if(!empty($_SESSION['tampon']['error']) and is_array($_SESSION['tampon']['error']))
			{
				foreach ($_SESSION['tampon']['error'] as $key=>$value) {
					// if(is_int($key))
						echo $value .'<br />';
				}

				$_SESSION['tampon']['error'] = null;
			}
			?>
		</div>
	</div>
</div><!-- /.container -->
