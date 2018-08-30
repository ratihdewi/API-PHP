<?php
	if(!empty($_GET['m']) and $_GET['m']=='login_db' and isset($_POST['login'])){
		$q = mysql_query("select a.*, b.nama_type from admin a, level b
		where a.id_level = b.id_level and a.username = '$_POST[uname]' and a.password =md5('$_POST[pwd]')");
		$f=mysql_fetch_array($q);
		
		if(mysql_num_rows($q) > 0)
		{
			$_SESSION['sess_level']=$f['nama_type'];
			$_SESSION['sess_login']=$f['username'];
			$_SESSION['sess_id']=$f['id_admin'];
			?>
				<script>
					window.location='index.php?m=home';
				</script>
			<?php
		}else{
				//dosen
					$q = mysql_query("select a.*, b.nama_type from dosen a, level b
					where a.id_level = b.id_level and a.username = '$_POST[uname]' and a.password = md5('$_POST[pwd]')"); 
				
					$f=mysql_fetch_array($q);
					
					if(mysql_num_rows($q) > 0)
					{
						$_SESSION['sess_level']=$f['nama_type'];
						$_SESSION['sess_login']=$f['username'];
						$_SESSION['sess_id']=$f['id_dosen'];
						$_SESSION['sess_dosen']=$f['id_dosen'];
						
						?>
							<script>
								window.location='index.php?m=home';
							</script>
						<?php
					}else{
						
						//mhsiswa
						$q = mysql_query("select a.*, b.nama_type from mahasiswa a, level b
						where a.id_level = b.id_level and a.username = '$_POST[uname]' and a.password = md5('$_POST[pwd]')");
						$f=mysql_fetch_array($q);
						
						if(mysql_num_rows($q) > 0)
						{
							$_SESSION['sess_level']=$f['nama_type'];
							$_SESSION['sess_login']=$f['username'];
							$_SESSION['sess_id']=$f['id_mahasiswa'];
							
							?>
								<script>
									     window.location='index.php?m=home';
								</script>
							<?php
						}else{
						//pengawas
						$q = mysql_query("select a.*, b.nama_type from pengawas a, level b
						where a.id_level = b.id_level and a.username = '$_POST[uname]' and a.password = md5('$_POST[pwd]')");
						$f=mysql_fetch_array($q);
						
						if(mysql_num_rows($q) > 0)
						{
							$_SESSION['sess_level']=$f['nama_type'];
							$_SESSION['sess_login']=$f['username'];
							$_SESSION['sess_id']=$f['id_pengawas'];
							?>
								<script>
									window.location='index.php?m=home';
								</script>
								<?php
								}else{
								?>
								<script>
									alert('Username atau Password Salah!');
									history.back();
								</script>
							<?php
							
					}	
				}	
			}
		}
	}
?>