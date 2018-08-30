<?php
	if(!empty($_GET['m']) and $_GET['m']=='ujian'  and !empty($_GET['op']) and $_GET['op']=='add'){		
	?>
	<div id="smallright">
	<h2 align='left'>Tambah Data Ujian </h2>
	
	<?php
	$tbl = "ujian";
	$col = "*";
	$pk = "id_ujian";
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
					case $pk:
							$name = $meta->name;
	 		   				$inp = "[auto]<input type='hidden' required name='".$meta->name."' id='".$meta->name."'>";	    					
	    					break;
					case "password":
							$name = $meta->name;
	    					$inp = "<input type='password' required value='' name='".$meta->name."'>";	
	    					break;
							
					case "tanggal":
							$name = $meta->name;
	 			    			$inp = "<input type='date' required name='".$meta->name."' id='".$meta->name."'>";	  	
	    					break;
	    			case "waktu":
							$name = $meta->name;
	 			    			$inp = "<input type='time' required name='".$meta->name."' id='".$meta->name."'>";	  	
	    					break;
					case "mata_kuliah":
							$inp = "<select required  style='width:200px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Pilih </option>";
							$q = mysql_query("select * from mata_kuliah where id_dosen='".$_SESSION['sess_id']."'");
							while($f = mysql_fetch_array($q))
							{
								$inp .= "<option value='$f[mata_kuliah] $f[lokal]'> $f[mata_kuliah] $f[lokal] </option>";
							}
							$inp .= "</select>";
							$name = "MATA KULIAH";
							break;
					case "pengawas":
							$inp = "<select style='width:200px;'  class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Boleh Kosong </option>";
							$q = mysql_query("select * from pengawas");
							while($f = mysql_fetch_array($q))
							{
								$inp .= "<option value='$f[id_pengawas]'> $f[nama] : $f[username] </option>";
							}
							$inp .= "</select>";
							$name = "PENGAWAS";
							break;
					case "jenis_ujian":
							$inp = "<select required style='width:200px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value='kuis_1'> KUIS 1 </option>";
							$inp .= "<option value='kuis_2'> KUIS 2 </option>";
							$inp .= "<option value='uts'> UTS</option>";
							$inp .= "<option value='kuis_3'> KUIS 3</option>";
							$inp .= "<option value='kuis_4'> KUIS 4</option>";
							$inp .= "<option value='uas'> UAS</option>";
							$inp .= "</select>";
							$name = "JENIS UJIAN";
							break;
					case "durasi":
							$inp = "<select required style='width:100px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value='1800'> 30 Menit </option>";
							$inp .= "<option value='2700'> 45 Menit </option>";
							$inp .= "<option value='3600'> 60 Menit </option>";
							$inp .= "<option value='4500'> 75 menit</option>";
							$inp .= "<option value='5400'> 90 menit</option>";
							$inp .= "<option value='7200'> 120 menit</option>";
							$inp .= "</select>";
							$name = "DURASI";
							break;
					case "sifat":
							$inp = "<select required  style='width:200px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value='Close Book'> Close Book </option>";
							$inp .= "<option value='Open Book'> Open Book </option>";
							$inp .= "</select>";
							$name = "SIFAT UJIAN";
							break;
					case "id_dosen":
						$name = "";
	    					$inp="<input type='hidden' name='".$meta->name."' id='".$meta->name."' value='".$_SESSION['sess_id']."'>";
	    					break;
						
	  	    		case "keterangan":
							$name = $meta->name;
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
</div>
	<?php
	}
?>			