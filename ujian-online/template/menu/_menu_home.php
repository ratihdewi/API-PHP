     <div id="cssmenu">
	 <ul>
      	<?php
      	if(empty($_SESSION['sess_login'])){
      	?>
           
        <?php
        }elseif($_SESSION['sess_level']=='admin'){
        ?>
        <li  <?php if(!empty($_GET['m']) and $_GET['m']=="home"){ echo "class='active'"; } ?> ><a href="index.php?m=home">Home</a></li>
		<li class="has-sub" <?php if(!empty($_GET['m']) and $_GET['m']=="dosen"){ echo 'class="active"'; } ?>><a href="index.php?m=pengguna">Pengguna</a> 
			<ul>
				<li <?php if(!empty($_GET['m']) and $_GET['m']=="dosen"){ echo 'class="active"'; } ?>><a href="index.php?m=dosen">Dosen</a></li> 
				<li <?php if(!empty($_GET['m']) and $_GET['m']=="mahasiswa"){ echo 'class="active"'; } ?>><a href="index.php?m=mahasiswa">Mahasiswa</a></li>
				<li <?php if(!empty($_GET['m']) and $_GET['m']=="pengawas"){ echo 'class="active"'; } ?>><a href="index.php?m=pengawas">Pengawas</a></li>	
			</ul>    
		</li>  		
		<li <?php if(!empty($_GET['m']) and $_GET['m']=="mata_kuliah"){ echo 'class="active"'; } ?>><a href="index.php?m=mata_kuliah">Mata Kuliah</a></li>        
       
		 <?php
        }elseif($_SESSION['sess_level']=='dosen'){
        ?>
        <li <?php if(!empty($_GET['m']) and $_GET['m']=="home"){ echo "class='active'"; } ?> ><a href="index.php?m=home">Home</a></li>  
		<li <?php if(!empty($_GET['m']) and $_GET['m']=="ujian"){ echo 'class="active"'; } ?>><a href="index.php?m=ujian">Ujian</a>
			<ul>             
   				<!--<li <?php if(!empty($_GET['m']) and $_GET['m']=="tipe"){ echo 'class="active"'; } ?>><a href="index.php?m=tipe">Tipe Ujian</a></li>   		-->
   				<li <?php if(!empty($_GET['m']) and $_GET['m']=="ujian"){ echo 'class="active"'; } ?>><a href="index.php?m=ujian">Data Ujian</a></li>   		
				<li <?php if(!empty($_GET['m']) and $_GET['m']=="soal"){ echo 'class="active"'; } ?>><a href="index.php?m=soal"> Soal dan Kunci Jawaban</a></li>
				<li <?php if(!empty($_GET['m']) and $_GET['m']=="soal"){ echo 'class="active"'; } ?>><a href="index.php?m=peserta"> Peserta</a></li>
			</ul>
		</li>   	
		<li <?php if(!empty($_GET['m']) and $_GET['m']==""){ echo 'class="active"'; } ?>><a href="index.php?m=nilai&op=pilih">Lihat Nilai</a></li>  
		 <?php
        }elseif($_SESSION['sess_level']=='pengawas'){
        ?>
         <li <?php if(!empty($_GET['m']) and $_GET['m']=="home"){ echo "class='active'"; } ?> ><a href="index.php?m=home">Home</a></li>               
   		<li <?php if(!empty($_GET['m']) and $_GET['m']=="beritaacara"){ echo 'class="active"'; } ?>><a href="index.php?m=beritaacara">Berita Acara</a></li> 
		<li <?php if(!empty($_GET['m']) and $_GET['m']=="peserta"){ echo 'class="active"'; } ?>><a href="index.php?m=peserta">Peserta</a></li>  		  	 
  		 <?php
        }elseif($_SESSION['sess_level']=='mahasiswa'){
        ?>
        <li <?php if(!empty($_GET['m']) and $_GET['m']=="home"){ echo "class='active'"; } ?> ><a href="index.php?m=home">Home</a></li>               
   		<li <?php if(!empty($_GET['m']) and $_GET['m']=="ujianmhs_pil"){ echo 'class="active"'; } ?>><a href="index.php?m=ujianmhs_pil">Ujian</a></li>   		
		<li <?php if(!empty($_GET['m']) and $_GET['m']==""){ echo 'class="active"'; } ?>><a href="index.php?m=nilaimhs&op=pilih">Lihat Nilai</a></li>   	 
		 <?php
        }
        ?>
      </ul>
    
	</div>