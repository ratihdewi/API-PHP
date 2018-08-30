<?php
	if(!empty($_GET['m']) and $_GET['m']=='ujianmhs'  and $_GET['op']=='send' and !empty($_SESSION['sess_login'])){	
	 
		mysql_query("delete from sla_matrix where id_mahasiswa = '".$_SESSION['sess_id']."' and id_soal in (select id_soal from soal where id_ujian ='".$_POST['id_ujian']."')");
		mysql_query("delete from jawaban where id_mahasiswa = '".$_SESSION['sess_id']."'  and id_soal in (select id_soal from soal where id_ujian ='".$_POST['id_ujian']."')");
		//mysql_query("delete from sla_matrix where id_mahasiswa = '".$_SESSION['sess_id']."'");
		
		$soal = mysql_query("select * from soal  where id_ujian = '".$_POST['id_ujian']."'  order by 1 asc");
		while($fsoal = mysql_fetch_array($soal))
		{
			
			steming();
			
			$jwb = "id_soal_".$fsoal['id_soal'];
			if(!empty($_POST[$jwb])){
			mysql_query("
				insert into jawaban values(
					'',
					'".$_SESSION['sess_id']."',
					'".$fsoal['id_soal']."',
					'".$_POST[$jwb]."',
					'0')");
			}		
					
		}
		steming_jawaban_mhs();
		sla();
		
		?>
		<script>
			alert('Jawaban Berhasil di Terima');
			window.location='index.php?m=nilaimhs&op=pilih';
		</script>
		<?php
	
	}
?>			
