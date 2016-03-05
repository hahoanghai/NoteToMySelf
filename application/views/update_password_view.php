<body style="background-image: url('<?php echo base_url();?>asset/sky.jpg')">
	<h2 class = "text-info text-center">Update your password</h2>
	<div id="update_password_form">
		<form action="<?php echo base_url()."login/"?>update_password" method="POST">
			<div>
				<label for="email">Email: </label>
				<?php if(isset($email_hash, $email_code)) {?>
				<input type="hidden" value="<?php echo $email_hash ?>" name="email_hash"/>
				<input type="hidden" value="<?php echo $email_code ?>" name="email_code"/>
				<?php } ?>
				<input type="email" value="<?php echo (isset($email)) ? $email : ''; ?>" name="email"/>
			</div>
			<div>
				<label for="password">New Password: </label>
				<input type="password" value="" name="password"/>
			</div>
			<div>
				<label for="password_conf">New Password Again: </label>
				<input type="password" value="" name="password_conf"/>
			</div>
			<div>
				<input type="submit" value="Update My Password" name="submit"/>
			</div>
		</form>
		<?php 
			echo validation_errors('<p class="error">');
		?>
	</div>
</body>
