<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=export.xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
	mysql_connect("localhost","root","");
	mysql_select_db("ujian_online");
	
	?>
	LAPORAN
	
	<?php
		$wh = "";
	 
	 $mhs = mysql_query("select * from peserta where id_ujian = '".$_GET['id_ujian']."'");
	 while($fmhs = mysql_fetch_array($mhs))
	 {
	 
	 $tbl = "(
	select b.id_mahasiswa, c.id_soal, c.soal, 		
	d.jawaban, a.cosim, c.bobot as bobot_soal , '' nilai_akhir
	from nilai a, mahasiswa b, soal c, jawaban d
	 where a.id_mahasiswa = b.id_mahasiswa 
	 and a.id_soal = c.id_soal 
	 and c.id_soal = d.id_soal 
	 and a.id_soal = d.id_soal
	 and b.id_mahasiswa = d.id_mahasiswa
	 and a.id_mahasiswa =  d.id_mahasiswa
	 and c.id_ujian = '".$_GET['id_ujian']."'
	 and a.id_mahasiswa = '".$fmhs['id_mahasiswa']."'
	 ) x";
	 
	$col = "*";	
	$pk = "id_nilai";
	$sqlAll = "select $col from $tbl where 1=1 $wh order by id_soal asc";
	$queryAll = mysql_query($sqlAll);
	$sql = "select $col from $tbl where 1=1 $wh order by id_soal asc ";
	$query = mysql_query($sql);
	//echo $sql;
	
	$tot=0;
	
	?>
<!-- table-->
<?php
	$dtx = mysql_fetch_array($query);
	$nama=mysql_fetch_array(mysql_query("select * from mahasiswa where id_mahasiswa = '".$fmhs['id_mahasiswa']."'"));
	echo "<table border='0'>";
	echo "<tr><td>NIM </td><td>: ".$nama['id_mahasiswa']."<td></tr>";
	echo "<tr><td>NAMA </td><td>: ".$nama['nama']."<td></tr>";
	echo "<tr><td>USERNAME </td><td>: ".$nama['username']."<td></tr>";
	echo "</table>";
?>
<table class="data"  border='1'>
   <thead class="data">
    	<tr class="data">
    	<th  class="data">NO</th>
    	<?php
    	//cols
    	$query = mysql_query($sql);
    	$i=0;
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
	    	if (!$meta) {
    		    echo "No information available<br />\n";
	    	}else{	    	
	    		if($meta->name!=$pk and $meta->name!="id_mahasiswa"){
    	    	echo "<th class=\"data\">".str_replace("_"," ",strtoupper($meta->name))."</th>";        	
    	    	}
        	}
	    	$i++;
        }
       
        ?>
        </tr>
  </thead>
    <tbody>
    	<?php
    	//rows
    	$no=1;
    	
    	while($rows = mysql_fetch_array($query)){
    		echo "<tr class='data'>";
    		echo "<td class='data'>$no</td>";
    		$i=0;
    		while ($i < mysql_num_fields($query)) {
    			$meta = mysql_fetch_field($query,$i);
		    	if (!$meta) {
    			    echo "No information available<br />\n";
	    		}else{	   
					if($meta->name!=$pk and $meta->name!="id_mahasiswa"){
		    			switch($meta->name)		
		    			{
		    				case "bobot":
		    					$val = $rows[$meta->name]." %";
		    				break;
		    				case "nilai_akhir":
								$nil = $rows['cosim']*$rows['bobot_soal'];
			    				$val = round($nil,2);
			    				$tot=$tot+$val;
		    				break;
		    				default:
			    				$val = $rows[$meta->name];
		    			}
    		    	echo "<td class='data'>".$val."</td>";           	
    		    	} 	
    	    		    	
	        	}	        	
		    	$i++;
		    	 
        	}
	       	
	       	 
    		echo "</tr>"; 
    	$no++;   		
    	}
    	?>    
    	<tr>
    		<td colspan='6' style='text-align:right;'><b>Total Nilai</b></td>
    		<td><b><?php echo $tot; ?></b></td>
    	</tr>
    		
    </tbody>
</table>
	<?php
	}
?>			