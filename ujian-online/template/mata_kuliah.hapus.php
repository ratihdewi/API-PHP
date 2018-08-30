<?php
	if(!empty($_GET['m']) and $_GET['m']=='mata_kuliah'  and !empty($_GET['op']) and $_GET['op']=='hapus' and !empty($_GET['id']) ){		
	?>
	<h2 align='center'>Hapus <?php echo $_GET['m'] ?> </h2>
	
	<?php
	$tbl = "mata_kuliah";
	$pk = "id_mata_kuliah";
	$col = "*";
	$wh = $pk." = '$_GET[id]'";
	$sql = "select $col from $tbl where $wh";
	$query = mysql_query($sql);
	
	//insert to database
	if(isset($_POST['hapus'])){		      
        mysql_query("delete from $tbl where $wh");
        ?>
        <script>
        	alert("data berhasil di hapus");
        	window.location='index.php?m=<?php echo $_GET['m'] ?>';
        </script>
        <?php
	}
	
	?>
<!-- table-->	
<form action='' method='post'>
<table id="box-table-a" summary="Meeting Results">
   <thead>
    	   	
    	<?php
    	$f = mysql_fetch_array($query);
    	//cols
    	$i=0;
    	while ($i < mysql_num_fields($query)) {
    		$meta = mysql_fetch_field($query,$i);
	    	if (!$meta) {
    		    echo "No information available<br />\n";
	    	}else{	    	
	    		if(strtoupper($meta->name)=='MARKER'){
	    			echo "<tr> 
    	    			<th scope=\"col\">".str_replace("_"," ",strtoupper($meta->name))."</th>
	    	    		<td><input type='hidden' name='".$meta->name."' id='".$meta->name."' value='home.png'>
	    	    		<img src='images/icon/home.png'></td>
    		    	</tr>"; 
	    		}else{
	    			switch($meta->name)
	    			{
	    				
	    				
	    				default:	
			    			$val = $f[$meta->name];	    					
	    			}
    	    		echo "<tr> 
    	    			<th scope=\"col\" width='200'>".str_replace("_"," ",strtoupper($meta->name))."</th>
	    	    		<td>
	    	    		$val 
	    	    		</td>
    		    	</tr>";        	
    	    	}
        	}
	    	$i++;
        }
        ?>
        
  </thead>
    <tbody>
    	<td colspan='2' align='right'>
    	<input type='button' class='btnLogin' name='batal' value='Batal' onclick="window.location='index.php?m=<?php echo $_GET['m']?>'">
    	<input type='submit' class='btnLogin' name='hapus' value='Hapus'>
    	</td>
    </body>
</table>
</form>
	<?php
	}
?>			