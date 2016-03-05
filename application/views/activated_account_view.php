<body style="background-color: #bad4f6">
<div class = "container text-center" style="border: 2px solid #32687b; width: 70%">
	<br>
		<p>
			<strong>
				You account has been activated. Now you can log in as <?php echo $email_address;?>
			</strong>
		</p>
		<?php echo form_open('login');?>
	    <div class = "form-group">
    		<a href='<?php echo base_url();?>' class="btn btn-info btn-md" role="button">Log in</a>
		</div>
		<?php echo form_close();?>
	<br>
</div>
</body>
</html>