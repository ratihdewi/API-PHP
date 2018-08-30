<?php
	if(!empty($_GET['m']) and $_GET['m']=='logout'){
		session_destroy();
			?>
				<script>
					window.location='index.php?m=home';
				</script>
			<?php
	}
?>