<?php

//error_reporting(0);

function steming(){	
	mysql_query("delete from steming_soal where id_soal in (select distinct id_soal from soal where id_ujian ='".$_GET['id']."')");
	
	$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
					 "'<[\/\!]*?[^<>]*?>'si",           // Strip out html tags
					 "'([\r\n])[\s]+'",                 // Strip out white space
					 "'&(quot|#34);'i",                 // Replace html entities
					 "'&(amp|#38);'i",
					 "'&(lt|#60);'i",
					 "'&(gt|#62);'i",
					 "'&(nbsp|#160);'i",
					 "'&(iexcl|#161);'i",
					 "'&(cent|#162);'i",
					 "'&(pound|#163);'i",
					 "'&(copy|#169);'i",
					 "'&#(\d+);'e");                    // evaluate as php
	
	$replace = array ("","-"," ","-",
					  ",",
					  "\\1",
					  "\"",
					  "&",
					  "<",
					  ">",
					  " ",
					  chr(161),
					  chr(162),
					  chr(163),
					  chr(169),
					  "chr(\\1)");
					  
	
	$q = mysql_query("select * from soal where id_ujian = '".$_GET['id']."'");
	
	while($fq=mysql_fetch_array($q))
	{
		$index=preg_replace_callback($search, $replace, $fq['jawaban']);
		$index2=explode(" ",$index);
		$rplc = array("-",",","-", ".", ";", "(", ")", "&laquo;","&laquo", ":", "\"");	
					
		for($j=0;$j<=count($index2);$j++)
		{
			if(strlen($index2[$j])>1 and stopword($index2[$j])==true){
			if($index2[$j]!='-' or $index2[$j]!=','){
				mysql_query("insert into steming_soal(
				id_soal,
				stem, 
				kata_dasar 
				) 
				values(	
				'".$fq['id_soal']."',
				'".strtolower(str_replace(",","",$index2[$j]))."',
				'".strtolower(NAZIEF(str_replace(",","",$index2[$j])))."')");
				}
			}
		}
		
	}
		
}


function steming_jawaban_mhs(){
	 mysql_query("delete from steming_jawaban where id_mahasiswa = '".$_SESSION['sess_id']."' and id_soal in (select distinct id_soal from soal where id_ujian ='".$_GET['id']."')");
	 //echo "delete from steming_jawaban where id_mahasiswa = '".$_SESSION['sess_id']."' and id_soal in (select id_soal from soal where id_ujian ='".$_GET['id']."')";
	 //exit();
	
	$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
					 "'<[\/\!]*?[^<>]*?>'si",           // Strip out html tags
					 "'([\r\n])[\s]+'",                 // Strip out white space
					 "'&(quot|#34);'i",                 // Replace html entities
					 "'&(amp|#38);'i",
					 "'&(lt|#60);'i",
					 "'&(gt|#62);'i",
					 "'&(nbsp|#160);'i",
					 "'&(iexcl|#161);'i",
					 "'&(cent|#162);'i",
					 "'&(pound|#163);'i",
					 "'&(copy|#169);'i",
					 "'&#(\d+);'e");                    // evaluate as php
	
	$replace = array ("","-","-",
					  ",",
					  "\\1",
					  "\"",
					  "&",
					  "<",
					  ">",
					  " ",
					  chr(161),
					  chr(162),
					  chr(163),
					  chr(169),
					  "chr(\\1)");
					  
	
	$q = mysql_query("select a.* from jawaban a, soal b where a.id_soal = b.id_soal and b.id_ujian = '".$_GET['id']."' and a.id_mahasiswa = '".$_SESSION['sess_id']."' order by 1 asc");
	
	while($fq=mysql_fetch_array($q))
	{
		$index=preg_replace_callback($search, $replace, $fq['jawaban']);
		$index2=explode(" ",$index);
		$rplc = array("-",",","-", ".", ";", "(", ")", "&laquo;","&laquo", ":", "\"");	
					
		for($j=0;$j<=count($index2);$j++)
		{
			if(strlen($index2[$j])>1 and stopword($index2[$j])==true){
			if($index2[$j]!='-' or $index2[$j]!=','){
				mysql_query("insert into steming_jawaban(
				id_soal,
				id_jawaban,
				id_mahasiswa,
				stem, 
				kata_dasar 
				) 
				values(	
				'".$fq['id_soal']."',
				'".$fq['id_jawaban']."',
				'".$_SESSION['sess_id']."',
				'".strtolower(str_replace(",","",$index2[$j]))."',
				'".strtolower(NAZIEF(str_replace(",","",$index2[$j])))."')");			
				}
			}
		}
		
	}
		
}


function stopword($term)
{
	$cek = mysql_num_rows(mysql_query("select * from stopword where stopword='".$term."'"));
	if($cek>0)
	{
		$hsl = false;
	}else{
		$hsl = true;
	}
	return $hsl;
}

function hitungKata($term,$ket,$id_buku)
{
	$hit = mysql_num_rows(mysql_query("select * from stem 
	where lower(stem)=lower('".$term."') 
	and keterangan = '".$ket."'
	and id_buku = '".$id_buku."'
	"));
	return $hit;
}

?>