<?php

class admin extends db {
  
    public function count_admins() {
        
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `users` WHERE `user_group` = 1');
        $query->execute();
        
        return $query->rowCount();
    }
    
    public function count_users() {
      
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `users`');
        $query->execute();
        
        return $query->rowCount();  
      
    }
    
    public function count_departments() {
      
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `departments`');
        $query->execute();
        
        return $query->rowCount();  
      
    }
    
    public function count_tickets() {
      
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `tickets`');
        $query->execute();
        
        return $query->rowCount();  
      
    }
    
    public function count_tickets_resolved() {
      
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `tickets` WHERE `status` = 2');
        $query->execute();
        
        return $query->rowCount();  
      
    }
    
    public function all_tickets() {
      
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `tickets`');
        $query->execute();
        
        if($query->rowCount() < 1) {
            
            echo '<div class="alert">No tickets have been opened yet.</div>';
            
        } else {
          
            while($result = $query->fetch(PDO::FETCH_ASSOC)):
              
              	 $department_query = $_link->query('SELECT * FROM `departments` WHERE `id`='.$result['department'].'');
      			  	 $department_query->execute();
      			  	 $department_result = $department_query->fetch(PDO::FETCH_ASSOC);
      			  	 
      			  	 $lastreply_query = $_link->query('SELECT * FROM `users` WHERE `id`='.$result['last_reply'].'');
      			  	 $lastreply_query->execute();
      			  	 $lastreply_result = $lastreply_query->fetch(PDO::FETCH_ASSOC);
      			  	 
      			  	 $author_query = $_link->query('SELECT * FROM `users` WHERE `id`='.$result['user'].'');
      			  	 $author_query->execute();
      			  	 $author_result = $author_query->fetch(PDO::FETCH_ASSOC);
      			  	 
      			  	 if($result['admin_read'] == 0) {
                      echo '<tr style="font-weight:bold;">';
                  } else {
                      echo '<tr>';
                  }
      			  	 
                  echo '
            	  				<td><a href="../ticket?id='.$result['uniqid'].'">'.$result['title'].'</a></td>
            	  				<td>'.$author_result['nick_name'].'</td>
            	  				<td>'.$lastreply_result['nick_name'].'</td>
            	  				<td>'.$department_result['name'].'</td>
            	  			</tr>';
              
            endwhile;
          
        }
        
    }
    
    public function all_users() {
        
        $_link = $this->getDBH();
        
        $query = $_link->query('SELECT * FROM `users`');
        $query->execute();
          
        while($result = $query->fetch(PDO::FETCH_ASSOC)):
              
      			echo '<tr>
      			        <td>'.$result['id'].'</td>
      			        <td>'.$result['nick_name'].'</td>
      			        <td>'.$result['email'].'</td>
      			        <td><a href="?user='.$result['id'].'"><span class="entypo-cog"></span></a></td>
      			    </tr>';
			
        endwhile;   
    }
    
    public function all_departments(){
	
	      $_link = $this->getDBH();
	    
	      $query = $_link->query('SELECT * FROM `departments` ORDER BY `id` ASC');
        $query->execute();
          
        while($result = $query->fetch(PDO::FETCH_ASSOC)):
              
            echo '<tr>
					        <td>'.$result['id'].'</td>
  					      <td>'.$result['name'].'</td>
  					      <td><a href="?department='.$result['id'].'"><span class="entypo-cog"></span></a></td>
  				        </tr>';
    
        endwhile;
	}
	    
	public function create_department($dpt){
	
	    $_link = $this->getDBH();
	    $errors = array();
	    $dpt = strip_tags($dpt);
			
			if(empty($dpt)){
			    $errors[] = 'You must enter a department name to create one.'; 
			}
			if(strlen($dpt) > 50){
			    $errors[] = 'Department cannot be more than 50 characters.';
			}
			
			if(count($errors) == 0){
			    $query = $_link->prepare('INSERT INTO `departments`() VALUES(NULL, :department, 0)');
          $query->bindParam(':department', $dpt, PDO::PARAM_STR);
		  	  $query->execute();
			
			    echo '<div class="alert success">Department has been added successfully.</div>';
			
			}else{
				  echo '<div class="alert">'.implode("<br>",$errors).'</div>';
			}
	}
	
	public function settings($function, $status) {
	  
	    $_link = $this->getDBH();
	    
	    switch($function) {
	        case 'signin':
	          if($status == 1) {
	              $query = $_link->query('UPDATE `admin_settings` SET `allow_user_sign_in` = 1 WHERE `id` = 1');
	              $query->execute();
	          } else {
	              $query = $_link->query('UPDATE `admin_settings` SET `allow_user_sign_in` = 0 WHERE `id` = 1');
	              $query->execute();
	          }
	          break;
	        case 'delete':
	          if($status == 1) {
	              $query = $_link->query('UPDATE `admin_settings` SET `self_delete_account` = 1 WHERE `id` = 1');
	              $query->execute();
	          } else {
	              $query = $_link->query('UPDATE `admin_settings` SET `self_delete_account` = 0 WHERE `id` = 1');
	              $query->execute();
	          }
	          break;
	        case 'register':
	          if($status == 1) {
	              $query = $_link->query('UPDATE `admin_settings` SET `allow_new_user_register` = 1 WHERE `id` = 1');
	              $query->execute();
	          } else {
	              $query = $_link->query('UPDATE `admin_settings` SET `allow_new_user_register` = 0 WHERE `id` = 1');
	              $query->execute();
	          }
	          break;
	       case 'protection':
	          if($status == 1) {
	              $query = $_link->query('UPDATE `admin_settings` SET `enable_protection` = 1 WHERE `id` = 1');
	              $query->execute();
	          } else {
	              $query = $_link->query('UPDATE `admin_settings` SET `enable_protection` = 0 WHERE `id` = 1');
	              $query->execute();
	          }
	          break;
	    }
	}
	
  	public function get_settings() {
  	    
  	    $_link = $this->getDBH();
  	    
  	    $query = $_link->query('SELECT * FROM `admin_settings` WHERE `id` = 1');
        $query->execute();
        
        while($result = $query->fetch(PDO::FETCH_ASSOC)):
        
            echo $result['self_delete_account'] . ' ' .$result['allow_user_sign_in'] . ' ' . $result['allow_new_user_register'] . ' ' . $result['enable_protection'];
        
        endwhile;
  	}
	
	  public function user_ticket_info($id, $option) {
        
        $_link = $this->getDBH();
        
        switch($option) {
            case 'open':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :id AND `resolved` = 0');
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount();
                break;
            case 'resolved':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :id AND `resolved` = 1');
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount();
                break;
            case 'unanswered':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `user` = :id AND `last_reply` = :id');
                $query->bindParam(':id', $id, PDO::PARAM_INT);
                $query->execute();
                return $query->rowCount();
                break;
        }
    }
    
    public function ticket_info_total($option) {
       
        $_link = $this->getDBH();
        
        switch($option) {
            case 'open':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `resolved` = 0');
                $query->execute();
                return $query->rowCount();
                break;
            case 'resolved':
                $query = $_link->prepare('SELECT * FROM `tickets` WHERE `resolved` = 1');
                $query->execute();
                return $query->rowCount();
                break;
            case 'unanswered':
                $query = $_link->prepare('SELECT f1.* FROM tickets f1 JOIN tickets f2 ON f1.user = f2.last_reply');
                $query->execute();
                
                $res = $query->rowCount();
                return $res;
                break;
        }
    }
    
    public function department_info($id, $field) {
      
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `departments` WHERE `id` = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() != 0) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
        
            return $result[$field];
        }
    }
    
    public function delete_department($department) {
      
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `departments` WHERE `id` = :id');
        $query->bindParam(':id', $department, PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() != 0) {
            
            $query = $_link->prepare('DELETE FROM `departments` WHERE `id` = :id');
            $query->bindParam(':id', $department, PDO::PARAM_INT);
            $query->execute();
            
            $query = $_link->prepare('DELETE FROM `tickets` WHERE `department` = :id');
            $query->bindParam(':id', $department, PDO::PARAM_INT);
            $query->execute();
            
            echo 'success';
        }
        
        
    }
    
    public function update_department($department, $update) {
      
        $_link = $this->getDBH();
        $update = strip_tags($update);
        
        $query = $_link->prepare('SELECT * FROM `departments` WHERE `id` = :id');
        $query->bindParam(':id', $department, PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() != 0) {
        
            $query = $_link->prepare('UPDATE `departments` SET `name` = :update WHERE `id` = :id');
            $query->bindParam(':id', $department, PDO::PARAM_INT);
            $query->bindParam(':update', $update, PDO::PARAM_STR);
            $query->execute();
            
            echo '<div class="alert success">Your department has been updated successfully.</div>';
        }
    }
    
    public function department_exists($department) {
      
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `departments` WHERE `id` = :id');
        $query->bindParam(':id', $department, PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() > 0) {
            return true;
        }
    }
    
    public function update_nickname($user, $update) {
      
        $users = new users;
        $update = strip_tags($update);
        
        if($users->getuserinfo('user_group') == 1) {
          
            $_link = $this->getDBH();
            
            $query = $_link->prepare('UPDATE `users` SET `nick_name` = :update WHERE `id` = :id');
            $query->bindParam(':id', $user, PDO::PARAM_INT);
            $query->bindParam(':update', $update, PDO::PARAM_STR);
            $query->execute();
            
            echo 'success';
        }
    }
    
    public function update_email($user, $update) {
      
        $users = new users;
        $update = strip_tags($update);
        
        if($users->getuserinfo('user_group') == 1) {
          
            $_link = $this->getDBH();
            
            $query = $_link->prepare('UPDATE `users` SET `email` = :update WHERE `id` = :id');
            $query->bindParam(':id', $user, PDO::PARAM_INT);
            $query->bindParam(':update', $update, PDO::PARAM_STR);
            $query->execute();
            
            echo 'success';
        }
    }
    
    public function make_admin($user, $admin_id) {
      
        $users = new users;
        
        if($users->getuserinfo('user_group') == 1 && $users->idtocolumn($admin_id, 'user_group') == 1) {
          
            $_link = $this->getDBH();
            
            $query = $_link->prepare('UPDATE `users` SET `user_group` = 1 WHERE `id` = :id');
            $query->bindParam(':id', $user, PDO::PARAM_INT);
            $query->execute();
            
            echo 'success';
        }
    }
    
    public function change_block($user) {
      
        $users = new users;
        $_link = $this->getDBH();
        
        if($users->idtocolumn($user, 'allowed') == 1) {
            
            $query = $_link->prepare('UPDATE `users` SET `allowed` = 0 WHERE `id` = :id');
            $query->bindParam(':id', $user, PDO::PARAM_INT);
            $query->execute();
            
            echo 'success';
        } else {
          
            $query = $_link->prepare('UPDATE `users` SET `allowed` = 1 WHERE `id` = :id');
            $query->bindParam(':id', $user, PDO::PARAM_INT);
            $query->execute();
            
            echo 'success';
        }
    }
    
    public function user_exists($user) {
      
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $query->bindParam(':id', $user, PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function close_ticket($ticket) {
        $tickets = new tickets;
        $users = new users;
        $_link = $this->getDBH();
        if($tickets->is_ticket($ticket) && $users->getuserinfo('user_group') == 1) {
            $query = $_link->prepare('UPDATE `tickets` SET `resolved` = 1 WHERE `uniqid` = :ticket');
            $query->bindParam(':ticket', $ticket, PDO::PARAM_STR);
            $query->execute();
            
            echo 'success';
        }
    }
  
}