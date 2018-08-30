<?php
	if(!empty($_GET['m']) and $_GET['m']=='pengawas'  and !empty($_GET['op']) and $_GET['op']=='import'){		
	?>
	<div id="smallRight">
	<h2 align='center'>Tambah Data Dosen</h2>
	<?php
	$tbl = "pengawas";
	$col = "*";
	$pk = "id_pengawas";
	$sql = "select $col from $tbl";
	$query = mysql_query($sql);
	
	//insert to database
	error_reporting(E_ERROR | E_PARSE);
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
		     
	}
	else if(isset($_POST['upload']))
	{

	$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
	$baris = $data->rowcount($sheet_index=0);
	$sukses = 0;
	$gagal = 0;
	for ($i=2; $i<=$baris; $i++) {
	  $no="";
      $level      = $data->val($i, 1);
      $username  = $data->val($i, 2);
      $password = md5($data->val($i, 3));
	  $nama  	= $data->val($i, 4);
	  $email 	= $data->val($i, 5);
 
 /*Query SQL*/
		$insert = "INSERT into pengawas(id_pengawas,id_level,username,password,nama,email)values('$no','$level','$username','$password','$nama','$email')";
		$hasil = mysql_query($insert);
		
	if($hasil) $sukses++;
	 else $gagal++;
	}
	?>
	 <script>
        	alert("data berhasil di import");
        	window.location='index.php?m=<?php echo $_GET['m'] ?>';
     </script>

	<?php
	}
	?>
<!-- table-->	

 <form method="post" enctype="multipart/form-data" name="upload" action="">
<table id="box-table-a" summary="Meeting Results">
   <thead>
    <fieldset>
                    <label>Silakan Pilih File Excel: </label>
                        <input name="userfile" type="file" class="btn">                        
                        <input type="submit" class="button" name="upload" value="import" onclick="return confirm('Apakah Anda yakin dengan data ini?')"  class="btn btn-primary" />
						<input type='button' class='button' name='batal' value='Batal' onclick="window.location='index.php?m=<?php echo $_GET['m']?>'">
	</fieldset>
    	   	
    	
        
  </thead>
    <tbody>
    	<td colspan='2' align='right'>
    	
    </body>
</table>
</form>
</div>
	<?php
	}
?>			