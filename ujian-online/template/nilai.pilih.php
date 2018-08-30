<?php
	if(!empty($_GET['m']) and $_GET['m']=='nilai'  and !empty($_GET['op']) and $_GET['op']=='pilih' and !empty($_SESSION['sess_login'])){
	?>
	<div id="smallRight">
	<h2 align='center'>Data Nilai Per Ujian</h2>	
	<?php
	if(!empty($_GET['pg'])){
		$stpg = $_GET['pg'];
	}else{
		$stpg = 0;
	}
	if($_SESSION['sess_level']=="dosen")
	{
		$wh = " and id_dosen = '".$_SESSION['sess_id']."'";
	}else{
		$wh = "";
	}
	
	$tbl = "ujian";
	$col = "*";	
	$pk = "id_ujian";
	$sqlAll = "select $col from $tbl where 1=1 ".$wh."  order by id_ujian desc";
	$queryAll = mysql_query($sqlAll);
	$sql = "select id_ujian,jenis_ujian,mata_kuliah from $tbl where 1=1 ".$wh."  order by id_ujian desc limit $stpg,10";
	$query = mysql_query($sql);
	//echo $sql;
	?>
<!-- table-->


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
        		<td class='data'><a href='index.php?m=".$_GET['m']."&id=".$rows[$pk]."'><img src='mos-css/img/detail.png'></a></td>
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