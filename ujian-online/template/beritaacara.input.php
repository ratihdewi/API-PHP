<?php
	if(!empty($_GET['m']) and $_GET['m']=='beritaacara'  and !empty($_GET['op']) and $_GET['op']=='input'){		
	?>
	<div id="map" style='width:100%;'></div>
	<h2 align='center'>Tambah <?php echo $_GET['m'] ?> </h2>
	
	<?php
	$tbl = "berita_acara";
	$col = "*";
	$pk = "id_berita_acara";
	$sql = "select $col from $tbl";
	$query = mysql_query($sql);
	
	//insert to database
	if(isset($_POST['simpan'])){
		
		//for($jmhs=0;$jmhs<count($_POST['id_mahasiswa']);$jmhs++){
		$i=0;
		$cols="";
		$vals="";
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
    		switch($meta->name)
    		{
    						    	
				default:
					$cols .= $meta->name.",";	    	    	
					$vals .= "'".$_POST[$meta->name]."',";    				    	
				
			}
	    	$i++;
        }
        $all_cols = substr($cols,0,strlen($cols)-1);
        $all_vals = substr($vals,0,strlen($vals)-1);        
        mysql_query("insert into $tbl($all_cols) values($all_vals)");
       // echo "insert into $tbl($all_cols) values($all_vals)";
        //}
        //exit();
        ?>
        <script>
        	alert("data berhasil di inputkan");
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
    	//cols
    	$i=0;
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
	    	if (!$meta) {
    		    echo "No information available<br />\n";
	    	}else{	    	
	    		if($meta->name!=$pk){
	    			$inp="";
	    			switch($meta->name)
	    			{
	    				case "id_ujian":
	    					$inp = "<input required type='hidden' name='".$meta->name."' id='".$meta->name."' value='".$_GET['id']."'>";
	    					$name="";
	    					break;
						case "id_pengawas":
	    					$inp = "<input required type='hidden' name='".$meta->name."' id='".$meta->name."' value='".$_SESSION['sess_id']."'>";
	    					$name="";
	    					break;
						case "isi":
	    					$inp = "<textarea name='".$meta->name."' id='".$meta->name."'></textarea>";
	    					$name="Isi";
	    					break;
						case "tanggal":
	    					 $inp = "<input required type='date' name='".$meta->name."' id='".$meta->name."' value='".date('Y-m-d')."'>";
	    					$name="Tanggal";
	    					break;
	    				default:	
			    			$inp = "<input required type='text' name='".$meta->name."' id='".$meta->name."'>";
							$name=$meta->name;   	    					
	    			}

    	    		echo "<tr> 
    	    			<th scope=\"col\">".str_replace("_"," ",strtoupper($name))."</th>
	    	    		<td>
	    	    		$inp
	    	    		</td>
    		    	</tr>";        	
    	    	}
        	}
	    	$i++;
        }
        ?>
        
  </thead>
    <tbody>
    	<td colspan='2' align='right'>
    	<input type='button' class='button' name='batal' value='Batal' onclick="window.location='index.php?m=<?php echo $_GET['m']?>'">
    	<input type='submit' class='button' name='simpan' value='Simpan'></td>
    </body>
</table>
</form>
	<?php
	}
?>			