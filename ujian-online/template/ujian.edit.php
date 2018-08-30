<?php
	if(!empty($_GET['m']) and $_GET['m']=='ujian'  and !empty($_GET['op']) and $_GET['op']=='edit' and !empty($_GET['id']) ){		
	?>
	<div id="smallRight">
	<h2 align='center'>Edit <?php echo $_GET['m'] ?> </h2>
	
	<?php
	$tbl = "ujian";
	$pk = "id_ujian";
	$col = "*";
	$wh = $pk." = '$_GET[id]'";
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
	    				case "tanggal":
							$name = $meta->name;
	 			    			$inp = "<input type='date' value='".$f[$meta->name]."' name='".$meta->name."' id='".$meta->name."'>";	  	
	    					break;
	    			case "waktu":
							$name = $meta->name;
	 			    			$inp = "<input type='time'  value='".$f[$meta->name]."'name='".$meta->name."' id='".$meta->name."'>";	  	
	    					break;
					case "mata_kuliah":
							$inp = "<select  style='width:200px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Pilih </option>";
							$q = mysql_query("select * from mata_kuliah where id_dosen='".$_SESSION['sess_id']."'");
							while($fp = mysql_fetch_array($q))
							{
								if($f[$meta->name]==$fp['mata_kuliah'])
								{
									$s = "selected";
								}else{
									$s = "";
								}
								$inp .= "<option $s value='$fp[mata_kuliah] $fp[lokal]'> $fp[mata_kuliah] $fp[lokal] </option>";
							}
							$inp .= "</select>";
							$name = "MATA KULIAH";
							break;
					case "pengawas":
							$inp = "<select  class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Boleh Kosong </option>";
							$q = mysql_query("select * from pengawas");
							while($fp = mysql_fetch_array($q))
							{
								if($f[$meta->name]==$fp['id_pengawas'])
								{
									$s = "selected";
								}else{
									$s = "";
								}
								$inp .= "<option $s value='$fp[id_pengawas]'> $fp[nama] : $fp[username] </option>";
							}
							$inp .= "</select>";
							$name = "PENGAWAS";
							break;
					case "jenis_ujian":
							$inp = "<select  style='width:200px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
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
							$inp = "<select style='width:100px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
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
							$inp = "<select  style='width:200px;' class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value='Close Book'> Close Book </option>";
							$inp .= "<option value='Open Book'> Open Book </option>";
							$inp .= "</select>";
							$name = "SIFAT UJIAN";
							break;
					case "id_dosen":
						$name = "";
	    					$inp="<input type='hidden' name='".$meta->name."' id='".$meta->name."' value='".$_SESSION['sess_id']."'>";
	    					break;
							
	    					
	    				case "pengawas":
							$inp = "<select  class='chosen-select' name='".$meta->name."' id='".$meta->name."'>";
							$inp .= "<option value=''> Boleh Kosong </option>";
							$q = mysql_query("select * from pengawas");
							while($f = mysql_fetch_array($q))
							{
								$inp .= "<option value='$f[id_pengawas]'> $f[nama] : $f[username] </option>";
							}
							$inp .= "</select>";
							$name = "PENGAWAS";
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
</div>