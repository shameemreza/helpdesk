<?php

class users extends db {
  
    public $email_err, $password_err;
    
    public function auth($email, $password, $type) {
        
        $_link = $this->getDBH();
    
    			  if($email != '' && $password != '' && $type != '') {
    
    				    $query = $_link->prepare('SELECT * FROM `users` WHERE `email` = :email');
    				    $query->bindParam(':email', $email, PDO::PARAM_STR);
    				    $query->execute();
    
    				    $res = $query->fetch(PDO::FETCH_ASSOC);
                
                  
                    if($type == 'returning_user') {
                        
                        if($this->site_settings('allow_user_sign_in') == 0) {
                            
                            echo 'Unfortunately the sign in function has been disabled by staff';
                        } else {
                            
                            if($query->rowCount() > 0) {
                          
                              if(password_verify($password, $res['password'])) {
                                  if($res['allowed'] != 0) {
                                      echo 'Your account seems to be locked by an adminstrator.';
                                  } else {
                                    $date = new DateTime();
                                    $date = $date->getTimestamp();
                                    $ip_address = $_SERVER['REMOTE_ADDR'];
                                    
                                    $query = $_link->prepare('UPDATE `users` SET `last_login` = :login, `most_recent_ip` = :ip WHERE `id` = :id');
                                    $query->bindParam(':login', $date, PDO::PARAM_STR);
                                    $query->bindParam(':ip', $ip_address, PDO::PARAM_STR);
                                    $query->bindParam(':id', $res['id'], PDO::PARAM_INT);
                                    $query->execute();
                                    
                                    setcookie('user', $res['id'], time() + 999999, '/');
                                    echo 'success';
                                }
                              } else {
                                  
                                 echo 'Incorrect password.'; 
                                  
                              }
                              
                            } else {
                                echo 'That email address has not been registered with us.';
                            }
                            
                        }
                        
                    } else if($type == 'new_user') {
                      
                        $date = new DateTime();
                        $date = $date->getTimestamp();
                      
                        if($this->site_settings('allow_new_user_register') == 0) {
                            
                            echo 'Unfortunately the registration function has been disabled by staff';
                        } else {
                            $time = new time;
                            if($this->site_settings('enable_protection') == 1 && $time->registration_time($_SERVER['REMOTE_ADDR'])) {
                              
                              echo 'Unfortunately you can only create an account every 24 hours from one ip address.';
                            } else {
                                if($query->rowCount() < 1) {
                                  if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                      
                                      $options = [
                                          'cost' => 12,
                                      ];
                                      $password = password_hash($password, PASSWORD_BCRYPT, $options);
                                      
                                      
                                      $url = "";
                                      
                                      $nickname = explode('@', $email);
                                      $ip_address = $_SERVER['REMOTE_ADDR'];
                                      
                                          $query = $_link->prepare('INSERT INTO `users`() VALUES(NULL, :email, :password, :date, :nick, 0, :login, :url, 0, :ip)');
                                          $query->bindParam(':email', $email, PDO::PARAM_STR);
                                          $query->bindParam(':password', $password, PDO::PARAM_STR);
                                          $query->bindParam(':date', $date, PDO::PARAM_STR);
                                          $query->bindParam(':nick', $nickname[0], PDO::PARAM_STR);
                                          $query->bindParam(':login', $date, PDO::PARAM_STR);
                                          $query->bindParam(':url', $url, PDO::PARAM_STR);
                                          $query->bindParam(':ip', $ip_address, PDO::PARAM_STR);
                                          $query->execute();
                                          
                                          $query = $_link->prepare('INSERT INTO `registrations`() VALUES(NULL, :ip, :date)');
                                          $query->bindParam(':ip', $ip_address, PDO::PARAM_STR);
                                          $query->bindParam(':date', $date, PDO::PARAM_STR);
                                          $query->execute();
                                          
                                          $query = $_link->prepare('SELECT * FROM `users` WHERE `email` = :email');
                              				    $query->bindParam(':email', $email, PDO::PARAM_STR);
                              				    $query->execute();
                          
                          				        $res = $query->fetch(PDO::FETCH_ASSOC);
                          				    
                          				        if($res['id'] == 1) {
                          				            $query = $_link->prepare('UPDATE `users` SET `user_group` = 1 WHERE `id` = :id');
                                              $query->bindParam(':id', $res['id'], PDO::PARAM_INT);
                                              $query->execute();
                          				        }
                                      
                                      setcookie('user', $res['id'], time() + 999999, '/');
                                      
                                      echo 'success';
                                      
                                  } else {
                                    
                                      echo 'The email address you have entered is invalid.';
                                    
                                  }
                                } else {
                                  echo 'That email address is already registered with us.';
                                }
                            }
                      
                            
                        }
                    }
    				    
    			  } else {
    
    				    echo 'Please fill in all fields.';
    				    
    			  }
    		
    }
    
    public function signed_in() {
        
        if(isset($_COOKIE['user']))
            return true;
        
    }
    
    public function getuserinfo($field) {
      
        $_link = $this->getDBH();
        $user_id = $_COOKIE['user'];
        
        $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $query->bindParam(':id', $user_id, PDO::PARAM_INT);
        $query->execute();
        
        $res = $query->fetch(PDO::FETCH_ASSOC);
        
        return $res[$field];
      
    }
    
    public function remove_url() {
      
        $_link = $this->getDBH();
        
        $url = "";
            
        $query = $_link->prepare('UPDATE `users` SET `url` = :url WHERE `id` = :id');
        $query->bindParam(':url', $url, PDO::PARAM_STR);
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
            
        echo 'success';
        
    }
    
    public function change_url($url) {
        
        $_link = $this->getDBH();
            
        if(empty($url)) {
                
            echo '<div class="alert error">Please fill in a URL.</div>';
                
        } else if(filter_var($url, FILTER_VALIDATE_URL)) {
              
            $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
            $query->bindParam(':id', $_COOKIE['users'], PDO::PARAM_INT);
            $query->execute();
                
            $result = $query->fetch(PDO::FETCH_ASSOC);
                
            if($result['url'] != $url) {
                  
                $query = $_link->prepare('UPDATE `users` SET `url` = :url WHERE `id` = :id');
                $query->bindParam(':url', $url, PDO::PARAM_STR);
                $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
                $query->execute();
                    
                echo '<div class="alert success">Your URL has been updated successfully.</div>';
                  
            }
              
        } else {
              
            echo '<div class="alert error">The URL you have entered is invalid.</div>';
              
        }
        
    }
    
    public function change_nickname($nickname) {
        $nickname = strip_tags($nickname);
        $_link = $this->getDBH();
        
        if($nickname != '') {
                
            $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
            $query->bindParam(':id', $_COOKIE['users'], PDO::PARAM_INT);
            $query->execute();
                
            $result = $query->fetch(PDO::FETCH_ASSOC);
                
            if($result['nick_name'] != $nickname) {
                  
                $query = $_link->prepare('UPDATE `users` SET `nick_name` = :nick WHERE `id` = :id');
                $query->bindParam(':nick', $nickname, PDO::PARAM_STR);
                $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
                $query->execute();
                    
                echo '<div class="alert success">Your nickname has been updated successfully.</div>';
              }
                
          } else {
              echo '<div class="alert error">Please fill in a nickname.</div>';
          }

    }
    
    public function change_password($new, $current) {
      
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_STR);
        $query->execute();
            
        $result = $query->fetch(PDO::FETCH_ASSOC);
            
        if(password_verify($current, $result['password']) && !empty($new)) {
                
            $options = [
                'cost' => 12,
            ];
            $new = password_hash($new, PASSWORD_BCRYPT, $options);
                
            $query = $_link->prepare('UPDATE `users` SET `password` = :password WHERE `id` = :id');
            $query->bindParam(':password', $new, PDO::PARAM_STR);
            $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
            $query->execute();
                
            echo 'success';
                
        } else {
              
            echo 'Please fill in all fields.';
              
        }
            
    }
    
    public function change_email($new) {
        
        $_link = $this->getDBH();

    		if(filter_var($new, FILTER_VALIDATE_EMAIL)) {
    				  
    		    $query = $_link->prepare('UPDATE `users` SET `email` = :email WHERE `id` = :id');
            $query->bindParam(':email', $new, PDO::PARAM_STR);
            $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
            $query->execute();
            				
            echo '<div class="alert success">Your email address has been updated successfully.</div>';
    				        
    		} else {
    				    
    				echo '<div class="alert error">The email address you have entered is invalid.</div>';
    				    
    		}
    }
    
    public function idtocolumn($id, $column) {
      $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
                
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        return $result[$column];
    }
    
    public function delete_me() {
        
        $_link = $this->getDBH();
        
        $query = $_link->prepare('DELETE FROM `users` WHERE `id` = :id');
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
        
        $query = $_link->prepare('DELETE FROM `tickets` WHERE `user` = :id');
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
        
        $query = $_link->prepare('DELETE FROM `ticket_replies` WHERE `user` = :id');
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
        
        setcookie('user', '', time() - 999999, '/');
        
        echo 'success';
    }
    
    public function account_exists() {
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() < 1) {
            setcookie('user', '', time() - 999999, '/');
            header('Location: ../');
            die();
        }
    }
    
    public function is_locked() {
        $_link = $this->getDBH();
        
        $query = $_link->prepare('SELECT * FROM `users` WHERE `id` = :id AND `allowed` = 1');
        $query->bindParam(':id', $_COOKIE['user'], PDO::PARAM_INT);
        $query->execute();
        
        if($query->rowCount() < 1) {
            return false;
        } else {
            setcookie('user', '', time() - 999999, '/');
            header('Location: ../');
            die();
        }
    }
}