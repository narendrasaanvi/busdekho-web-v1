<?php
  include('config.php');
 
  $name=$_POST['name'];
  //////////////////
  $vendor=$_POST['vendor'];
$city_from = $_POST['from'];
$city_to = $_POST['to'];
$msg=$_POST['msg'];

$mobile = $_POST['mobile'];
 $busid=$_POST['bus_code'];
  ///////////
  
	$buss=$mysqli->query("INSERT INTO `tblreport`( `vendor`, `city_form`, `city_to`, `msg`,`username`,`mobile`,`busid`) values('$vendor','$city_from','$city_to','$msg','$name','$mobile','$busid')");
	if($buss)
    	{
    	    $status = "Report Submited";
    	} else{
    	    $status = "Try Again Later";
    	}
$error=array('status'=>$status);
    	echo json_encode($error,JSON_PRETTY_PRINT);

?>