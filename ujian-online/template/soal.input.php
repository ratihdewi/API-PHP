<?php
	if(!empty($_GET['m']) and $_GET['m']=='soal'  and !empty($_GET['op']) and $_GET['op']=='input'){		
	?>
	<div id="map" style='width:100%;'></div>
	<h2 align='center'>Tambah <?php echo $_GET['m'] ?> </h2>
	
	<?php
	$tbl = "soal";
	$col = "*";
	$pk = "id_soal";
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
	    		if($meta->name!=$pk){
	    			$inp="";
	    			switch($meta->name)
	    			{
	    				
						case "id_ujian":
	 		   				$inp = "[auto]<input type='hidden' name='".$meta->name."' value='".$_GET['id']."'>";	    					
	    					break;
						case "id_tipe":
							$inp = "<select onchange=\"window.location='index.php?m=soal&op=input&id=".$_GET['id']."&tipe='+this.value\" class='chosen-select' style='width:100px;' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Pilih </option>";
							$q = mysql_query("select * from tipe_soal where status='aktif'");
							while($f = mysql_fetch_array($q))
							{							
								if(!empty($_GET['tipe']) and $_GET['tipe']==$f['id_tipe'])
								{
									$s = "selected";
								}else{
									$s = "";
								}
								
								$inp .= "<option $s value='".$f['id_tipe']."'> $f[nama] </option>";
							}
							$inp .= "</select>";
							$name = "tipe soal";
							break; 
						case "id_mata_kuliah":
							$inp = "<select class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Pilih </option>";
							$q = mysql_query("select* from mata_kuliah");
							while($f = mysql_fetch_array($q))
							{
							
								$inp .= "<option value='$f[id_mata_kuliah]'> $f[mata_kuliah] </option>";
							}
							$inp .= "</select>";
							$name = "mata kuliah";
							break; 
	    				case "soal":
	    					$inp = "<textarea required name='".$meta->name."' cols='50' rows='5' id='".$meta->name."'></textarea>";
	    					break;
						case "jawaban":
	    					$inp = "<textarea required name='".$meta->name."' cols='50' rows='5' id='".$meta->name."'></textarea>";
	    					break;
	    				case "no_soal":
	    					if(!empty($_GET['tipe'])){
		    					$nosoal = mysql_fetch_array(mysql_query("select max(no_soal) max from soal where id_ujian = '".$_GET['id']."' and id_tipe='".$_GET['tipe']."'"));
		    					$nextno = $nosoal['max']+1;
	    					}else{
	    						$nextno="";
	    					}
			    			$inp = "<input readonly type='text' name='".$meta->name."' id='".$meta->name."' value='".$nextno."'>";	    						    				
	    					break;
	    				default:	
			    			$inp = "<input required type='text' name='".$meta->name."' id='".$meta->name."'>";	    					
	    			}

    	    		echo "<tr> 
    	    			<th scope=\"col\">".str_replace("_"," ",strtoupper($meta->name))."</th>
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