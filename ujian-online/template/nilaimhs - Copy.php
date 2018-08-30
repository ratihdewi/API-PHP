<?php
	if(!empty($_GET['m']) and $_GET['m']=='nilaimhsx'  and empty($_GET['op']) and !empty($_SESSION['sess_login'])){
		
		
		$soalxx = mysql_query("select distinct id_soal from sla_matrix where q_or_d = 'q' and id_soal in ( select id_soal from soal where id_ujian = '".$_GET['id']."') order by id_soal asc");		
		mysql_query("delete from nilai where id_soal in (select id_soal from ujian where id_ujian ='".$_GET['id']."')");

		while($fsoal = mysql_fetch_array($soalxx)){
		
		$arr = array();
		$dtMtxQ=array();
		$mtx_q = mysql_query("select distinct qs,frekuensi,id_soal from sla_matrix where q_or_d = 'q' and id_soal ='".$fsoal['id_soal']."'  order by id_sla_matrix asc");		
		$xq = 0;	 
		
		while($dtq = mysql_fetch_array($mtx_q))
		{								
			$dtMtxQ[$xq]=array();			
			$mtx_d= mysql_query("select distinct a.id_mahasiswa
				from sla_matrix a where a.qs = '".$dtq['qs']."'  and id_soal ='".$fsoal['id_soal']."'
				and q_or_d = 'd' order by a.id_mahasiswa asc");	
				
			while($fmtx_d = mysql_fetch_array($mtx_d))
			{
				$fk = mysql_fetch_array(mysql_query("select frekuensi from sla_matrix where qs='".$dtq['qs']."' 
				and id_soal ='".$fsoal['id_soal']."' and id_mahasiswa = '".$fmtx_d['id_mahasiswa']."' and q_or_d = 'd' order by id_sla_matrix asc"));
				array_push($dtMtxQ[$xq],$fk['frekuensi']);
			}
			array_push($arr,$dtMtxQ[$xq]);
			$xq++;
		}
		
		//print_r($arr);
		//echo "<br>";
		/*
		$sumArray = array();		
		for($y=0;$y<count($arr);$y++)
		{		
			$sum=0;
			for($xy=0;$xy<count($arr[$y]);$xy++){
				$sum = $sum+$arr[$y][$xy];				
			}
			//array_push($arr[$y],$sum );
		}	
		*/
		
		$mtx = new Matrix($arr); 	
		//echo "ID SOAL = ".$fsoal['id_soal']."<br>";	
		//print_r($mtx);
		//GET SVD
		$svd = $mtx->svd(); 

		//AMBIL DARI KUNCI JAWABAN
		
		$kunciTfIdf[0] = array();
		$hslx= mysql_query("select * from sla_matrix where id_mahasiswa = '0'  and id_soal ='".$fsoal['id_soal']."'");
		while($fhslx = mysql_fetch_array($hslx))
		{
			array_push($kunciTfIdf[0],$fhslx['frekuensi']);
		}	
		
		//AMBIL DARI JAWABAN 
		// and id_mahasiswa='".$_SESSION['sess_id']."'
		$hsl = mysql_query("select * from jawaban  where id_soal ='".$fsoal['id_soal']."'  order by id_mahasiswa asc");
		//echo "select * from jawaban  where id_soal ='".$fsoal['id_soal']."'  and id_mahasiswa='".$_SESSION['sess_id']."' order by id_mahasiswa asc<br>";
		$idjwb = 1;
		$paramDok = 0;
		while($dtHsl =mysql_fetch_array($hsl)){
			$arrTfIdf[0] = array();
			$hslx= mysql_query("select * from sla_matrix where id_mahasiswa = '".$dtHsl['id_mahasiswa']."'  and id_soal ='".$fsoal['id_soal']."' and id_jawaban = '".$dtHsl['id_jawaban']."'");					
			// echo "select * from sla_matrix where id_mahasiswa = '".$dtHsl['id_mahasiswa']."'  and id_soal ='".$fsoal['id_soal']."' and id_jawaban = '".$dtHsl['id_jawaban']."'<br>";
			while($fhslx = mysql_fetch_array($hslx))
			{
				array_push($arrTfIdf[0],$fhslx['frekuensi']);
			}	
			
			
			//reduce U;
			$redU = $svd->getU();
			$redUdo = $redU->A;
			$redUOK=array();
			for($rowu=0;$rowu<count($redUdo);$rowu++)
			{
				$redCU=array();
				for($colu=0;$colu<2;$colu++)
				{
					if($colu==1){
						$nilai = 1*$redUdo[$rowu][$colu];
					}else{
						$nilai = $redUdo[$rowu][$colu];
					}
					array_push($redCU,$nilai);					
				}
				array_push($redUOK,$redCU);
			}			
			//echo "red U";
			//print_r($redUOK);
			
			
			//reduce S;
			$redS = $svd->getS();
			$redSdo = $redS->A;
			$redSOK=array();
			for($rows=0;$rows<2;$rows++)
			{
				$redCS=array();
				for($cols=0;$cols<2;$cols++)
				{
					array_push($redCS,$redSdo[$rows][$cols]);					
				}
				array_push($redSOK,$redCS);
			}			
			//echo "red S";
			//print_r($redS);
			
			
			//reduce V; 
			$redV = $svd->getV();
			//echo "<!-- V -->";
			//print_r($redV);
						
			$redVdo = $redV->A;
			$redVOK=array();
			for($rows=0;$rows<count($redVdo[$rows]);$rows++)
			{
				$redCV=array();
				for($cols=0;$cols<2;$cols++)
				{
					array_push($redCV,$redVdo[$rows][$cols]);					
				}
				array_push($redVOK,$redCV);
			}			
			$mtxV = new Matrix($redVOK);
			//$trV = $mtxV->transpose();
			//echo "<!--red V-->";
			//print_r($mtxV);
			
			//getRow by Row 
			$redRV = $mtxV;			
			$redRVdo = $redRV->A;	
			$redRVOK=array();			
			$redCRV=array();
			for($cols=0;$cols<count($redRVdo[$paramDok]);$cols++)
			{
				array_push($redCRV,$redRVdo[$paramDok][$cols]);					
			}
			array_push($redRVOK,$redCRV);
			 
			$tfidf = new Matrix($arrTfIdf);
			$uMatrix = new Matrix($redUOK);
			$sMatrix = new Matrix($redSOK);
			
			//echo "<!-- U -->";
			//print_r($uMatrix);
			//echo "<!-- S -->";
			//print_r($sMatrix);
			
			$Qtfidf = new Matrix($kunciTfIdf); 
			//echo "<!-- Key Q -->";
			//print_r($Qtfidf);
			
			$qa = $Qtfidf->times($uMatrix);
			$qb = $qa->times($sMatrix);			 			
 			//print_r($qb);
 			$bdok = new Matrix($redRVOK); 
 			//echo "<!-- Vrow-->";			
 			//print_r($bdok);
 			 
			//echo "-----------------------";
			$bobot = cosim($qb,$bdok);
			//insert to Nilai
			if($fsoal['id_soal']==$dtHsl['id_soal']){
				mysql_query("insert into nilai values('','".$dtHsl['id_mahasiswa']."','".$dtHsl['id_soal']."','".$bobot."')");
			//	echo "insert into nilai values('','".$dtHsl['id_mahasiswa']."','".$dtHsl['id_soal']."','".$bobot."')<br>";
				
			}
			$idjwb++;	
			
			$paramDok++;
		}
		
//			exit();
	}
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
	select a.id_mahasiswa, b.username, c.id_soal, c.soal, 		
	d.jawaban, a.cosim, c.bobot as bobot_soal , '' nilai_akhir
	from nilai a, mahasiswa b, soal c, jawaban d
	 where a.id_mahasiswa = b.id_mahasiswa 
	 and a.id_soal = c.id_soal 
	 and c.id_soal = d.id_soal 
	 and a.id_soal = d.id_soal
	 and b.id_mahasiswa = d.id_mahasiswa
	 and a.id_mahasiswa =  d.id_mahasiswa
	 and c.id_ujian = '".$_GET['id']."') x";
	
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
    		<td colspan='8' style='text-align:right;'><b>Total Nilai</b></td>
    		<td><b><?php echo $tot; ?></b></td>
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