<?php
  include('config.php');

  $name=$_POST['name'];
$bus_code=$_POST['bus_code'];
$msg=$_POST['msg'];
  
	$buss=$mysqli->query("insert into tblsuggestion(name,message,bus_id) values('$name','$msg','$bus_code')");
	if($buss)
    	{
    	    $status = "Suggestion Submited";
    	} else{
    	    $status = "Try Again Later";
    	}
$error=array('status'=>$status);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>