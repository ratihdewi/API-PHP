<?php
include "addons/JAMA/Matrix.php";

function count_kata($q_or_d,$idsoal,$kd,$idjwb=null)
{
	if($q_or_d=='q')
	{
		$jlh = mysql_num_rows(mysql_query("select * from steming_soal where kata_dasar = '".$kd."' and id_soal='".$idsoal."'"));		
	}else{
		$jlh = mysql_num_rows(mysql_query("select * from steming_jawaban where kata_dasar = '".$kd."' and id_jawaban = '".$idjwb."' and id_mahasiswa = '".$_SESSION['sess_id']."' and id_soal='".$idsoal."'"));				
	}
	return $jlh;
}


function sla()
{
	mysql_query("delete from sla_matrix where id_mahasiswa = '".$_SESSION['sess_id']."' and id_soal in (select distinct id_soal from soal where id_ujian ='".$_GET['id']."')");
	mysql_query("delete from sla_matrix where id_mahasiswa = '0' and id_soal in (select id_soal from soal where id_ujian ='".$_GET['id']."')");
	
	
	//hitung q
	//cek idsoal
	$soalx = mysql_query("select * from soal where id_ujian = '".$_GET['id']."'");	
	while($fsoalx = mysql_fetch_array($soalx)){
		$stem = mysql_query("		
		select distinct kata_dasar, id_soal from (
			select distinct kata_dasar, id_soal from steming_soal x  where id_soal = '".$fsoalx['id_soal']."'
				union
			select distinct kata_dasar, id_soal from steming_jawaban y where id_soal = '".$fsoalx['id_soal']."'
		) xy
		");
	
		while($fstem = mysql_fetch_array($stem))
		{					
			mysql_query("insert into sla_matrix(qs,id_mahasiswa,id_soal,q_or_d,frekuensi) values('".$fstem['kata_dasar']."','0','".$fstem['id_soal']."','q','".count_kata("q",$fstem['id_soal'],$fstem['kata_dasar'])."')");
		}	
		
	}
	
	//exit();
	
	//hitung soal masing-masing soal dibuat baris
	$soalx = mysql_query("select * from soal where id_ujian = '".$_GET['id']."'");	
	while($fsoalx = mysql_fetch_array($soalx)){

	$j = mysql_query("select a.* from jawaban a, soal b where a.id_soal = b.id_soal and b.id_ujian = '".$_GET['id']."' and a.id_soal = '".$fsoalx['id_soal']."' order by 1 asc");
	while($fJ = mysql_fetch_array($j))
	{
		$stem = mysql_query("		
		select distinct kata_dasar,id_soal from (
			select distinct kata_dasar,id_soal from steming_soal x where id_soal = '".$fsoalx['id_soal']."'
				union
			select distinct kata_dasar,id_soal from steming_jawaban y  where id_soal = '".$fsoalx['id_soal']."'
		) xy
		");
		while($fstem = mysql_fetch_array($stem))
		{	
			//cek SLA is existed?
			$csla = mysql_query("select * from sla_matrix 
				where qs='".$fstem['kata_dasar']."' and 
			 	id_mahasiswa = '".$fJ['id_mahasiswa']."' and 
				id_soal='".$fstem['id_soal']."' and 
				id_jawaban = '".$fJ['id_jawaban']."' and 
				q_or_d= 'd'");
			if(mysql_num_rows($csla)==0){
				mysql_query("insert into sla_matrix(qs,id_mahasiswa,id_soal,id_jawaban,q_or_d, frekuensi) 
							values('".$fstem['kata_dasar']."','".$fJ['id_mahasiswa']."','".$fstem['id_soal']."','".$fJ['id_jawaban']."','d','".count_kata("d",$fstem['id_soal'],$fstem['kata_dasar'],$fJ['id_jawaban'])."')");
			}
		}
	}
	
	}
	//
	
	
	
}
?>