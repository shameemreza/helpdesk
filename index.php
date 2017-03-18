<?php 
	include 'init.php'; 
	include 'core/includes/head.php'; 
	if($users->signed_in()){
?>
	
<div class="container">
<div class="row home-hero">
    <div class="six offset-by-three columns">
	    <img src="//gravatar.com/avatar/<?php echo md5($users->getuserinfo('email')); ?>?s=100">
	    <h4>Welcome, <?php echo $users->getuserinfo('nick_name'); ?></h4>
		<ul>
			<li>Last login <?php echo $time->ago($users->getuserinfo('last_login')); ?></li>
		</ul>
	    <a href="./settings" class="button">Edit Account</a>
	    <?php if($users->getuserinfo('user_group') == 1){ echo '<a href="./admin" class="button button-primary">Admin Panel</a>'; };?>
	    <a href="logout.php" class="button button-blank"><span class="entypo-logout"></span></a>
	</div>
</div>

<br><br>

<div class="row home-sections">
    <div class="six columns">
		<div class="section" id="new">
		    <div class="entypo-list-add"></div>
		    <b>Open New Ticket</b>
		    <p>Create or view support tickets to receive responses from our team.</p>
	    </div>
	</div>
	
    <div class="six columns">
		<div class="section" id="current">
			<div class="entypo-list"></div>
		    <b>Current Tickets</b>
		    <p>View and manage any tickets that may have responses from our team.</p>
	    </div>
	</div>
  </div>
 
<div style="display: none" class="section_view_current">
<table class="u-full-width">
  <thead>
    <tr>
      <th>Subject</th>
      <th>Status</th>
      <th>Last Reply</th>
      <th>Recent</th>
    </tr>
  </thead>
  <tbody>
    <?php $tickets->my_tickets(); ?>
  </tbody>
</table>
</div>

<div style="display: none" class="section_view_new">
  <span id="create_ticket_error"></span>
	<form method="" id="create_ticket">
		<div class="row">
			<div class="columns six">
				<label for="subject">Subject</label>
				<input type="text" placeholder="Subject" class="u-full-width" id="subject">
			</div>
			
			<div class="columns six">
				<label for="department">Department</label>
				<select id="department" class="u-full-width">
					<option disabled="disabled">Please select department</option>
					<?php $tickets->get_departments(); ?>
				</select>
			</div>
		</div>
		
		<label for="subject">Message</label>
		<textarea placeholder="Message" id="message" style="min-height:300px;" class="u-full-width"></textarea>
		
		<button type="submit" class="button">Post Ticket</button>
	</form>

</div>
 
</div>

<?php 
	}else{
		header('Location: ./authenticate');
	}
	include 'core/includes/foot.php'; 
?>
