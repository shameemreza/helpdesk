<?php 
	include '../init.php'; 
	include '../core/includes/head.php';
	if($users->signed_in()) {
	  header('Location: ../');
	  die();
	}
?>	

<div class="container">
	<div class="row">
		<div class="six offset-by-three columns auth-section">
			<h4>Sign in or Register</h4>
			<p>Using the form below please enter your email address and password and we'll get started.</p>
			<form id="auth" action="" method="post">
				<div class="row">
					<div class="six columns">
					    <input type="radio" id="radio1" name="type" value="returning_user" checked="checked">
	    				<label class="user-type" for="radio1">
	    					<b>Returning User</b>
	    					<p>If you are registered before please sign in below.</p>
	    				</label>
    				</div>
				
					<div class="six columns">
					    <input type="radio" id="radio2" name="type" value="new_user">
	    				<label class="user-type" for="radio2">
	    					<b>New User</b>
	    					<p>If you're not already signed up then you'll need to do so</p>
	    				</label>
    				</div>
				</div>
        <span id="alerts"></span>
				<label for="email">Email Address</label>
				<input class="u-full-width" type="email" name="email" required autocomplete="off" placeholder="you@domain.com" id="email">
				
				<label for="password">Password</label>
				<input class="u-full-width" type="password" name="password" required placeholder="Keep it secure" id="password">
				
				<button class="button" name="submit" type="submit">Authenticate</button>
			</form>
		</div>
	</div>
</div>

<?php 
	include '../core/includes/foot.php'; 
?>