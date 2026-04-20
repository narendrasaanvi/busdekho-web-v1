<?php
  include('config.php');
	$buss=$mysqli->query("select DISTINCT state  from tbldepot ORDER BY depot ASC");
	if($buss->num_rows>0)
    	{
    	    While( $row = $buss->fetch_assoc()){
    	        $final[] = $row;
    	    }
    	} else{
    	    $final =  $mysqli->error;
    	}
$error=array('status'=>'true','msg'=>'Enter Bus Type','data'=>$final);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>