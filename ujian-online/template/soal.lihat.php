<?php
	if(!empty($_GET['m']) and $_GET['m']=='soal'  and !empty($_GET['op']) and $_GET['op']=='lihat'){
	?>
	<div id="smallRight">
	<h2 align='center'>Data soal</h2>	
	<?php
	if(!empty($_GET['pg'])){
		$stpg = $_GET['pg'];
	}else{
		$stpg = 0;
	}
	
	$tbl = "( select a.id_soal, b.nama as tipe_soal, a.no_soal,a.soal, a.jawaban, a.bobot from soal a, tipe_soal b where a.id_tipe = b.id_tipe and a.id_ujian = '".$_GET['id']."') x";
	$col = "*";	
	$pk = "id_soal";
	$sqlAll = "select $col from $tbl order by id_soal desc";
	$queryAll = mysql_query($sqlAll);
	$sql = "select $col from $tbl order by id_soal desc limit $stpg,10";
	$query = mysql_query($sql);
	?>
<!-- table-->

<a href='index.php?m=soal&op=input&id=<?php echo $_GET['id']?>'>Tambah Data</a>	

<table id="box-table-a" summary="Meeting Results" class="data">
   <thead class="data">
    	<tr class="data">
    	<th class="data">NO</th>
    	<?php
    	//cols
    	$i=0;
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
	    	if (!$meta) {
    		    echo "No information available<br />\n";
	    	}else{	    	
	    		if($meta->name==$pk){
	    		echo "";
	    		}else{	    		
    	    	echo "<th class=\"data\">".str_replace("_"," ",strtoupper($meta->name))."</th>";        	
    	    	}
        	}
	    	$i++;
        }
        ?>
        <th class="data"></th>
        <th class="data"></th>
        </tr>
  </thead>
    <tbody>
    	<?php
    	//rows
    	$no=1;
    	if(empty($_GET['pg'])){
    		$no = 1;
    	}else{
    		$no = $_GET['pg']+1;
    	}
    	while($rows = mysql_fetch_array($query)){
    		echo "<tr class='data'>";
    		echo "<td class='data'>$no</td>";
    		$i=0;
    		while ($i < mysql_num_fields($query)) {
    			$meta = mysql_fetch_field($query,$i);
		    	if (!$meta) {
    			    echo "No information available<br />\n";
	    		}else{	   
	    			if($meta->name==$pk){
	    			echo "";
		    		}else{		    
		    			switch($meta->name)		
		    			{
		    				case "bobot":
		    				$val = $rows[$meta->name]." %";
		    				break;
		    				default:
		    				$val = $rows[$meta->name];
		    			}
    		    	echo "<td class='data'>".$val."</td>";           	
    		    	} 	
    	    		    	
	        	}	        	
		    	$i++;
		    	 
        	}
        
        	echo "
        		<td class='data'><a href='index.php?m=".$_GET['m']."&op=edit&id=".$rows[$pk]."'>Edit</a></td>
        		<td class='data'><a href='index.php?m=".$_GET['m']."&op=hapus&id=".$rows[$pk]."'>Hapus</a></td>
        		";
        	
    		echo "</tr>"; 
    	$no++;   		
    	}
    	?>    
    		
    </tbody>
</table>
	
	<?php
	$div = mysql_num_rows($queryAll)/10;
	$mod = mysql_num_rows($queryAll)%10;
	if($mod > 0){
		$jlh = $div+1;
	}else{
		$jlh = $div;
	}
	echo "<div class='page'>";
	for($i=1;$i<=$jlh;$i++)
	{
		$pages = ($i-1)*10;
		echo "<a href='index.php?m=".$_GET['m']."&pg=$pages'>".$i."</a> 	";
	}
	
	
	?>
	<?php
	
	echo "</div> ";
	}
?>			
</div>