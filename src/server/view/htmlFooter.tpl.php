<?php
if(!empty($_SESSION['tampon']['html']['js']) and is_array($_SESSION['tampon']['html']['js']))
	foreach ($_SESSION['tampon']['html']['js'] as $value)
		echo $_SESSION['tampon']['html']['js'] . "\n";
?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="toolBootstrap/js/jquery-1.10.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="toolBootstrap/js/bootstrap.min.js"></script>
    <!-- gestion de l'AJAX -->
    <script src="js/tools.js"></script>
  </body>
</html>
