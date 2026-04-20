<?php
  include('config.php');
  $bus_code=$_POST['busid'];
  
  $name=$_POST['name'];
$bus_code=$_POST['bus_code'];
$msg=$_POST['msg'];
$rate=$_POST['rate'];
  
	$buss=$mysqli->query("insert into tblfeedback(name,message,bus_id,rate) values('$name','$msg','$bus_code','$rate')");
	if($buss)
    	{
    	    $status = "Feedback Submited";
    	} else{
    	    $status = "Try Again Later";
    	}
$error=array('status'=>$status);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>