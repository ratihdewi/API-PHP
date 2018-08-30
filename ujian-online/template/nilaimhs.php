<?php
	if(!empty($_GET['m']) and $_GET['m']=='nilaimhs'  and empty($_GET['op']) and !empty($_SESSION['sess_login'])){
		
	?>	
	<div id="smallRight">
	<h2 align='center'>Nilai</h2>	
	<?php
	if(!empty($_GET['pg'])){
		$stpg = $_GET['pg'];
	}else{
		$stpg = 0;
	}
	
	if($_SESSION['sess_level']=='mahasiswa')	
	{
		$wh = " and id_mahasiswa = '".$_SESSION['sess_id']."'";
		//$wh = "";
	}else{
		$wh = "";
	}
	
	$tbl = "(
	select xi.*,a.cosim, '' nilai_akhir from (select b.id_mahasiswa, b.username, c.id_soal, c.soal, 		
	d.jawaban, c.bobot as bobot_soal 
	from  mahasiswa b, soal c, jawaban d
	 where c.id_soal = d.id_soal  
	 and b.id_mahasiswa = d.id_mahasiswa
	 and c.id_ujian = '".$_GET['id']."') xi left join nilai a on (a.id_soal = xi.id_soal and  a.id_mahasiswa = xi.id_mahasiswa)
	 ) x";
	
	//$tbl = "nilai";
	$col = "*";	
	$pk = "id_nilai";
	$sqlAll = "select $col from $tbl where 1=1 $wh and jawaban <> '' order by id_soal asc";
	$queryAll = mysql_query($sqlAll);
	$sql = "select $col from $tbl where 1=1 $wh and jawaban <> '' order by id_soal asc limit $stpg,10";
	$query = mysql_query($sql);
	//echo $sql;
	?>
<!-- table-->

<table class="data">
   <thead class="data">
    	<tr class="data">
    	<th  class="data">NO</th>
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
        if($_SESSION['sess_level']!='mahasiswa')	
		{
        ?> 
       <!-- <th class="data"></th> -->
        <?php
        }
        ?>
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
    	$tot=0;
    	while($rows = mysql_fetch_array($query)){
    		echo "<tr class='data'>";
    		echo "<td class='data'>$no</td>";
    		$i=0;
    		while ($i < mysql_num_fields($query)) {
    			$meta = mysql_fetch_field($query,$i);
		    	if (!$meta) {
    			    echo "No information available<br />\n";
	    		}else{	   
	    			if($meta->name==$pk ){
	    			echo "";
		    		}else{		    
		    			switch($meta->name)		
		    			{
		    				case "bobot":
		    				$val = $rows[$meta->name]." %";
		    				break;
		    				case "cosim":
								if($rows[$meta->name]<=0.2)
								{
									$valcos = 0;
								}elseif($rows[$meta->name]>0.2 and $rows[$meta->name]<=0.7){
									$valcos = 0.6;
								}elseif($rows[$meta->name]>0.7){
									$valcos = 1;
								}
		    					$val = $valcos;
		    				break;
		    				case "nilai_akhir":
								//$nil = $rows['cosim']*$rows['bobot_soal'];								
								$nil = $valcos*$rows['bobot_soal'];
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
    		<td colspan='8' style='text-align:right;'><b>Total Nilai</b></td>
    		<td><b><?php 
			
			$jlhTot = mysql_fetch_array(mysql_query("select distinct nilai_jumlah from nilai where id_soal in (select id_soal from soal where id_ujian = '".$_GET['id']."')"));
			$allTot = $tot+$jlhTot['nilai_jumlah'];
			if($allTot>=100)
			{
				$totx=100;
			}else{
				$totx=$allTot;
			}
			echo $totx; 
			
			?></b></td>
    	</tr>	
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