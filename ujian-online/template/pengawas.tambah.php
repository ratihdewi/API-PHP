<?php
	if(!empty($_GET['m']) and $_GET['m']=='pengawas'  and !empty($_GET['op']) and $_GET['op']=='add'){		
	?>
	<div id=smallRight>
	<h2 align='center'>Tambah <?php echo $_GET['m'] ?> </h2>
	
	<?php
	$tbl = "pengawas";
	$col = "*";
	$pk = "id_pengawas";
	$sql = "select $col from $tbl";
	$query = mysql_query($sql);
	
	//insert to database
	if(isset($_POST['simpan'])){
		$i=0;
		$cols="";
		$vals="";
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
			$cols .= $meta->name.",";
			switch($meta->name)			
			{
				case "password":
					$vals .= "'".md5($_POST[$meta->name])."',";  
				break;
				case "gambar";
					$vals .= "'$kode".$_FILES['gambar']['name']."',";   
					break;									    	
				default:
					$vals .= "'".$_POST[$meta->name]."',";    				    	
			}   	    	
			  				    	
	    	$i++;
        }
        $all_cols = substr($cols,0,strlen($cols)-1);
        $all_vals = substr($vals,0,strlen($vals)-1);        
        mysql_query("insert into $tbl($all_cols) values($all_vals)");
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
	    		if(strtoupper($meta->name)=='MARKER'){
	    			echo "<tr> 
    	    			<th scope=\"col\">".str_replace("_"," ",strtoupper($meta->name))."</th>
	    	    		<td><input type='hidden' name='".$meta->name."' id='".$meta->name."' value='home.png'>
	    	    		<img src='images/icon/home.png'></td>
    		    	</tr>"; 
	    		}else{
	    			$inp="";
	    			switch($meta->name)
	    			{
					case "id_pengawas":
							$name = $meta->name;
	    					$inp = "[auto]<input type='hidden' value='".$meta->name."' name='".$meta->name."'>";	
	    					break;
					case "password":
	    					$inp = "<input type='password' value='' name='".$meta->name."'>";
							$name = $meta->name;	
	    					break;
					case "id_level":
	    					$inp="<input type='hidden' name='".$meta->name."' id='".$meta->name."' value='4'>";
							$name = "";
	    					break;
	  	    		case "keterangan":
	    					$inp = "<textarea required name='".$meta->name."' cols='50' rows='5' id='".$meta->name."'></textarea>";
	    					break;
	    				default:	
			    			$inp = "<input required type='text' name='".$meta->name."' id='".$meta->name."'>";	 
							$name = $meta->name;	    					
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
</div>