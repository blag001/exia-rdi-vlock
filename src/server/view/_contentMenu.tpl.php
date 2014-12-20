<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<!-- bouton en format mini -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">V-Lyon</a>
		</div>
		<!-- full menu -->
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<?php
					$menu = $_SESSION['menu']->getCurrentMenu(0);
					echo '<a href="'.$menu['url'].'" class="dropdown-toggle" data-toggle="dropdown">';
					echo $menu['title'].' <b class="caret"></b></a>';
					?>
					<ul class="dropdown-menu">
						<?php
						foreach ($_SESSION['menu']->getListMenu(0) as $title => $url) {
							echo '<li';
							if($_SESSION['menu']->isCurrentMenu(0, $title))
								echo ' class="active"';
							echo ' >';
							echo '<a href="'.$url.'">'.$title.'</a>';
							echo '</li>';
						}

						?>
					</ul>
				</li>

				<?php
				$subMenu = $_SESSION['menu']->getListMenu(1);
				// si presence d'un sous menu
				if(!empty($subMenu))
				{
					?>
					<li class="dropdown">
						<?php
						$menu = $_SESSION['menu']->getCurrentMenu(1);
						echo '<a href="'.$menu['url'].'" class="dropdown-toggle" data-toggle="dropdown">';
						echo $menu['title'].' <b class="caret"></b></a>';
						?>
						<ul class="dropdown-menu">
						<?php
						foreach ($subMenu as $title => $url) {
							echo '<li';
							if($_SESSION['menu']->isCurrentMenu(1, $title))
								echo ' class="active"';
							echo ' >';
							echo '<a href="'.$url.'">'.$title.'</a>';
							echo '</li>';
						}

						?>
						</ul>
					</li>
					<?php
				}
				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="index.php?page=logout">Se d&eacute;conecter</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>
