<?php
	if(!empty($_GET['m']) and $_GET['m']=='ujianmhs'  and empty($_GET['op']) and !empty($_SESSION['sess_login'])){	
?>

<?php	
	if(isset($_POST['submit']))
	{
		mysql_query("truncate table sla_matrix");
		mysql_query("delete from jawaban where id_mahasiswa = '".$_SESSION['sess_id']."'");
		mysql_query("delete from sla_matrix where id_mahasiswa = '".$_SESSION['sess_id']."'");
		
		$soal = mysql_query("select * from soal where id_ujian = '".$_POST['id_ujian']."' order by 1 asc");
		echo "select * from soal where id_ujian = '".$_POST['id_ujian']."' order by 1 asc<br>";
		while($fsoal = mysql_fetch_array($soal))
		{
			
			steming();
			
			$jwb = "id_soal_".$fsoal['id_soal'];
			
			mysql_query("
				insert into jawaban values(
					'',
					'".$_SESSION['sess_id']."',
					'".$fsoal['id_soal']."',
					'".$_POST[$jwb]."',
					'0')");
					
					
		}
		steming_jawaban_mhs();
		sla();
		
		?>
		<script>
			alert('Jawaban Berhasil di Terima');
			window.location='index.php?m=ujianmhs_pil';
		</script>
		<?php
	}	
	
	?>
	<div id="smallRight">
	<h2 align='center'>Jawab Soal Berikut</h2>	
	
	<?php	 

	
	//set Random
	$cekRand = mysql_fetch_array(mysql_query("select a.id_tipe from soal a, peserta b where a.id_ujian = b.id_ujian and a.id_ujian = '".$_GET['id']."' and b.id_mahasiswa = '".$_SESSION['sess_id']."' and a.id_tipe > 0 order by rand() "));
	$tbl = "(select a.*, c.tanggal, c.waktu,c.durasi from soal a, peserta b , ujian c where a.id_ujian = b.id_ujian and a.id_ujian = c.id_ujian and a.id_ujian = '".$_GET['id']."' and b.id_mahasiswa = '".$_SESSION['sess_id']."' and a.id_tipe = '".$cekRand['id_tipe']."' ) x";
	$col = "id_soal, soal, durasi, bobot";	
	$pk = "id_soal";
	$sql = "select $col from $tbl order by 1 asc";
	$query = mysql_query($sql);
	//echo $sql;
	/*and datediff(c.tanggal,NOW())<=0 and time_format(timediff(c.waktu,'".date('h:i:s')."'),'%s')<=0*/
	?>
<!-- table-->
		<span id="remainnextpage"></span> <font color='#123edf'><center>Waktu Pengerjaan Ujian <span id="remain"></span> lagi</center></font>
		<?php
 		$dtsql= $sql;
 		$tmsoal = 120;
		$fdurasi= mysql_fetch_array(mysql_query($dtsql));
 		?>
		
		<script language="javascript" type="text/javascript">
			
			counter();			
			function counter(){	
				seconds = <?php  echo $fdurasi['durasi']; ?>;	
				i=0;
				countDown();	
			}

			function countDown(){
				i.toString();
				document.getElementById("remain").innerHTML=Math.floor(((seconds/86400)%1)*24) + "Jam :" + Math.floor(((seconds/3600)%1)*60) + "Menit :" + Math.round(((seconds/60)%1)*60) + 'Detik';
				setTimeout("countDown()",1000);
				var sectm = seconds%<?php echo $tmsoal; ?>;
				if(sectm == 0){
					//document.getElementById('submit').click();
				}
				
				if(seconds == 0)
				{
					//document.form1.submit();		
					document.getElementById('kirim').click();					
					//alert('Submit');
				}else {
					seconds--;
				}	
			}
			
		</script>
<form action="index.php?m=ujianmhs&op=send&id=<?php echo $_GET['id'] ?>" method="post" name='form1'>
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
	    		if($meta->name!=$pk){
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
		    				case "soal":
		    				$val = $rows[$meta->name]."<br><textarea name='id_soal_".$rows['id_soal']."' cols='50' rows='5'></textarea>";
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
    		
    </tbody>
</table>
	<input type="hidden" name="id_ujian" value="<?php echo $_GET['id']; ?>">
	<input class="button" type="submit" value="submit" name="submit">
	</form>
	<?php 
	}
?>			
</div>