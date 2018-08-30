<?php
	if(empty($_GET['m']) or $_GET['m']=='home'){	
?>	
		<div id="smallRight">
	<h3><font color="#009900" face="Lucida Sans Unicode">Dashboard</font></h3>
    <?php
	
 $bulan= array (1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"); $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"); 
 $cetak_date = $hari[(int)date("w")] .', '. date("j ") . $bulan[(int)date('m')] . date(" Y"); 
 echo "<b>$cetak_date</b>"; ?>


	<?php
				if ($_SESSION['sess_level']=='dosen')
					{
						$modal2 = 'panduan_dosen';
						
					}
				else if ( $_SESSION['sess_level']=='mahasiswa')
					{
				
						$modal2 = 'panduan_mahasiswa';
					} 	
					
				else if ( $_SESSION['sess_level']=='pengawas')
					{
				
						$modal2 = 'panduan_pengawas';
					} 	
								
				else 
					{
						$modal2 = 'panduan_admin';
					};
	?>


	
		<div class="shortcutHome" align="center">
		<a  class="js-open-modal" href="#" data-modal-id="popup1"><img src="mos-css/img/office-building_(1).png"><br>Jadwal Ujian</a>
		</div>
		<div class="shortcutHome" align="center">
		<a class="js-open-modal" href="#" data-modal-id="<?php echo $modal2 ?>" ><img src="mos-css/img/peng.png"><br>Panduan</a>
		</div>
		<div class="shortcutHome" align="center">
		<a class="js-open-modal" href="#" data-modal-id="popup3"><img src="mos-css/img/posting.png"><br>Info</a>
		</div>
		
	<div id="panduan_dosen" class="modal-box">  
		  <header>
			<a href="#" class="js-modal-close close"><img src="mos-css/img/delete.png" /></a>
			<h3> Jadwal Ujian </h3>
		  </header>
		  <div class="modal-body">
				<!-- isi pop up -->
				<p align="left"> Selamat datang sistem ujian online essay menggunakan metode LSA. halaman ini akan memberi panduan singkat tentang cara penggunaan sistem : <br />
				<b>Halaman beranda</b> anda dapat melihat informasi umum tentang ujian yang telah atur sebelumnya <br />
				<b>Menu Ujian</b> terdiri dari sub menu sebagai berikut : <br />
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rArr;Menu Jadwal Ujian</b> untuk mengelolah jadwal ujian <br />
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rArr;Menu Soal dan Kunci Jawaban</b> untuk mengelolah soal dan kunci jawaban ujian <br />
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rArr;Menu Peserta </b>untuk mengelolah data mahasiswa yang akan menjadi peserta ujian <br />
				<b>Menu Nilai</b> berguna untuk melihat hasil nilai mahasiswa <br />
				</p>
				
		  </div>
	</div>
	
	
	
	<div id="popup1" class="modal-box">  
		  <header>
			<a href="#" class="js-modal-close close"><img src="mos-css/img/delete.png" /></a>
			<h3> Panduan Menggunakan Sistem Untuk Dosen</h3>
		  </header>
		  <div class="modal-body">
				<!-- isi pop up -->
				<p align="center"> Jadwal ujian disesuaikan kalender akademik
				</p>
				
		  </div>
	</div>
	
	<div id="panduan_mahasiswa" class="modal-box">  
		  <header>
			<a href="#" class="js-modal-close close"><img src="mos-css/img/delete.png" /></a>
			<h3> Panduan Menggunakan Sistem Untuk Mahasiswa</h3>
		  </header>
		  <div class="modal-body">
				<!-- isi pop up -->
				<p align="left"> Selamat datang sistem ujian online essay menggunakan metode LSA. halaman ini akan memberi panduan singkat tentang cara penggunaan sistem : <br />
				<b>Halaman beranda</b> anda dapat melihat informasi umum tentang ujian yang telah atur sebelumnya <br />
				<b>Menu Ujian</b> Berguna bagi mahasiswa untuk ketika akan memulai ujian. Jadwal ujian hanya akan muncul ketika membuka sistem sesuai dengan jadwal ujian yang telah ditentukan serta ketika peserta sudah ditambahkan.Ketika peserta menekan tombol <img src="mos-css/img/mulai.png" /> maka soal akan ditampilkan dan waktu segera dihitung mundur<br />
				<b>Menu Nilai</b> berguna untuk melihat nilai mahasiswa setelah jawaban telah di inputkan. dengan menekan <img src="mos-css/img/detail.png" /> maka hasil ujian akan ditampilkan  <br />
				</p>
				
		  </div>
	</div>
	
	<div id="panduan_pengawas" class="modal-box">  
		  <header>
			<a href="#" class="js-modal-close close"><img src="mos-css/img/delete.png" /></a>
			<h3> Panduan Menggunakan Sistem Untuk Pengawas</h3>
		  </header>
		  <div class="modal-body">
				<!-- isi pop up -->
				<p align="left"> Selamat datang sistem ujian online essay menggunakan metode LSA. halaman ini akan memberi panduan singkat tentang cara penggunaan sistem : <br />
				<b>Halaman beranda</b> anda dapat melihat informasi umum tentang ujian yang telah atur sebelumnya <br />
				<b>Menu Peserta</b> terdiri dari tombol <img src="mos-css/img/add.png" /> untuk menambahkan peserta ujian dan mengimputkan berita acara ujian <br />
					
				</p>
				
		  </div>
	</div>
	
	<div id="panduan_admin" class="modal-box">  
		  <header>
			<a href="#" class="js-modal-close close"><img src="mos-css/img/delete.png" /></a>
			<h3> Panduan Menggunakan Sistem Untuk admin</h3>
		  </header>
		  <div class="modal-body">
				<!-- isi pop up -->
				<p align="left"> Selamat datang sistem ujian online essay menggunakan metode LSA. halaman ini akan memberi panduan singkat tentang cara penggunaan sistem : <br />
				<b>Halaman beranda</b> anda dapat melihat panduan menggunakan sistem, tentang sistem dan pengumuman jadwal ujian <br />
				<b>Menu Pengguna</b> terdiri dari sub menu sebagai berikut : <br />
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rArr;Menu Dosen</b> untuk mengatur pengguna dosen <br />
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rArr;Menu Mahasiswa</b> mengatur pengguna mahasiswa <br />
						<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rArr;Menu Pengawas </b>mengatur pengguna pengawas <br />
				<b>Menu Matakuliah</b> berguna untuk mengelola matakuliah <br />
				</p>
				
		  </div>
	</div>
	
	<div id="popup3" class="modal-box">  
		  <header>
			<a href="#" class="js-modal-close close"><img src="mos-css/img/delete.png" /></a>
			<h3> Tentang Sistem </h3>
		  </header>
		  <div class="modal-body">
				<!-- isi pop up -->
				<p align="justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sistem ini dirancang untuk memudahkan pengoreksian ujian essay. Pembobotan kata dilakukan menggunakan pembobotan lokal <i><b>term frequency (tf) </i></b> dengan metode <i><b>raw  term  frequency</i></b> dengan menghitung jumlah kemuculan kata dalam setiap dokumen.Pengoreksisan ujian dilakukan dengan menggunakan algoritma <b>LSA</b>. <b>LSA</b> dalam perhitungannya menggunakan <b>SVD (Singular Value Decomposition)</b>.  <strong>SVD</strong> merepresentasikan semantic space ke dalam bentuk matriks yang memiliki ordo lebih kecil dibandingkan ordo matriks aslinya, namun perhitungan matriks tetap menghasilkan matriks yang  bernilai hamper sama. SVD merupakan teorema aljabar linear yang dikatakan mampu memecah blok suatu matriks A menjadi tiga matriks baru, yaitu sebuah matriks orthogonal U, Matriks  diagonal S, dan Transpose Matriks orthogonal V. Untuk mengukur kedekatan vektor queri dengan dokumen digunakan perhitungan <b>cosim (cosin similarity)</b>.
				</p>
				
		  </div>
	</div>
<?php
	}
?>
</div>