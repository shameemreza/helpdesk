<?php 
	include '../init.php'; 
	include '../core/includes/head.php'; 
	if($users->getuserinfo('user_group') == 1){
?>
	
<div class="container">
	<div class="columns four admin-menu">
		<?php $admin_menu_active = "dashboard"; include 'admin_menu.php'; ?>
	</div>
	
	<div class="columns eight admin_dash admin-ticket-chart">	
		
		<div class="row">
			<div class="columns four">
				<a href="ticket_management.php">
					<div class="stat">
						<h3><?php echo $admin->count_tickets(); ?></h3>
						<h5>Tickets</h5>
					</div>
				</a>
			</div>
			
			<div class="columns four">
				<a href="user_management.php">
					<div class="stat">
						<h3><?php echo $admin->count_users(); ?></h3>
						<h5>Users</h5>
					</div>
				</a>
			</div>
		
			<div class="columns four">
				<a href="site_management.php">
					<div class="stat">
						<h3><?php echo $admin->count_departments(); ?></h3>
						<h5>Departments</h5>
					</div>
				</a>
			</div>
		</div>
				
		<hr>
		
		<table class="u-full-width">
		  <thead>
		  <tbody>
		    <tr>
		      <td>Number of Administrators</td>
		      <td><?php echo $admin->count_admins(); ?></td>
		    </tr>
		    <tr>
		      <td>Number of Users</td>
		      <td><?php echo $admin->count_users(); ?></td>
		    </tr>
		    <tr>
		      <td>Total Number of Tickets</td>
		      <td><?php echo $admin->count_tickets(); ?></td>
		    </tr>
			<tr>
		      <td>Total Number of Unanswered Tickets</td>
		      <td><?php echo $admin->ticket_info_total('unanswered'); ?></td>
		    </tr>
			<tr>
		      <td>Total Number of Open Tickets</td>
		      <td><?php echo $admin->ticket_info_total('open'); ?></td>
		    </tr>
			<tr>
		      <td>Total Number of Resolved Tickets</td>
		      <td><?php echo $admin->ticket_info_total('resolved'); ?></td>
		    </tr>
			<tr>
		      <td>Site Date</td>
			  <td><?php echo date('d/m/Y'); ?></td>
		    </tr>
		    <tr>
		      <td>Site Time</td>
			  <td><?php echo date('h:i:s a', time()); ?></td>
		    </tr>
		    <tr>
		      <td>Site Timezone</td>
			  <td><?php echo date_default_timezone_get(); ?></td>
		    </tr>
		  </tbody>
		</table>
		
	</div> 
</div>
		
<?php 
	}else{
		header('Location: ../');
	}
	include '../core/includes/foot.php'; 
?>