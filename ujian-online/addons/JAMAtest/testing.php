<?php
include "Matrix.php";
//include "SingularValueDecomposition.php";

$a = array(array(1,2,3,4,5),array(1,2,3,4,5),array(1,2,3,4,5));
$mtx = new Matrix($a);
$svd = new SingularValueDecomposition($mtx);
print_r($svd->getU());
?>
<br><br><br>
<?php
print_r($svd->getS());

?>