<?php 
	include '../init.php'; 
	include '../core/includes/head.php'; 
	if($users->getuserinfo('user_group') == 1){
?>
	
<div class="container">
	<div class="columns four admin-menu">
		<?php $admin_menu_active = "ticket_management"; include 'admin_menu.php'; ?>
	</div>
	
	<div class="columns eight">		
		<table class="u-full-width">
			<thead>
				<tr>
					<th>Subject</th>
					<th>Author</th>
					<th>Last Reply</th>
					<th>Department</th>
    			</tr>
  			</thead>
  			<tbody>
  				<?php $admin->all_tickets(); ?>
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