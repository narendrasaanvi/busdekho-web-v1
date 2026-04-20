<?php
  include('config.php');
 
$subject=$_POST['subject'];
 
$msg=$_POST['msg'];
  
	$buss=$mysqli->query("insert into tblmail(subject,msg) values('$subject','$msg')");
	if($buss)
    	{
    	    $status = "MSG Send";
    	} else{
    	    $status = "Try Again Later";
    	}
$error=array('status'=>$status);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>