window.onload=counter;
function counter()
{
var seconds = 5;
countDown();
}	
function countDown()
{
document.getElementById("remain").innerHTML=seconds;
if(seconds>0)
{
if ($durasi_ujian == 30) {
	$lama_ujian = 1800;
	}else if ($durasi_ujian == 45) {
	$lama_ujian = 2700;
	}else if ($durasi_ujian == 60) {
	$lama_ujian = 3600;
	}else if ($durasi_ujian == 90) {
	$lama_ujian = 5400;
	}else{
	$lama_ujian = 7200;
	}
seconds=seconds - 1;
setTimeout(countDown,$durasi_ujian );
}
if(seconds == 0)
{
document.form1.submit();
}

}