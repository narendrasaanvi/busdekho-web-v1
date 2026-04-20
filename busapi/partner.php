<?php
  include('config.php');
 

$name=$_POST['name'];
$mobile=$_POST['mobile'];
$person=$_POST['person'];
$city=$_POST['city'];
$no_vichal=$_POST['no_vichal'];
  
	$buss=$mysqli->query("insert into tblmember(name,mobile,person,city,no_vichal) values('$name','$mobile','$person','$city','$no_vichal')");
	if($buss)
    	{
    	    $status = "Request Submited";
    	} else{
    	    $status = "Try Again Later";
    	}
$error=array('status'=>$status);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>