<body style="background-image: url('<?php echo base_url();?>asset/sky.jpg')">
	<h1 class = "text-info text-center">Reset Password</h1>
	<div class = "container text-center" style="border: 2px solid #32687b; width: 70%">
		<div id="reset_password_form">
			<form action="reset_password" method="POST">
			<br>
			<div class = "form-group form-inline control-group info">
				<label for="email">Email: </label>
				<input type="email" class="form-control" value="<?php echo set_value('email')?>" name="email"/>
			</div>
			<div class = "form-group">
				<input type="submit" class="btn btn-info btn-md" value="Reset My Password" name="submit"/>
			</div>
			</form>
				<?php
					echo validation_errors('<p class="error">');
					if(isset($error)){
						echo '<p class="error">' . $error . '</p>';
					}
				?>
		</div>
	</div>
</body>
</html>
