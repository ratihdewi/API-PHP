<?php
	if(!empty($_GET['m']) and $_GET['m']=='nilai'  and empty($_GET['op']) and !empty($_SESSION['sess_login'])){
	//hitung matrix 
		//reset nilai sebelum di tampilkan hasil SLA		
		//soal		
		if($_SESSION['sess_level']=='dosen'){
			$soalxx = mysql_query("select distinct id_soal from sla_matrix where q_or_d = 'q' and id_soal in ( select id_soal from soal where id_ujian = '".$_GET['id']."') order by id_soal asc");
			//echo "delete from nilai where id_soal in (select id_soal from soal where id_ujian ='".$_GET['id']."')  <br>";		
			mysql_query("delete from nilai where id_soal in (select id_soal from soal where id_ujian ='".$_GET['id']."')  ");
			

		}else{
			$soalxx = mysql_query("select distinct id_soal from sla_matrix where q_or_d = 'q'  and id_soal in ( select b.id_soal from peserta a, ujian b where a.id_ujian = b.id_ujian and b.id_mahasiswa = '".$_SESSION['sess_id']."' and a.id_ujian = '".$_GET['id']."')  order by id_soal asc");				
			mysql_query("delete from nilai where id_soal in (select id_soal from soal where id_ujian ='".$_GET['id']."') and id_mahasiswa = '".$_SESSION['sess_id']."'");
		}
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
				$fk = mysql_fetch_array(mysql_query("select frekuensi from sla_matrix where qs='".$dtq['qs']."'  and id_soal ='".$fsoal['id_soal']."' and id_mahasiswa = '".$fmtx_d['id_mahasiswa']."' and q_or_d = 'd' order by id_sla_matrix asc"));
				array_push($dtMtxQ[$xq],$fk['frekuensi']);
			}
			array_push($arr,$dtMtxQ[$xq]);
			$xq++;
		}
		
		
		$sumArray = array();		
		for($y=0;$y<count($arr);$y++)
		{		
			$sum=0;
			for($xy=0;$xy<count($arr[$y]);$xy++){
				$sum = $sum+$arr[$y][$xy];				
			}
			//array_push($arr[$y],$sum );
		}	
	
		$mtx = new Matrix($arr); 	
		//echo "ID SOAL = ".$fsoal['id_soal'];	
		//print_r($mtx);
		//GET SVD
		$svd = $mtx->svd(); 

		//AMBIL DARI KUNCI JAWABAN
		
		$kunciTfIdf[0] = array();
		$hslx= mysql_query("select * from sla_matrix where id_mahasiswa = '0'  and id_soal ='".$fsoal['id_soal']."'");					//and id_soal = '".$dtq['id_soal']."'
		while($fhslx = mysql_fetch_array($hslx))
		{
			array_push($kunciTfIdf[0],$fhslx['frekuensi']);
		}	
		
		//AMBIL DARI JAWABAN 
		
		$hsl = mysql_query("select * from jawaban  where id_soal ='".$fsoal['id_soal']."' order by id_mahasiswa asc");
		$idjwb = 1;
		$paramDok = 0;
		while($dtHsl =mysql_fetch_array($hsl)){
			$arrTfIdf[0] = array();
			$hslx= mysql_query("select * from sla_matrix where id_mahasiswa = '".$dtHsl['id_mahasiswa']."'  and id_soal ='".$fsoal['id_soal']."' and id_jawaban = '".$dtHsl['id_jawaban']."'");					
			
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
			//print_r($redS);
			
			
			//reduce V; 
			$redV = $svd->getV();
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
			//echo "<!-- V -->";
			
			$mtxV = new Matrix($redVOK);
			//$trV = $mtxV->transpose();
			//echo "<!-- Vt -->";
			//print_r($trV);
			
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
			//print_r($redRVOK);
			
			$tfidf = new Matrix($arrTfIdf);
			$uMatrix = new Matrix($redUOK);
			$sMatrix = new Matrix($redSOK);
			//echo "<!-- Key Q -->";
			//print_r($tfidf);
			//echo "<!-- U -->";
			//print_r($uMatrix);
			//echo "<!-- S -->";
			//print_r($sMatrix);
			
			$Qtfidf = new Matrix($kunciTfIdf); 
			$qa = $Qtfidf->times($uMatrix);
			$qb = $qa->times($sMatrix);			 			
 			//print_r($qb);
 			$bdok = new Matrix($redRVOK); 
 			//echo "<!-- Vrow-->";			
 			//print_r($bdok);
 			 
			//echo "-----------------------";
			$bobot = cosim($qb,$bdok);
			//insert to Nilai
			mysql_query("insert into nilai(id_nilai,id_mahasiswa,id_soal,cosim) values('','".$dtHsl['id_mahasiswa']."','".$dtHsl['id_soal']."','".$bobot."')");
			
			
			$idjwb++;	
			
			$paramDok++;
		}
		
	}
	?>
	<div id="smallRight">
	<h2 align='center'>Nilai</h2>	
	<a href="addons/export.php?id_ujian=<?php echo $_GET['id'] ?>">Print Xls</a> | 
	<a href="index.php?m=nilai&id=<?php echo $_GET['id']; ?>&opx=edit">Edit Nilai</a>
	
	<?php
	
	if(!empty($_GET['upgrade']) and $_GET['upgrade']=='yes')
	{
		mysql_query("update nilai set nilai_jumlah = '".$_POST['nilai_jumlah']."' where id_soal in (select id_soal from soal where id_ujian='".$_GET['id']."')");
		//echo "update nilai set nilai_jumlah = '".$_POST['nilai_jumlah']."' where id_soal in (select id_soal from soal where id_ujian='".$_GET['id']."')";
		//exit();
	}
	
	if(!empty($_GET['opx']) and $_GET['opx']=='edit')
	{
	?>
	<form action="index.php?m=nilai&id=<?php echo $_GET['id']; ?>&upgrade=yes" method="post">
		<h2>Form Edit Nilai</h2>
		Input Nilai <input type="number" style="width:75px;" maxlength="2" name="nilai_jumlah" />
		<input type="submit" name="submit" />
	</form>
	<?php
	}
	?>
	
	<?php
	$dekUjian = mysql_query("select * from berita_acara where id_ujian='".$_GET['id']."'");
			/**/$dxt=mysql_fetch_array($dekUjian);
        		echo "<br><br><div style='float:left;'> <b>Berita Acara : ".$dxt['isi']."</b></div><br><br>";
        	/**/
	
	if($_SESSION['sess_level']=='mahasiswa')	
	{
		$wh = " and id_mahasiswa = '".$_SESSION['sess_id']."'";
	}else{
		$wh = "";
	}
	 
	 $mhs = mysql_query("select * from peserta where id_ujian = '".$_GET['id']."'");
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
	 and c.id_ujian = '".$_GET['id']."'
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
	echo "<table>";
	echo "<tr><td>NIM </td><td>: ".$nama['id_mahasiswa']."<td></tr>";
	echo "<tr><td>NAMA </td><td>: ".$nama['nama']."<td></tr>";
	echo "<tr><td>USERNAME </td><td>: ".$nama['username']."<td></tr>";
	echo "</table>";
?>
<table class="data">
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
							case "cosim":
								if($rows[$meta->name]<=0.2)
								{
									$valcos = 0;
								}elseif($rows[$meta->name]>0.2 and $rows[$meta->name]<=0.7){
									$valcos = 0.6;
								}elseif($rows[$meta->name]>0.7){
									$valcos = 1;
								}
		    					$val = round($rows[$meta->name],5)."(".$valcos.")";
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
    		<td colspan='6' style='text-align:right;'><b>Total Nilai</b></td>
    		<td><b>
			<?php 
			
			$jlhTot = mysql_fetch_array(mysql_query("select distinct nilai_jumlah from nilai where id_soal in (select id_soal from soal where id_ujian = '".$_GET['id']."')"));
			$allTot = $tot+$jlhTot['nilai_jumlah'];
			if($allTot>=100)
			{
				$totx=100;
			}else{
				$totx=$allTot;
			}
			echo $totx; 
			
			?>
			</b></td>
    	</tr>
    		
    </tbody>
</table>
	
	<?php
	}
	
	
	?>
	<?php
	
	echo "</div> ";
	}
?>			
</div>