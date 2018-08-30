<?php

	mysql_connect("localhost","root","");
	mysql_select_db("ujian_online");
	
	include 'algoritma_nazief&adriani.php';
	
	$kata = $_POST["kata"];
	
	
		if(cekKamus($kata) == 1){
			$sql = "SELECT * from stemlist where stem ='$kata' LIMIT 1";
			//echo $sql.'<br/>';
			$result = mysql_query($sql) or die(mysql_error());  
			if(mysql_num_rows($result)==1){
				$arrayy = array('jumlah' => 1,'kembalian'=>'ada');		
				echo json_encode($arrayy);
			}else{
				$arrayy = array('jumlah' => 0);	
				echo json_encode($arrayy);
			}			
		}elseif(Del_Inflection_Suffixes($kata) == 2){
			$kataAsal = $kata;
			if(preg_match('/([km]u|nya|[kl]ah|pun)$/i',$kata)){ // Cek Inflection Suffixes
				$__kata = preg_replace('/([km]u|nya|[kl]ah|pun)$/i','',$kata);		
				if(preg_match('/([klt]ah|pun)$/i',$kata)){ // Jika berupa particles (“-lah”, “-kah”, “-tah” atau “-pun”)	
					if(preg_match('/([km]u|nya)$/i',$__kata)){ // Hapus Possesive Pronouns (“-ku”, “-mu”, atau “-nya”)
						$__kata__ = preg_replace('/([km]u|nya)$/i','',$__kata);
						$__kata__ = $kata1;
						$kata1 = array('jumlah' => 1,'kembalian'=>'ada');
						echo json_encode ($kata1);
					}
				}
				
				echo json_encode ($__kata__);
						
			}
			
			echo json_encode ($kataAsal);			
		
		} elseif(Del_Derivation_Suffixes($kata) ==1){
			$kataAsal = $kata;
			
			if(preg_match('/(i|an)$/i',$kata)){ // Cek Suffixes
				$__kata = preg_replace('/(i|an)$/i','',$kata);		
			if(cekKamus($__kata)){ // Cek Kamus			
				return $__kata;
			}
			/*-- Jika Tidak ditemukan di kamus --*/
			if(preg_match('/(kan)$/i',$kata)){ // cek -kan 				
				$__kata__ = preg_replace('/(kan)$/i','',$kata);
				if(cekKamus($__kata__)){ // Cek Kamus
					return $__kata__;
				}
			}
			if(Cek_Prefix_Disallowed_Sufixes($kata)){
				return $kataAsal;
			}
		
		}
		return $kataAsal;
		}
		
	
?>