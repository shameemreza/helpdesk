<?php 
    include '../init.php'; 
    include '../core/includes/head.php'; 
    if($users->getuserinfo('user_group') == 1){
	if (isset($_GET['user'])){
	  if(!$admin->user_exists($_GET['user'])) {
	      header('Location: ../');
	      die();
	  }
?>
	
    <div class="container">
        <div class="row">
            <div class="columns four admin-menu">
                <?php $admin_menu_active = "user_management"; include 'admin_menu.php'; ?>
            </div>

            <div class="columns eight admin-edit-user">
                <h4>Edit user account &mdash; <?php echo $users->idtocolumn($_GET['user'], 'nick_name') ?></h4>
                <span id="errors"></span>
                <table class="u-full-width">
                    <thead>
                        <tr>
                            <th>Type</th>

                            <th>Data</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Unique ID</td>

                            <td><?php echo $users->idtocolumn($_GET['user'], 'id'); ?></td>
                        </tr>

                        <tr>
                            <td>Nickname</td>

                            <td><?php echo $users->idtocolumn($_GET['user'], 'nick_name') ?></td>
                        </tr>

                        <tr>
                            <td>Email Address</td>

                            <td><?php echo $users->idtocolumn($_GET['user'], 'email') ?></td>
                        </tr>

                        <tr>
                            <td>Registered</td>

                            <td><?php echo $time->ago($users->idtocolumn($_GET['user'], 'sign_up_date')); ?></td>
                        </tr>

                        <tr>
                            <td>Last Login</td>

                            <td><?php echo $time->ago($users->idtocolumn($_GET['user'], 'last_login')); ?></td>
                        </tr>

                        <tr>
                            <td>Website URL</td>

                            <td><a href="http://google.com/" target="_blank"><?php echo $users->idtocolumn($_GET['user'], 'url') ?></a></td>
                        </tr>

                        <tr>
                            <td>Number of Tickets Open</td>

                            <td><?php echo $admin->user_ticket_info($_GET['user'], 'open'); ?></td>
                        </tr>

                        <tr>
                            <td>Number of Tickets Resolved</td>

                            <td><?php echo $admin->user_ticket_info($_GET['user'], 'resolved'); ?></td>
                        </tr>

                        <tr>
                            <td>Number of Tickets Unanswered</td>

                            <td><?php echo $admin->user_ticket_info($_GET['user'], 'unanswered'); ?></td>
                        </tr>
                        
						<tr>
                            <td>Most Recent IP</td>

                            <td><?php echo $users->idtocolumn($_GET['user'], 'most_recent_ip') ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="columns four">
                        <label for="nickname">Nickname</label>
                    </div>

                    <div class="columns six">
                        <input type="text" id="nickname" autocomplete="off" placeholder="Modify Nickname" value="<?php echo $users->idtocolumn($_GET['user'], 'nick_name'); ?>" class="u-full-width">
                    </div>

                    <div class="columns two">
                        <button id="admin_update_nickname" class="button button-blank u-full-width">Update</button>
                    </div>
                </div>

                <div class="row">
                    
                    <div class="columns four">
                        <label for="email">Email Address</label>
                    </div>
                    <div class="columns six">
                        <input type="text" id="email" autocomplete="off" placeholder="Modify Nickname" value="<?php echo $users->idtocolumn($_GET['user'], 'email'); ?>" class="u-full-width">
                    </div>

                    <div class="columns two">
                        <button id="admin_update_email" class="button button-blank u-full-width">Update</button>
                    </div>
                </div>

                <div class="row">
                    <div class="columns four">
                        Actions
                    </div>

                    <div class="columns four">
                        <?php
                            if($users->idtocolumn($_GET['user'], 'user_group') == 1) {
                        ?>
                            <button class="button u-full-width" disabled>User is admin</button>
                        <?php
                            } else {
                        ?>
                            <button class="button u-full-width" id="make_admin">Make Admin</button>
                        <?php
                            }
                        ?>
                    </div>

                    <div class="columns four">
                      
                        <?php
                            if($users->idtocolumn($_GET['user'], 'allowed') == 1) {
                        ?>
                            <button class="button u-full-width" id="change_access">Access denied</button>
                        <?php
                            } else {
                        ?>
                            <button class="button u-full-width" id="change_access">Don't Allow Access</button>
                        <?php
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php }else{ ?>

    <div class="container">
        <div class="row">
            <div class="columns four admin-menu">
                <?php $admin_menu_active = "user_management"; include 'admin_menu.php'; ?>
            </div>

            <div class="columns eight user_management">
                <table class="u-full-width">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nickname</th>
                            <th>Email Address</th>
                            <th>.</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $admin->all_users(); ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
    
<?php 
	}
	}else{
        header('Location: ../');
	}
    
    include '../core/includes/foot.php'; 
?>