<body style="background-image: url('<?php echo base_url();?>asset/sky.jpg')">
<h1 class = "text-info text-center">Register</h1>
<div class = "container text-center" style="border: 2px solid #32687b; width: 70%">
	
		<?php echo form_open('signup');?>
		<br>
		<div class = "form-group form-inline control-group info">
		<strong>Username: </strong><input type="text" class="form-control" name="email" placeholder="email" value="<?php echo set_value('email')?>">
		<div class="error" id="email_error"><?php echo "<strong>".form_error('email')."</strong>"?></div>
		</div>
		
		<div class = "form-group form-inline control-group info">
		<strong>Password: </strong><input type="password" class="form-control" name="password">
        <div class="error" id="password_error"><?php echo "<strong>".form_error('password')."</strong>"?></div>
    	</div>
        
        <div class = "form-group form-inline control-group info">
        <strong>Re-Password: </strong><input type="password" class="form-control" name="re_password">
        <div class="error" id="re_password_error"><?php echo "<strong>".form_error('re_password')."</strong>"?></div>
       	</div>

       	<div class = "form-group form-inline control-group info">
       		<?php
       			echo $image.'<br/>';
				if(form_error('captcha')){
					echo "<strong>".form_error('captcha')."</strong>";
				}
				else{
					echo "<strong>".form_label('Enter text from image above')."</strong>";
				}
				echo '<br/>';
				$data = array(
					'class' =>'form-control',
					'id'	=>'captcha',
					'name'	=>'captcha',
					'value'	=>'',
					'style'	=>'width:32%',
				);
				echo form_input($data);
       		?>
       	</div>

       	<div class = "form-group">
        	<input type="submit" class="btn btn-info btn-md" value="Create Account">
        	<a href='<?php echo base_url()."login"?>' class="btn btn-info btn-md" role="button">Log in</a>
		</div>
		
		<?php echo form_close();?>

</div>

</body>
</html>