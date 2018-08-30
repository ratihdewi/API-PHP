<?php
	if(!empty($_GET['m']) and $_GET['m']=='mata_kuliah'  and !empty($_GET['op']) and $_GET['op']=='add'){		
	?>
	<div id="smallRight">
	<h2 align='center'>Tambah Mata Kuliah </h2>
	
	<?php
	$tbl = "mata_kuliah";
	$col = "*";
	$pk = "id_mata_kuliah";
	$sql = "select $col from $tbl";
	$query = mysql_query($sql);
	
	//insert to database
	if(isset($_POST['simpan'])){
		$_SESSION['sqlcari']="";
		$i=0;
		$cols="";
		$vals="";
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
			$cols .= $meta->name.",";	    	    	
			$vals .= "'".$_POST[$meta->name]."',";    				    	
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
	    				 case $pk:
							$name = $meta->name;	
	 		   				$inp = "[auto]<input type='hidden' name='".$meta->name."' id='".$meta->name."'>";	    					
	    					break;
						case "id_dosen":
							$inp = "<select style='width:100px;'  class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Pilih </option>";
							$q = mysql_query("select * from dosen");
							while($f = mysql_fetch_array($q))
							{
								$inp .= "<option value='$f[id_dosen]'> $f[nama]</option>";
							}
							$inp .= "</select>";
							$name = "DOSEN";
							break;

						case "sks":
							$inp = "<select style='width:100px;'  class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value='2'> 2 SKS </option>";
							$inp .= "<option value='3'> 3 SKS </option>";
							$inp .= "<option value='4'> 4 SKS </option>";
							$inp .= "<option value='6'> 6 SKS </option>";
							$inp .= "</select>";
							$name ="SKS";
							break;
						case "lokal":
							$inp = "<select  class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value='A'> A </option>";
							$inp .= "<option value='B'> B </option>";
							$inp .= "<option value='C'> C </option>";
							$inp .= "<option value='D'> D </option>";
							$inp .= "<option value='E'> E </option>";
							$inp .= "<option value='F'> F </option>";
							$inp .= "<option value='G'> G </option>";
							$inp .= "<option value='H'> H </option>";
							$inp .= "</select>";
							$name ="LOKAL";
							break;	
						
	    				case "keterangan":
	    					$inp = "<textarea required name='".$meta->name."' cols='50' rows='5' id='".$meta->name."'></textarea>";
	    					break;
	    				default:
							$name = $meta->name;	
			    			$inp = "<input required type='text' name='".$meta->name."' id='".$meta->name."'>";	    					
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