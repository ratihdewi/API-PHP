<?php

// Set path to JAMA Matrix object.
require_once "Matrix.php";

// Wrap JAMA matrix functionality inside a qr function.
function svd($m) {
  $A  = new Matrix($m);
  $SVD = $A->svd();
  $ans['U']  = $SVD->getU();
  $ans['V']  = $SVD->getV();
  $ans['S']  = $SVD->getS();
  return $ans;
}

function inverse($m) {
  $A  = new Matrix($m);
  $inverse = $A->inverse();
  return $inverse;
}

function transpose($m) {
  $A  = new Matrix($m);
  $transpose = $A->transpose();
  return $transpose;
}

$m=array(
		array(0,0.6990,0,0,0,0),
array(0.69897000433602,0,0,0,0,0.6990),
array(0,0.6990,0,0,0,0),
array(0,0,0.6990,0,0,0),
array(0.22184874961636,0.2218,0,0.4437,0,0.2218),
array(0,0.6990,0,0,0,0),
array(0,0,0,0,0.6990,0),
array(0,0,0,0,0.6990,0),
array(0,0,0.6990,0,0,0),
array(0,0.6990,0,0,0,0),
array(0,0,0,0,0,0.6990),
array(0,0,0,0.6990,0,0),
array(0,0,0,0.6990,0,0),
array(0,0,0,0,0.6990,0),
array(0,0,0,0.6990,0,0),
array(0,0,0.6990,0,0,0),
array(0,0,0,0,0,0.6990),
array(0,0,0.6990,0,0,0),
);

$svd=svd($m);
echo'<pre>';
print_R($svd);
echo'</pre>';
// Output Eigenvalue and Eigenvector components of answer object.
?>
