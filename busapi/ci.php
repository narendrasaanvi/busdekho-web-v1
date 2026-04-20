<?php
  include('config.php');
	$buss=$mysqli->query("SELECT *  FROM tblcity ORDER BY `city` ASC");
	if($buss->num_rows>0)
    	{
    	    echo  json_encode($buss,JSON_PRETTY_PRINT); 
    	    
    	   // While( $row = $buss->fetch_assoc()){
    	     //   $final[] = $row;
    	    //}
    	} else{
    	    $final =  $mysqli->error;
    	}
$error=array('status'=>'true','msg'=>'Enter Bus Type','data'=>$final);
    	//echo json_encode($error,JSON_PRETTY_PRINT);

?>