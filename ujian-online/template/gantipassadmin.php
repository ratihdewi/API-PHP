<?php
	if(!empty($_GET['m']) and $_GET['m']=='gantipass'  and empty($_GET['op']) and !empty($_SESSION['sess_login'])){
	?>
	<div id="smallRight">
	<h2 align='center'>Ganti Password </h2>
	
	<?php
	switch($_SESSION['sess_level'])
	{
		case "admin":
			$tbl = "admin";
			$pk = "id_admin";
			$col = "username,password";
			$wh = $pk." = '".$_SESSION['sess_id']."'";
		break;
		case "mahasiswa":
			$tbl = "mahasiswa";
			$pk = "id_mahasiswa";
			$col = "username,password";
			$wh = $pk." = '".$_SESSION['sess_id']."'";
		break;
		case "dosen":
			$tbl = "dosen";
			$pk = "id_dosen";
			$col = "username,password";
			$wh = $pk." = '".$_SESSION['sess_id']."'";
		break;
		case "pengawas":
			$tbl = "pengawas";
			$pk = "id_pengawas";
			$col = "username,password";
			$wh = $pk." = '".$_SESSION['sess_id']."'";
		break;
		default:
		
	}
		$sql = "select $col from $tbl where $wh";
	$query = mysql_query($sql);
	
	//insert to database
	if(isset($_POST['simpan'])){
		$i=0;
		$set="";
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
			$set .= $meta->name."='".$_POST[$meta->name]."',";	    	    							    	
	    	$i++;
        }
        $all_set = substr($set,0,strlen($set)-1);
       // echo "update $tbl set $all_set where $wh";
        //exit();
        mysql_query("update $tbl set $all_set where $wh");
        ?>
        <script>
        	alert("data berhasil di update");
        	window.location='index.php?m=<?php echo $_GET['m'] ?>';
        </script>
        <?php
	}
	
	?>
<!-- table-->	
<form action='' method='post'>
<table id="box-table-a" summary="Meeting Results">
   <thead>
    	   	
    	<?php
    	$f = mysql_fetch_array($query);
    	//cols
    	$i=0;
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
	    	if (!$meta) {
    		    echo "No information available<br />\n";
	    	}else{	    	
	    		
	    			$inp = "";
    	    		switch($meta->name)
	    			{	  
	    				case $pk:
	 		   				$inp = "[auto]<input type='hidden' name='".$meta->name."' id='".$meta->name."' value='".$f[$meta->name]."'>";	    					
	    					break;	  
						case "username":
							$inp ="<input type='text' name='".$meta->name."' id='".$meta->name."' value='".$f[$meta->name]."' >";		
							$name = "username";
							break;	
							case "password":
							$inp ="<input type='password' name='".$meta->name."' id='".$meta->name."' value='".$f[$meta->name]."' >";		
							$name = "password";
							break;	
	    				default:	
			    			$inp = "<input required type='text' name='".$meta->name."' id='".$meta->name."' value='".$f[$meta->name]."'>";	    					
	    			}

    	    		echo "<tr>     	    			
    	    			<th scope=\"col\">".str_replace("_"," ",strtoupper($meta->name))."</th>
	    	    		<td>
	    	    		$inp
	    	    		</td>
    		    	</tr>";        	
    	    	
        	}
	    	$i++;
        }
        ?>
        
  </thead>
    <tbody>
    	<td colspan='2' align='right'>
    	 <input type='button' class='button' name='batal' value='Batal' onclick="window.location='index.php?m=<?php echo $_GET['m']?>'">
    	<input type='submit' class='button' name='simpan' value='Simpan'>
    	</td>
    </body>
</table>
</form>
	<?php
	}
?>			