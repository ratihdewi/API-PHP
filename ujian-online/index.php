<?php
session_start();

	foreach (glob("application/*.php") as $filename){
	    include $filename;
		error_reporting(E_ERROR | E_PARSE);
	}
?>
<html>
<head>
<title>Sistem Ujian Online </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Copyright" content="arirusmanto.com">
<meta name="description" content="Admin MOS Template">
<meta name="keywords" content="Admin Page">
<meta name="author" content="Ari Rusmanto">
<meta name="language" content="Bahasa Indonesia">

<link rel="shortcut icon" href="mos-css/img/favicon.gif"> <!--Pemanggilan gambar favicon-->
<link rel="stylesheet" type="text/css" href="mos-css/mos-style.css"> <!--pemanggilan file css-->
<link rel="stylesheet" type="text/css" href="addons/chosen_v1.4.0/chosen.css"> <!--pemanggilan file css-->
<script src="addons/chosen_v1.4.0/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="mos-css/modal.css">
<script src="scripts/modaljs.js"> </script>

<!--
 	<link rel="stylesheet" type="text/css" href="mos-css/styles.css">
   	<script src="mos-css/jquery-latest.min.js" type="text/javascript"></script>
   	<script src="script.js"></script>
-->
</head>
<?php
/**if($_GET['m']=='ujianmhs')
{
	$x = "onkeydown=\"return (event.keyCode == 154)\"";
}else{
	$x = "";
}**/	
?>
<body <?php //echo $x; ?>>
<?php
if(empty($_GET['m']) or empty($_SESSION['sess_login'])){	
?>
<div id="loginForm">
	<div class="headLoginForm">
	Form Login Sistem
	</div>
	<div class="fieldLogin">
	<form   action='index.php?m=login_db' method='POST'>
		
		  <input type="text" tabindex="1" placeholder="Username" name="uname" required>	<br>	  
		  <input type="password" tabindex="2" required name="pwd"  placeholder="Password">	<br>			  		
		  <input type="submit" class='button' name='login' value="Login" tabindex="4">
		
	</form>
	</div>
	
<?php
	}else{
?>
<div id="header">
  <div class="inHeader1">
    <div class="mosAdmin1">
    	<h3><font face="Palatino Linotype, Book Antiqua, Palatino, serif" color="#009900">SISTEM  INFORMASI UJIAN ONLINE ESSAY </font></h3>
		<P align="left">	Hallo, <?php echo $_SESSION['sess_login'] ?> <br>
		<?php if(!empty($_GET['m']) and $_GET['m']=="gantipass") ?><a href="index.php?m=gantipass">Ganti Password</a> | <a href="index.php?m=logout">Keluar</a>
		</P>
    </div>
	<div class="clear">
	</div>
	</div>
 </div>
</div>

<div id="wrapper">
	
	<?php
		//MENU NAV
		
		foreach (glob("template/menu/*.php") as $filename){
			include $filename;
		}
		foreach (glob("template/*.php") as $filename){
	    	include $filename;
	}
	?>
	
	
</div>
<div class="clear"></div>
<div id="footer">
	  UIN Suska Riau &copy; 2015<br/>
	  Create by Dheru Alam Perkasa 
</div>
<?php
}
?>
	
  <script src="addons/chosen_v1.4.0/chosen.jquery.js" type="text/javascript"></script>
  <script src="addons/chosen_v1.4.0/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>
</body>
</html>