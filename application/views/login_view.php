<body style="background-image: url('<?php echo base_url();?>asset/sky.jpg')">
<h1 class = "text-info text-center">Login</h1>
<div class = "container text-center" style="border: 2px solid #32687b; width: 70%">

	<?php echo form_open('login');?>
	<br>	
	<div class = "form-group form-inline control-group info">
	<strong>Username: </strong><input type="text" class="form-control" name="email" placeholder="email" value="<?php echo set_value('email')?>">
	<div class="error" id="email_error"><?php echo form_error('email')?></div>
	</div>
		
	<div class = "form-group form-inline control-group info">
	<strong>Password: </strong><input type="password" class="form-control" name="password">
    <div class="error" id="password_error"><?php echo form_error('password')?></div>
    <?php
    	if(isset($incorrect_password)){
			echo '<div class="error" id="password_error">' . $incorrect_password . '</div>';
		}
		if(isset($not_activated)){
			echo '<div class="error" id="password_error">' . $not_activated . '</div>';
		}
		if(isset($email_not_found)){
			echo '<div class="error" id="password_error">' . $email_not_found . '</div>';
		}
		if(isset($block_account)){
			echo '<div class="error" id="password_error">' . $block_account . '</div>';
		}
	?>
    </div>

    <div class = "form-group">
    <input type="submit" class="btn btn-info btn-md" value="Login">
	<a href='<?php echo base_url()."signup/";?>' class="btn btn-info btn-md" role="button">Create Account</a>
	</div>
	<a href='<?php echo base_url()."login/";?>reset_password' style="font-weight: bold; color:red;">Reset your password</a>
	<?php echo form_close();?>	
	<!--<?php echo validation_errors('<p class="error">');?>-->

</div>
</body>
</html>