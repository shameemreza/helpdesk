<?php

class time extends db {
    
    public function ago($time) {
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");
        $now = time();

        $difference     = $now - $time;
        $tense         = 'ago';
        
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        
        $difference = round($difference);
   
        if($difference != 1) {
            $periods[$j].= "s";
        }
        return $difference . " " . $periods[$j] . " ago";
    }
    
    public function registration_time($ip) {
        $_link = $this->getDBH();
        $query = $_link->prepare('SELECT * FROM `registrations` WHERE `ip` = :ip');
        $query->bindParam(':ip', $ip, PDO::PARAM_STR);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        return $result['date'];
        
        if($query->rowCount() == 0) {
            return true;
        } else if(time() - $result['date'] > 86400) {
            return true;
        } else {
            return false;
        }
    }
      
}