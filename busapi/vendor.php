<?php
  include('config.php');
  $busid=$_POST['busid'];
							
											
	$buss=$mysqli->query("select * from tblvendor where travel_name = '$busid'");
	if($buss->num_rows>0)
    	{
    	    $row = $buss->fetch_assoc();
    	} else{
    	    $row =  $mysqli->error;
    	}
$error=array('status'=>'true','msg'=>'Enter Bus Type','data'=>$row);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>