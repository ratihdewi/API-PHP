<?php
	mysql_connect("localhost","root","");
	mysql_select_db("ujian_online");
	
	$text = $_POST["text"];
	
	$sql = "SELECT * from stemlist where stem ='$text' LIMIT 1";
	//echo $sql.'<br/>';
	$result = mysql_query($sql) or die(mysql_error());  
	if(mysql_num_rows($result)==1){
		$arrayy = array('jumlah' => 1,'kembalian'=>'a');
	
		
		echo json_encode($arrayy);
	}else{
		$arrayy = array('jumlah' => 0);
	
	
		echo json_encode($arrayy);
	}
	
	
	
