<body style="background-image: url('<?php echo base_url();?>asset/sky.jpg')">
	<div class = "container" style="border: 2px solid #32687b; width: 70%">
	<?php echo form_open('home/logout');?>
		<h1>Welcome to mynote.tk, <?php echo $email ?> !</h1>
		<input type="submit" class="btn btn-info btn-sm text-left" value="Log Out" name="logout" />
		<?php
			echo form_close("<br/>");

			echo form_open('home/delete_img');
			echo form_hidden('loggedinuser', $email);
			$count = 1;
			if(count($imagearray) >= 1){
				foreach($imagearray as $blob) {
					//create image from blob
					$image = imagecreatefromstring($blob);
					$hiddenname = "imgcontents" . $count;
					$currentid = $this->upload_model->get_user_id($email);
					$imgid = $this->upload_model->get_image_by_file($currentid, $blob);
					echo form_hidden($hiddenname, $imgid);

					ob_start();
					imagejpeg($image, null, 80);
					$data = ob_get_contents();
					ob_end_clean();
					echo '<img src="data:image/jpg;base64,' . base64_encode($data) . '" width="100" height="100" />&nbsp;&nbsp;';
					$name = "delete" . $count;
					echo '<input type="submit" class="btn btn-danger btn-xs" value="Delete" name="' . $name . '"/>';
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					$count++;
				}
			}
			echo form_close("</br>");

			echo form_open('home/delete_link');
			echo form_hidden('loggedinuser', $email);
			$count = 1;
			if(count($linkarray) >= 1){
				foreach($linkarray as $linkref) {
					$hiddenname = "link" . $count;
					echo form_hidden($hiddenname, $linkref);
					echo "<a href=" . $linkref . " target='_blank'>". $linkref ."</a>&nbsp;&nbsp;";
					$name = "delete" . $count;
					echo '<input type="submit" class="btn btn-danger btn-xs" value="Delete" name="' . $name . '"/>';
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					$count++;
				}
			}
			echo form_close("</br>");

			echo form_open_multipart('home/do_upload');

			echo form_hidden('loggedinuser', $email);
			$data = array(
					'type'        => 'textarea',
					'name'        => 'note',
					'id'       	  => 'note',
					'size'        => '200',
					'style'       => 'width:70%',
					'class'		  => 'form-control',
					'rows'		  => '5',
					'value'		  => $usernote
			);
			echo form_label('Notes', 'notes') . '<br/>' . form_textarea($data);
			echo "<br/>";
			echo form_label('Links', 'links');
			echo "<br/>";
			$data = array(
					'type'        => 'text',
					'name'        => 'link',
					'style'       => 'width:70%',
					'class'		  => 'form-control',
					'placeholder' => 'Enter Links...'
			);
			echo form_input($data);
			echo "<br/>";
//			foreach ($linkarray as $linkref) {
//				echo "<a href=" . $linkref . " target='_blank'>". $linkref ."</a><br/>";
//			}

		$data = array(
				'type'        => 'text',
				'size'        => '200',
				'rows'		  => '5',
				'style'       => 'width:70%',
				'class'		  => 'form-control',
				'name'        => 'tbd',
				'value' 	  => $tbd
		);
			echo form_label('TBD', 'tbd') . '<br/>' . form_textarea($data);

			echo "<br/>";

		echo '<input type="file" class="btn btn-success btn-sm" name="userfile" size="20" /><br/><br/>';
		//echo '<input type="submit" class="btn btn-info btn-md" value="upload" name="upload" />';
		echo "<div class='text-center'>";
		echo form_submit('save', 'Save', 'class="btn btn-success btn-sm"');
		echo "<br/><br/>";
		echo "</div>";
		?>

	</div>
</body>