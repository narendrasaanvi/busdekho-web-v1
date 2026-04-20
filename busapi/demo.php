       <?php
       $TIme=2;
       date_default_timezone_set('Asia/Kolkata');
       $current_time = date("H:i:s");
       if($TIme==2){
            $current_time = new DateTime();
            $current_time->add(new DateInterval('PT2H'));
            echo $current_time->format('H:i:s');
        }
        if($TIme==4){
            $current_time = new DateTime();
            $current_time->add(new DateInterval('PT4H'));
            echo $current_time->format('H:i:s');
        }
        if($TIme==6){
            $current_time = new DateTime();
            $current_time->add(new DateInterval('PT6H'));
            echo $current_time->format('H:i:s');
        }
        ?>