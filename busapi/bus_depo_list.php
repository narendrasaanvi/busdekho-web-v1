<?php
  include('config.php');
  $state = $_POST['sate'];
	$buss=$mysqli->query("select * from tbldepot where state ='$state' ORDER BY city ASC");
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