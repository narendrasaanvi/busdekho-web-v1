		<?php
		include('config.php');


		$city_from = addslashes($_POST['city_from']);
		$city_to = addslashes($_POST['city_to']);
		$limit = $_POST['limit'];

		$Count = $mysqli->query("SELECT *   FROM (SELECT tblstation.id
												,tblstation.bus_code,tblbus.`route`,tblstation.arrival 
												AS starttime,tblbus.`city_from`,tblbus.vendor,tblbus.`city_to`,tblbus.`seat`,tblstation.`day` AS startday,tblstation.distance
												FROM tblstation,tblbus
												WHERE station_id = '$city_from' AND tblstation.`bus_code`= tblbus.`bus_code` 
												 AND STATUS ='active') AS TAB_1, 
												(SELECT   bus_code,arrival AS endtime ,tblstation.`day` AS endday,tblstation.distance
												FROM  tblstation  WHERE station_id = '$city_to') AS TAB_2
												WHERE (TAB_1.distance < TAB_2.distance && TAB_1.bus_code = TAB_2.bus_code)
												GROUP BY TAB_1.`bus_code`
												ORDER BY starttime ASC");
		$rows_count = $Count->num_rows;



		$buss = $mysqli->query("SELECT *   FROM (SELECT tblstation.id
												,tblstation.bus_code,tblbus.`route`,tblstation.arrival 
												AS starttime,tblbus.`city_from`,tblbus.vendor,tblbus.`city_to`,tblbus.`seat`,tblstation.`day` AS startday,tblstation.distance
												FROM tblstation,tblbus
												WHERE station_id = '$city_from' AND tblstation.`bus_code`= tblbus.`bus_code` 
												 AND STATUS ='active') AS TAB_1, 
												(SELECT   bus_code,arrival AS endtime ,tblstation.`day` AS endday,tblstation.distance
												FROM  tblstation  WHERE station_id = '$city_to') AS TAB_2
												WHERE (TAB_1.distance < TAB_2.distance && TAB_1.bus_code = TAB_2.bus_code)
												GROUP BY TAB_1.`bus_code`
												ORDER BY starttime ASC LIMIT $limit");
		while ($row = $buss->fetch_assoc()) {

			$bus_code = $row['bus_code'];

			$bussaa = $mysqli->query("select *  from tblbustype where bus_code ='$bus_code'");
			if ($bussaa->num_rows > 0) {
				while ($rowaa = $bussaa->fetch_assoc()) {
					$row['type']  = $rowaa['bustype'];
				}
			}



			$enday = $row['endday'];
			$starttime = $row['starttime'];
			$stoptime = $row['endtime'];

			if ($enday == "1") {
				$starttime = $row['starttime'];
				$stoptime = $row['endtime'];
				$x = strtotime($stoptime);
				$y = strtotime($starttime);
				if ($x > $y) {
					$diff = ($x - $y);
				} else {
					$diff = ($y - $x);
				}
				$total = $diff / 60;
				$totalhours = floor($total / 60);
				$finalhours = $totalhours;
				$finaltime =  sprintf("%02dh %02dm", $finalhours, $total % 60);
			}


			if ($enday == "2") {
				$starttime = $row['starttime'];
				$stoptime = $row['endtime'];
				$secondday = '24:00';
				$thired = '00:00';

				$x = strtotime($starttime);
				$y = strtotime($stoptime);
				$z = strtotime($secondday);
				$c = strtotime($thired);
				$a = ($z - $x);
				$d = ($y - $c);
				$diff = $a + $d;
				$total = $diff / 60;
				$totalhours = floor($total / 60);
				$finalhours = $totalhours;
				$finaltime =  sprintf("%02dh %02dm", $finalhours, $total % 60);
			}

			if ($enday == "3") {
				$starttime = $row['starttime'];
				$stoptime = $row['endtime'];
				$secondday = '24:00';
				$thired = '00:00';

				$x = strtotime($starttime);
				$y = strtotime($stoptime);
				$z = strtotime($secondday);
				$c = strtotime($thired);



				$t1 = ($z - $x);
				$t2 = ($z - $c);
				$t3 = ($y - $c);

				$diff = $t1 + $t2 + $t3;

				$total = $diff / 60;
				$totalhours = floor($total / 60);
				$finalhours = $totalhours;
				$finaltime =  sprintf("%02dh %02dm", $finalhours, $total % 60);
			}

			if ($enday == "4") {
				$starttime = $row['starttime'];
				$stoptime = $row['endtime'];
				$secondday = '24:00';
				$thired = '00:00';

				$x = strtotime($starttime);
				$y = strtotime($stoptime);
				$z = strtotime($secondday);
				$c = strtotime($thired);



				$t1 = ($z - $x);
				$t2 = ($z - $c);
				$t3 = ($y - $c);

				$diff = $t1 + $t2 + $t2 + $t3;

				$total = $diff / 60;
				$totalhours = floor($total / 60);
				$finalhours = $totalhours;
				$finaltime =  sprintf("%02dh %02dm", $finalhours, $total % 60);
			}


			if ($enday == "5") {
				$starttime = $row['starttime'];
				$stoptime = $row['endtime'];
				$secondday = '24:00';
				$thired = '00:00';

				$x = strtotime($starttime);
				$y = strtotime($stoptime);
				$z = strtotime($secondday);
				$c = strtotime($thired);



				$t1 = ($z - $x);
				$t2 = ($z - $c);
				$t3 = ($y - $c);

				$diff = $t1 + $t2 + $t2 + $t2 + $t3;

				$total = $diff / 60;
				$totalhours = floor($total / 60);
				$finalhours = $totalhours;
				$finaltime =  sprintf("%02dh %02dm", $finalhours, $total % 60);
			}

			$row['yatraTime'] = $finaltime;





			$res[] =  $row;
		}

		$error = array('status' => 'true', 'msg' => 'Enter Bus Type', 'count' => $rows_count, 'data' => $res);
		echo json_encode($error, JSON_PRETTY_PRINT);




		?>	