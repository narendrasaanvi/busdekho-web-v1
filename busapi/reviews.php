<?php
  include('config.php');
  $bus_code=$_POST['busid'];
	$buss=$mysqli->query("SELECT * FROM tblfeedback WHERE bus_id='$bus_code'");
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