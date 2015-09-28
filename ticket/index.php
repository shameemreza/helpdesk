<?php 
	include '../init.php'; 
	include '../core/includes/head.php';
	
	if(isset($_GET['id']) && $users->signed_in() && $tickets->is_ticket($_GET['id'])) {
	    if(!$tickets->my_ticket($_GET['id'])) {
  	      if(!$users->getuserinfo('user_group') == 1){
    	        header('Location: ../authenticate');
    	        die();
  	      }
	    }
	    $tickets->user_read($_GET['id']);
	    $tickets->admin_read($_GET['id']);
?>	

<div class="container">
	<div class="row">
		<a href="../" class="go-back"><span class="entypo-left-open"></span>Dashboard</a>
		<br><br>
		
		<div class="ticket-insert">
			<div class="columns three">
				<div class="profile">				
					<img src="//gravatar.com/avatar/<?php echo md5($users->idtocolumn($tickets->ticket_info($_GET['id'], 'user'), 'email')); ?>?s=100">
					<ul>
						<li><b><?php echo $users->idtocolumn($tickets->ticket_info($_GET['id'], 'user'), 'nick_name'); ?></b></li>
						<li><?php if($users->idtocolumn($tickets->ticket_info($_GET['id'], 'user'), 'user_group') == 1) { echo 'Administrator'; } else { echo 'General user'; } ?></li>
							<?php if($users->idtocolumn($tickets->ticket_info($_GET['id'], 'user'), 'id') == $_COOKIE['user']) { echo '<li><a href="#">Edit Account</a></li>'; } ?>
					</ul>
				</div>
			</div>
		
			<div class="columns nine">
				<h4><?php echo $tickets->ticket_info($_GET['id'], 'title'); ?></h4>
				<hr>
				<?php echo nl2br($tickets->ticket_info($_GET['id'], 'init_msg')); ?>
				<ul>
					<li><?php if($tickets->ticket_info($_GET['id'], 'resolved') == 0 && $tickets->ticket_info($_GET['id'], 'user') == $_COOKIE['user']) { ?>
					        <a id="no_longer_help" href="#"><span class="entypo-check"></span>I no longer need help</a>
					    <?php } else if($tickets->ticket_info($_GET['id'], 'user') == $_COOKIE['user']) { ?>
					        This ticket has been marked resolved.
					    <?php } else if($users->getuserinfo('user_group') == 1 && $tickets->ticket_info($_GET['id'], 'resolved') == 0) { ?></li>
					        <button id="close_ticket" style="margin-right:15px;">Close ticket</button>
					    <?php } else { ?>
					        Ticket closed
					    <?php } ?>
					<li>Posted <?php echo $time->ago($tickets->ticket_info($_GET['id'], 'date')); ?></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- ticket post end -->	
	
	<hr>
	
	<!-- reply start -->
	<span id="replies">
	    	<?php $tickets->ticket_replies($_GET['id']); ?>
	</span>
	<!-- reply end -->
	
	<div class="row">
		<div class="ticket-insert">
			<div class="columns three">
				<div class="profile">				
					<img src="//gravatar.com/avatar/<?php echo md5($users->getuserinfo('email')); ?>?s=100">
					<ul>
						<li><b><?php echo $users->getuserinfo('nick_name'); ?></b></li>
						<li><?php if($users->getuserinfo('user_group') == 1) { echo 'Administrator'; } else { echo 'General user'; } ?></li>
						<li><a href="#">Edit Account</a></li>
					</ul>
				</div>
			</div>
			<?php 
			    if($tickets->ticket_info($_GET['id'], 'resolved') == 0) {
			?>
		
			<div class="columns nine">
				<form id="reply">
  				<textarea id="rtext" class="u-full-width" placeholder="Enter your reply..."></textarea>
  				<button type="submit">Add Reply</button>
  			</form>
			</div>
			<?php } else { ?>
			<div class="columns nine">
			  <?php if($users->getuserinfo('user_group') == 0) { ?>
			      <div class="alert warning">This ticket has been closed, please open another support ticket for further support.</div>
			  <?php } else { ?>
			       <div class="alert warning">This ticket has been closed.</div>
			  <?php } ?>
				<form>
  				<textarea id="rtext" class="u-full-width" disabled placeholder="Enter your reply..."></textarea>
  				<button disabled>Add Reply</button>
  			</form>
			</div>
			<?php } ?>
		</div>
	</div>
	
</div>

<?php
	} else {
	    header('Location: ../authenticate');
	    die();
	}
	include '../core/includes/foot.php'; 
?>