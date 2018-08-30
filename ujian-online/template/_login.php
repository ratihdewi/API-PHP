<?php
	/*if(empty($_GET['m']) or $_GET['m']=='login'){*/ 
	if(empty($_GET['m']) or empty($_SESSION['sess_login'])){	
?>
	<form class='box' action='index.php?m=login_db' method='POST'>
		<fieldset class="boxBody">
			<h2>Form Login</h2> 
		  <input type="text" tabindex="1" placeholder="Username" name="uname" required>		  
		  <input type="password" tabindex="2" required name="pwd"  placeholder="Password">				  		
		  <input type="submit" class='button' name='login' value="Login" tabindex="4">
		</fieldset>
	</form>
	
<?php
	}
	?>
	