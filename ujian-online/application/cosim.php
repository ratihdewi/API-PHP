<?php
function cosim($q, $d){	
	/*
Let d1 = 13.845931556664 1.2579684032034
Let d2 = 5.619619912659 4.7237891345902
Cosine Similarity (d1, d2) =  dot(d1, d2) / ||d1|| ||d2||dot(d1, d2) = (13.845931556664)*(5.619619912659) + (1.2579684032034)*(4.7237891345902) = 83.7512501599

||d1|| = sqrt((13.845931556664)^2 + (1.2579684032034)^2) = 13.9029603026

||d2|| = sqrt((5.619619912659)^2 + (4.7237891345902)^2) = 7.34127453177

Cosine Similarity (d1, d2) = 83.7512501599 / (13.9029603026) * (7.34127453177)
                           = 83.7512501599 / 102.065448386
                           = 0.820564172152


------------------------------------------
End Calculations
------------------------------------------
	*/
	
	$arrQ = $q->A;
	$arrD = $d->A;
	//cari dot(Q,D)
	$dot = 0;
	for($i=0;$i<count($arrQ[0]);$i++)
	{
		$dot = ($arrQ[0][$i] * $arrD[0][$i]) + $dot;
	}
	
	//echo $dot."<br>";
	//cari ||d1||
	$abxQ=0;
	for($i=0;$i<count($arrQ[0]);$i++)
	{
		$abxQ = ($arrQ[0][$i] * $arrQ[0][$i]) + $abxQ;
	}
	$sqAbxQ = sqrt($abxQ);
	//echo $sqAbxQ."<br>";
	$abxD=0;
	for($i=0;$i<count($arrD[0]);$i++)
	{
		$abxD = ($arrD[0][$i] * $arrD[0][$i]) + $abxD;
	}
	$sqAbxD = sqrt($abxD);
	
	//echo $sqAbxQ."<br>";
	$cosim = $dot / ($sqAbxQ*$sqAbxD);
	return $cosim;
	

}
?>