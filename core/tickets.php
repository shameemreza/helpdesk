<?php

class tickets extends db {
  
    public function __construct() {
        

        
    }
  
    public function my_tickets() {
        
        $_link = $this->getDBH();
        $time = new time;
        $users = new users;
        
        $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :user');
        $query->bindParam(':user', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
        
        while($result = $query->fetch(PDO::FETCH_ASSOC)):
            
            if($result['user_read'] < 1) {
              
                echo  '<tr style="font-weight:bold;word-wrap:break-word;">';
                
            } else {
              
                echo  '<tr>';
            
            }
            
            echo  '<td><a href="ticket/?id=' . $result['uniqid'] .'">' . $result['title'] . '</a></td>';
            
            if($result['resolved'] == 1) {
                echo  '<td><span class="entypo-check"></span></td>';
            } else if($result['resolved'] == 0) {
                echo  '<td><span class="entypo-comment"></span></td>';
            }
            
            if($result['last_reply'] == $_COOKIE['user']) {
              
                echo  '<td>Me</td>';
              
            } else {
              
                echo  '<td>' . $users->idtocolumn($result['last_reply'], 'nick_name') . '</td>';
              
            }
            
            echo  '<td>' . $time->ago($result['date']) . '</td>';
            echo  '</tr>';
            
        endwhile;
      
    }
    
    public function ticket_info($ticket, $field) {
      
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `tickets` WHERE `uniqid` = :uniqid');
        $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        return $result[$field];
        
    }
    
    public function is_ticket($ticket) {
        
        $_link = $this->getDBH();
      
        $query = $_link->prepare('SELECT * FROM `tickets` WHERE `uniqid` = :uniqid');
        $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
        $query->execute();
        
        if($query->rowCount() > 0) {
            
            return true;
          
        } else {
          
            return false;
          
        }
    }
    
    public function my_ticket($ticket) {
        $_link = $this->getDBH();
      
        $query = $_link->prepare('SELECT * FROM `tickets` WHERE `uniqid` = :uniqid');
        $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if($result['user'] == $_COOKIE['user']) {
            
            return true;
          
        } else {
          
            return false;
          
        }
    }
    
    public function user_read($ticket) {
        
        $_link = $this->getDBH();
      
        $query = $_link->prepare('UPDATE `tickets` SET `user_read` = 1 WHERE `uniqid` = :uniqid');
        $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
        $query->execute();
        
    }
    
    public function admin_read($ticket) {
        $users = new users;
        if($users->getuserinfo('user_group') == 1 && $this->ticket_info($ticket, 'user') != $_COOKIE['user']) {
            $_link = $this->getDBH();
      
            $query = $_link->prepare('UPDATE `tickets` SET `admin_read` = 1 WHERE `uniqid` = :uniqid');
            $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
            $query->execute();
        }
    }
    
    public function reply($user, $ticket, $text) {
      $_link = $this->getDBH();
        $text = strip_tags($text);
        if(trim($text) != '') {
            $date = new DateTime();
            $date = $date->getTimestamp();
            
            $query = $_link->prepare('INSERT INTO `ticket_replies`() VALUES(NULL, :user, :text, :ticket, :date)');
            $query->bindParam(':user', $_COOKIE['user'], PDO::PARAM_INT);
            $query->bindParam(':text', $text, PDO::PARAM_STR);
            $query->bindParam(':ticket', $ticket, PDO::PARAM_STR);
            $query->bindParam(':date', $date, PDO::PARAM_STR);
            $query->execute();
            
            $query = $_link->prepare('UPDATE `tickets` SET `last_reply` = :id WHERE `uniqid` = :uniqid');
            $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
            $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
            $query->execute();
            
            echo 'success';
        } else {
            echo '<div class="alert error">Please enter a comment.</div>';
        }
        
    }
    
    public function ticket_replies($ticket) {
      
        $_link = $this->getDBH();
      
        $query = $_link->prepare('SELECT * FROM `ticket_replies` WHERE `ticket_id` = :uniqid');
        $query->bindParam(':uniqid', $ticket, PDO::PARAM_STR);
        $query->execute();
        
        $users = new users;
        $time = new time;
        
        while($result = $query->fetch(PDO::FETCH_ASSOC)):
            if($users->idtocolumn($result['user'], 'user_group') == 1) {
                $level = 'Administrator';
            } else {
                $level = 'General user';
            }
            echo '<div class="row">
                		<div class="ticket-insert">
                			<div class="columns three">
                				<div class="profile">				
                					<img src="//gravatar.com/avatar/' . md5($users->idtocolumn($result['user'], 'email')) . '?s=100">
                					<ul>
                						<li><b>' . $users->idtocolumn($result['user'], 'nick_name') . '</b></li>
                						<li>' . $level . '</li>';
                						
                						if($users->idtocolumn($result['user'], 'id') == $_COOKIE['user']) { echo '<li><a href="#">Edit Account</a></li>'; }
                					  
                					echo '</ul>
                				</div>
                			</div>
                		
                			<div class="columns nine">
                				<div class="reply-text-wrap" style="word-wrap:break-word;">
                				' . $result['text'] . '
                				<ul>
                					
                					<li>Posted ' . $time->ago($result['date']) . '</li>
                				</ul>
                				</div>
                			</div>
                		</div>
                	</div>';
            
        endwhile;
    }
    
    public function get_departments() {
        
        $_link = $this->getDBH();
      
        $query = $_link->query('SELECT * FROM `departments`');
        $query->execute();
        
         while($result = $query->fetch(PDO::FETCH_ASSOC)):
            
            echo '<option value="' . $result['id'] . '">' . $result['name']  . '</option>';
            
        endwhile;
    }
    
    public function create($subject, $department, $message) {
            
            $_link = $this->getDBH();
            
            if($subject != '' && $message != '') {
                
                $date = new DateTime();
                $date = $date->getTimestamp();
                $uniqid = uniqid();
                
                $message = strip_tags($message);
              
                $query = $_link->prepare('INSERT INTO `tickets`() VALUES(NULL, :uniqid, :user, :title, :init_msg, :dpt, :date, :last_reply, 1, 0, 0)');
                $query->bindParam(':uniqid', $uniqid, PDO::PARAM_STR);
                $query->bindParam(':user', $_COOKIE['user'], PDO::PARAM_INT);
                $query->bindParam(':title', $subject, PDO::PARAM_STR);
                $query->bindParam(':init_msg', $message, PDO::PARAM_STR);
                $query->bindParam(':dpt', $department, PDO::PARAM_STR);
                $query->bindParam(':date', $date, PDO::PARAM_STR);
                $query->bindParam(':last_reply', $_COOKIE['user'], PDO::PARAM_INT);
                $query->execute();
                
                echo 'success ' . $uniqid;
            } else {
              echo '<div class="alert error">Please fill in all fields.</div>';
            }
    }
    
    public function my_tickets_info($option) {
        
        $_link = $this->getDBH();
        
        switch($option) {
            case 'open':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :id');
                $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount();
                break;
            case 'resolved':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :id AND `resolved` = 1');
                $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount();
                break;
            case 'unanswered':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :id AND `last_reply` = :id');
                $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount();
                break;
        }
    }
    
    public function ticket_resolved($ticket) {
        $_link = $this->getDBH();
        if($this->is_ticket($ticket) && $this->ticket_info($ticket, 'user') == $_COOKIE['user']) {
            $query = $_link->prepare('UPDATE `tickets` SET `resolved` = 1 WHERE `uniqid` = :ticket');
            $query->bindParam(':ticket', $ticket, PDO::PARAM_STR);
            $query->execute();
            
            echo 'success';
        }
    }

}