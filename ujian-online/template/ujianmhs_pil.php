<?php
	if(!empty($_GET['m']) and $_GET['m']=='ujianmhs_pil'  and empty($_GET['op']) and !empty($_SESSION['sess_login'])){		
	?>
	<div id="smallRight">
	<h2 align='center'>Data ujian</h2>	
	<?php
	if(!empty($_GET['pg'])){
		$stpg = $_GET['pg'];
	}else{
		$stpg = 0;
	}
	
	$tbl = "(select a.* from ujian a, peserta b where a.id_ujian = b.id_ujian and b.id_mahasiswa = '".$_SESSION['sess_id']."') x";
	$col = "*";	
	$pk = "id_ujian";
	$sqlAll = "select $col from $tbl order by id_ujian desc";
	$queryAll = mysql_query($sqlAll);
	$sql = "select id_ujian,mata_kuliah,jenis_ujian,tanggal, waktu, durasi from $tbl order by id_ujian desc limit $stpg,10";
	$query = mysql_query($sql);
	?>
<!-- table-->

<table class="data" >
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
        	echo "<td class='data'>";
			 $dekUjian = mysql_query("select * from nilai where id_soal in (select id_soal from soal where id_ujian = '".$rows[$pk]."') ");
		//	 echo "select * from nilai where id_soal in (select id_soal from soal where id_ujian = '".$rows[$pk]."') ";
			if(mysql_num_rows($dekUjian)==0){
        		echo "
        		<a href='index.php?m=ujianmhs&id=".$rows[$pk]."'><img src='mos-css/img/mulai.png'></a>
        		";
        	}
			echo "</td>";
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