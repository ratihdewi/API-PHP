<?php
include '_conn.php';	
// fungsi-fungsi
/*

DP + DP + root word + DS + PP + P

DP : Derivation Prefix
DS : Derivation Suffix
PP : Possessive Pronoun (Inflection) [ku,mu,nya]
P : Particle (Inflection) [lah,kah,]

Affik = Imbuhan
Prefik = Awalan
Suffix = Akhiran
Infix = Sisipan 
Konfix = Awalan dan Akhiran


Prefix Disallowed suffixes
be- -i
di- -an
ke- -i, -kan [kecuali: ke-tahu-i]
me- -an
se- -i,-kan
te- -an

Nazief and Adriani’s Algorithm 
function cekKamus($kata){
	// cari di database	
	$kamus    = file_get_contents('kamus-ind.txt');
	$katadasar        = explode("\n", $kamus);		
	
	$tes=  array_search($kata,$katadasar);
	
	if (in_array($kata, $katadasar)) {
		return true; // True jika ada
	}else{
		return false; // jika tidak ada FALSE
	}

}
*/
					
function cekKamus($kata){
	// cari di database	
	$sql = "SELECT * from stemlist where stem ='$kata' LIMIT 1";
	//echo $sql.'<br/>';
	$result = mysql_query($sql) or die(mysql_error());  
	if(mysql_num_rows($result)==1){
		return true; // True jika ada
	}else{
		return false; // jika tidak ada FALSE
	}
}


/*============= Stemming dengan Metode Nazief and Adriani’s Algorithm ===============================*/
/*
DP + DP + DP + root word + DS + PP + P

DP : Derivation Prefix
DS : Derivation Suffix
PP : Possessive Pronoun (Inflection) [ku,mu,nya]
P : Particle (Inflection) [lah,kah,]
*/

// Hapus Inflection Suffixes (“-lah”, “-kah”, “-ku”, “-mu”, atau “-nya”)
function Del_Inflection_Suffixes($kata){ 
	$kataAsal = $kata;
	if(preg_match('/([km]u|nya|[kl]ah|pun)$/i',$kata)){ // Cek Inflection Suffixes
		$__kata = preg_replace('/([km]u|nya|[kl]ah|pun)$/i','',$kata);
		if(preg_match('/([klt]ah|pun)$/i',$kata)){ // Jika berupa particles (“-lah”, “-kah”, “-tah” atau “-pun”)
			if(preg_match('/([km]u|nya)$/i',$__kata)){ // Hapus Possesive Pronouns (“-ku”, “-mu”, atau “-nya”)
				$__kata__ = preg_replace('/([km]u|nya)$/i','',$__kata);
				return $__kata__;
			}
		}
		return $__kata;	
	}
	return $kataAsal;
}


// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
function Cek_Prefix_Disallowed_Sufixes($kata){
	if(preg_match('/^(be)[[:alpha:]]+(i)$/i',$kata)){ // be- dan -i
		return true;
	}
	if(preg_match('/^(di)[[:alpha:]]+(an)$/i',$kata)){ // di- dan -an				
		return true;
		
	}
	if(preg_match('/^(ke)[[:alpha:]]+(i|kan)$/i',$kata)){ // ke- dan -i,-kan
		return true;
	}
	if(preg_match('/^(me)[[:alpha:]]+(an)$/i',$kata)){ // me- dan -an
		return true;
	}
	if(preg_match('/^(se)[[:alpha:]]+(i|kan)$/i',$kata)){ // se- dan -i,-kan
		return true;
	}
	return false;
}

// Hapus Derivation Suffixes (“-i”, “-an” atau “-kan”)
function Del_Derivation_Suffixes($kata){
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

// Hapus Derivation Prefix (“di-”, “ke-”, “se-”, “te-”, “be-”, “me-”, atau “pe-”)
function Del_Derivation_Prefix($kata){
	$kataAsal = $kata;	
		
	/* ------ Tentukan Tipe Awalan ------------*/
	if(preg_match('/^(di|[ks]e)/i',$kata)){ // Jika di-,ke-,se-
		$__kata = preg_replace('/^(di|[ks]e)/i','',$kata);
		if(cekKamus($__kata)){			
			return $__kata; // Jika ada balik
		}
		$__kata__ = Del_Derivation_Suffixes($__kata);
		if(cekKamus($__kata__)){
			return $__kata__;
		}
		/*------------end “diper-”, ---------------------------------------------*/
		if(preg_match('/^(diper)/i',$kata)){			
			$__kata = preg_replace('/^(diper)/i','',$kata);
			if(cekKamus($__kata)){			
				return $__kata; // Jika ada balik
			}
			$__kata__ = Del_Derivation_Suffixes($__kata);
			if(cekKamus($__kata__)){
				return $__kata__;
			}
			/*-- Cek luluh -r ----------*/
			$__kata = preg_replace('/^(diper)/i','r',$kata);
			if(cekKamus($__kata)){			
				return $__kata; // Jika ada balik
			}
			$__kata__ = Del_Derivation_Suffixes($__kata);
			if(cekKamus($__kata__)){
				return $__kata__;
			}
		}
		/*------------end “diper-”, ---------------------------------------------*/
	}
	if(preg_match('/^([tmbp]e)/i',$kata)){ //Jika awalannya adalah “te-”, “me-”, “be-”, atau “pe-”
		
		/*------------ Awalan “te-”, ---------------------------------------------*/
		if(preg_match('/^(te)/i',$kata)){ // Jika awalan “te-”,
			/* Cara Menentukan Tipe Awalan Untuk Kata Yang Diawali Dengan “te-”
			Following Characters
			Set 1 					Set 2 					Set 3 		Set 4 		Tipe Awalan
		1.	“-r-“ 					“-r-“ 					- 			- 			none
		2.	“-r-“ 					Vowel (aiueo) 			- 			- 			ter-luluh
		3.	“-r-“ 					not(vowel or “-r-”) 	“-er-“ 		vowel 		ter
		4.	“-r-“ 					not(vowel or “-r-”) 	“-er-“ 		not vowel 	ter-
		5.	“-r-“ 					not(vowel or “-r-”) 	not “-er-“ 	- 			ter
		6.	not(vowel or “-r-”) 	“-er-“ 					vowel 		- 			none
		7.	not(vowel or “-r-”) 	“-er-“ 					not vowel 	- 			te
			*/
			if(preg_match('/^(terr)/i',$kata)){ // 1.
				return $kata;
			}
			if(preg_match('/^(ter)[aiueo]/i',$kata)){ // 2.
				$__kata = preg_replace('/^(ter)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(ter[^aiueor]er[aiueo])/i',$kata)){ // 3.
				$__kata = preg_replace('/^(ter)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(ter[^aiueor]er[^aiueo])/i',$kata)){ // 4.
				$__kata = preg_replace('/^(ter)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(ter[^aiueor][^(er)])/i',$kata)){ // 5.
				$__kata = preg_replace('/^(ter)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(te[^aiueor]er[aiueo])/i',$kata)){ // 6.
				return $kata; // return none
			}
			if(preg_match('/^(te[^aiueor]er[^aiueo])/i',$kata)){ // 7.
				$__kata = preg_replace('/^(te)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
		/*------------end “te-”, ---------------------------------------------*/
		/*------------ Awalan “me-”, ---------------------------------------------*/
		if(preg_match('/^(me)/i',$kata)){ // Jika awalan “me-”,
			/* Cara Menentukan Tipe Awalan Untuk Kata Yang Diawali Dengan “me-”
			Following Characters
			Set 1 					Set 2 					Set 3 		Set 4 		Tipe Awalan
		1.	“-ng-“ 					Vowel [kghq] 			- 			- 			meng-
		2.	“-ny-“ 					Vowel (aiueo) 			- 			- 			meny-s
		3.	“-m-“ 					[bfpv]				 	- 			- 			mem-
		4.	“-n-“ 					[cdjsz] 				- 			- 			men-
		5.	- 						- 						- 			-			me-

			*/
			if(preg_match('/^(meng)[aiueokghq]/i',$kata)){ // 1.
				$__kata = preg_replace('/^(meng)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}				
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				/*--- cek luluh k- --------*/
				$__kata = preg_replace('/^(meng)/i','k',$kata); // luluh k-
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			
			if(preg_match('/^(meny)/i',$kata)){ // 2.
				$__kata = preg_replace('/^(meny)/i','s',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(mem)[bfpv]/i',$kata)){ // 3.
				$__kata = preg_replace('/^(mem)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				/*--- cek luluh p- --------*/
				$__kata = preg_replace('/^(mem)/i','p',$kata); // luluh p-
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(men)[cdjsz]/i',$kata)){ // 4.
				$__kata = preg_replace('/^(men)/i','',$kata);	
				
				if(cekKamus($__kata)){
					
					return $__kata; // Jika ada balik
				}				
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){					
					return $__kata__;
				}				
			}
			if(preg_match('/^(me)/i',$kata)){ // 5.
				$__kata = preg_replace('/^(me)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				/*--- cek luluh t- --------*/
				$__kata = preg_replace('/^(men)/i','t',$kata); // luluh t-
				
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				
			}
		}
		/*------------end “me-”, ---------------------------------------------*/
		/*------------ Awalan “be-”, ---------------------------------------------*/
		if(preg_match('/^(be)/i',$kata)){ // Jika awalan “be-”,
			/* Cara Menentukan Tipe Awalan Untuk Kata Yang Diawali Dengan “be-”
			Following Characters
			Set 1 					Set 2 					Set 3 		Set 4 		Tipe Awalan
		1.	“-r-“ 					Vowel 					- 			- 			ber-
		2.	“-r-“ 					Not Vowel 	 			- 			- 			ber-
		3.	“-k-“ 					-				 		- 			- 			be-


			*/
			if(preg_match('/^(ber)[aiueo]/i',$kata)){ // 1.
				$__kata = preg_replace('/^(ber)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata = preg_replace('/^(ber)/i','r',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}				
			}
			
			if(preg_match('/(ber)[^aiueo]/i',$kata)){ // 2.
				$__kata = preg_replace('/(ber)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(be)[k]/i',$kata)){ // 3.
				$__kata = preg_replace('/^(be)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
		/*------------end “be-”, ---------------------------------------------*/
		/*------------ Awalan “pe-”, ---------------------------------------------*/
		
		if(preg_match('/^(pe)/i',$kata)){ // Jika awalan “pe-”,
			/* Cara Menentukan Tipe Awalan Untuk Kata Yang Diawali Dengan “pe-”
			Following Characters
			Set 1 					Set 2 					Set 3 		Set 4 		Tipe Awalan
		1.	“-ng-“ 					Vowel [kghq] 			- 			- 			peng-
		2.	“-ny-“ 					Vowel (aiueo) 			- 			- 			peny-s
		3.	“-m-“ 					[bfpv]				 	- 			- 			pem-
		4.	“-n-“ 					[cdjsz] 				- 			- 			pen-
		5.	“-r-“ 					- 						- 			-			per-
		6.	- 						- 						- 			-			pe-

			*/			
			if(preg_match('/^(peng)[aiueokghq]/i',$kata)){ // 1.
				$__kata = preg_replace('/^(peng)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}				
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}				
			}
			
			if(preg_match('/^(peny)/i',$kata)){ // 2.
				$__kata = preg_replace('/^(peny)/i','s',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(pem)[bfpv]/i',$kata)){ // 3.
				$__kata = preg_replace('/^(pem)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}

				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(pen)[cdjsz]/i',$kata)){ // 4.
				$__kata = preg_replace('/^(pen)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
								/*-- Cek luluh -p ----------*/
				$__kata = preg_replace('/^(pem)/i','p',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
			}
			if(preg_match('/^(per)/i',$kata)){ // 5.				
				$__kata = preg_replace('/^(per)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
				/*-- Cek luluh -r ----------*/
				$__kata = preg_replace('/^(per)/i','r',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
			if(preg_match('/^(pe)/i',$kata)){ // 6.
				$__kata = preg_replace('/^(pe)/i','',$kata);
				if(cekKamus($__kata)){			
					return $__kata; // Jika ada balik
				}
				$__kata__ = Del_Derivation_Suffixes($__kata);
				if(cekKamus($__kata__)){
					return $__kata__;
				}
			}
		}
		/*------------end “pe-”, ---------------------------------------------*/
		/*------------ Awalan “memper-”, ---------------------------------------------*/
		
		if(preg_match('/^(memper)/i',$kata)){				
			$__kata = preg_replace('/^(memper)/i','',$kata);
			if(cekKamus($__kata)){			
				return $__kata; // Jika ada balik
			}
			$__kata__ = Del_Derivation_Suffixes($__kata);
			if(cekKamus($__kata__)){
				return $__kata__;
			}
			/*-- Cek luluh -r ----------*/
			$__kata = preg_replace('/^(memper)/i','r',$kata);
			if(cekKamus($__kata)){			
				return $__kata; // Jika ada balik
			}
			$__kata__ = Del_Derivation_Suffixes($__kata);
			if(cekKamus($__kata__)){
				return $__kata__;
			}
		}		
		
	}
	
	/* --- Cek Ada Tidaknya Prefik/Awalan (“di-”, “ke-”, “se-”, “te-”, “be-”, “me-”, atau “pe-”) ------*/
	if(preg_match('/^(di|[kstbmp]e)/i',$kata) == FALSE){
		return $kataAsal;
	}
	
	return $kataAsal;
}

function NAZIEF($kata){
	
	$kataAsal = $kata;
	
	/* 1. Cek Kata di Kamus jika Ada SELESAI */
	if(cekKamus($kata)){ // Cek Kamus
		return $kata; // Jika Ada kembalikan
	}
	
	/* 2. Buang Infection suffixes (\-lah", \-kah", \-ku", \-mu", atau \-nya") */
	$kata = Del_Inflection_Suffixes($kata);
	
	/* 3. Buang Derivation suffix (\-i" or \-an") */
	$kata = Del_Derivation_Suffixes($kata);
	
	/* 4. Buang Derivation prefix */
	$kata = Del_Derivation_Prefix($kata);
	
	
	return $kata;

}
?>