<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	//private $logged_in;
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		if($this->session->userdata('logged_in')){
			$email = $this->session->userdata('email');
			$this->load->model('upload_model');
			$currentid = $this->upload_model->get_user_id($email);
			$usernote = $this->upload_model->get_note($currentid);
			$tbd = $this->upload_model->get_tbd($currentid);
			$linkarray = $this->upload_model->get_link($currentid);
			$imagearray = $this->upload_model->get_image($currentid);

			$this->load->view('header_view');
			$this->load->view('home_view', array('email'=>$email, 'usernote'=>$usernote, 'tbd'=>$tbd,
							'linkarray' => $linkarray, 'imagearray' => $imagearray));
		}else{
			$this->load->view('header_view');
			$this->load->view('login_view');
		}
	}

	public function logout(){
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('user_id');
		$this->session->sess_destroy();
		redirect('home','refresh'); 
	}

	function do_upload(){
		//detect form errors
		$this->form_validation->set_rules('note', 'note', 'xss_clean');
		$this->form_validation->set_rules('link', 'link', 'xss_clean|prep_url');
		$this->form_validation->set_rules('note', 'tbd', 'xss_clean');

		if($this->form_validation->run() === FALSE){
			$this->load->view('login_view');
			echo "Don't enter bad data!!";
		}
		else {
			$this->load->model('upload_model');
			$this->load->helper('form');

			//get user id where email = $currentuser
			$currentuser = $this->input->post('loggedinuser');
			$currentid = $this->upload_model->get_user_id($currentuser);
			$note = '';
			$tbd = '';

			//handles note
			if ($this->input->post('note')) {
				$note = $this->input->post('note');
				$data = array(
						'userid' => $currentid,
						'note' => $note
				);

				//if get note where userid = currentid is false, insert
				$usernote = $this->upload_model->get_note($currentid);
				if ($usernote == null) {
					//add note to database
					$this->upload_model->add_note($data);
				} else {
					//update
					$data = array(
							'note' => $note
					);
					$this->upload_model->update_note($data, $currentid);
				}
			}

			//handles tbd
			if ($this->input->post('tbd')) {
				$tbd = $this->input->post('tbd');
				$data = array(
						'userid' => $currentid,
						'tbd' => $tbd
				);

				//if get tbd where userid = currentid is false, insert
				$usertbd = $this->upload_model->get_tbd($currentid);
				if ($usertbd == null) {
					//add note to database
					$this->upload_model->add_tbd($data);
				} else {
					//update
					$data = array(
							'tbd' => $tbd
					);
					$this->upload_model->update_tbd($data, $currentid);
				}
			}

			/* LINK */

			if ($this->input->post('link')) {
				$link = $this->input->post('link');
				$linkarray = $this->upload_model->get_link($currentid);
				if (count($linkarray) < 4) {
					$data = array(
							'userid' => $currentid,
							'link' => $link
					);
					$this->upload_model->add_link($data);
				} else {
					echo "You can't add more links, limit is 4.";
				}
			}

			
			if (!empty($_FILES['userfile']['name'])) {
				$this->do_pic_upload($currentid);
			}

			//echo img tags with the source
			//query db, return array of image names, loop and display
			$imagearray = $this->upload_model->get_image($currentid);
			$linkarray = $this->upload_model->get_link($currentid);
			$this->load->view('header_view');
			$this->load->view('home_view', array('email' => $currentuser, 'usernote' => $note, 'tbd' => $tbd, 'linkarray' => $linkarray
			, 'imagearray' => $imagearray));
		}
	}
	
	function delete_link(){
		$this->load->model('upload_model');
		$currentuser = $this->input->post('loggedinuser');
		$currentid = $this->upload_model->get_user_id($currentuser);

		if($this->input->post('delete1')){
			$link = $this->input->post('link1');
			
			$linkid = $this->upload_model->get_link_byid($currentid, $link);
			
			$this->upload_model->delete_link($currentid, $linkid);
		}if($this->input->post('delete2')){
			$link = $this->input->post('link2');
			$linkid = $this->upload_model->get_link_byid($currentid, $link);
			$this->upload_model->delete_link($currentid, $linkid);
		}if($this->input->post('delete3')){
			$link = $this->input->post('link3');
			$linkid = $this->upload_model->get_link_byid($currentid, $link);
			$this->upload_model->delete_link($currentid, $linkid);
		}if($this->input->post('delete4')){
			$link = $this->input->post('link4');
			$linkid = $this->upload_model->get_link_byid($currentid, $link);
			$this->upload_model->delete_link($currentid, $linkid);
		}
		$email = $currentuser;
		$usernote = $this->upload_model->get_note($currentid);
		$tbd = $this->upload_model->get_tbd($currentid);
		$linkarray = $this->upload_model->get_link($currentid);
		$imagearray = $this->upload_model->get_image($currentid);

		$this->load->view('header_view');
		$this->load->view('home_view', array('email'=>$email, 'usernote'=>$usernote, 'tbd'=>$tbd,
				'linkarray' => $linkarray, 'imagearray' => $imagearray));
	}


	function delete_img(){
		$this->load->model('upload_model');
		$currentuser = $this->input->post('loggedinuser');
		$currentid = $this->upload_model->get_user_id($currentuser);


		if($this->input->post('delete1')){
			$imgid = $this->input->post('imgcontents1');
			$this->upload_model->delete_image($currentid, $imgid);
		}if($this->input->post('delete2')){
			$imgid = $this->input->post('imgcontents2');
			$this->upload_model->delete_image($currentid, $imgid);
		}if($this->input->post('delete3')){
			$imgid = $this->input->post('imgcontents3');
			$this->upload_model->delete_image($currentid, $imgid);
		}if($this->input->post('delete4')){
			$imgid = $this->input->post('imgcontents4');
			$this->upload_model->delete_image($currentid, $imgid);
		}
		$email = $currentuser;
		$usernote = $this->upload_model->get_note($currentid);
		$tbd = $this->upload_model->get_tbd($currentid);
		$linkarray = $this->upload_model->get_link($currentid);
		$imagearray = $this->upload_model->get_image($currentid);

		$this->load->view('header_view');
		$this->load->view('home_view', array('email'=>$email, 'usernote'=>$usernote, 'tbd'=>$tbd,
				'linkarray' => $linkarray, 'imagearray' => $imagearray));
	}

	function do_pic_upload($currentid){
		$this->load->model('upload_model');
		$config['upload_path'] = 'uploads/temp/';
		$config['allowed_types'] = 'gif|jpg';
		$config['max_size'] = '1024';
		$config['max_width'] = '1024';
		$config['max_height'] = '768';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload()) {
			echo $this->upload->display_errors();
		} else {
			$imagearray = $this->upload_model->get_image($currentid);
			if(count($imagearray) <=3 ) {

				$data = $this->upload->data();
				$filename = "uploads/temp/" . $data['raw_name'] . $data['file_ext'];
				$handle = fopen($filename, "r");
				$contents = fread($handle, filesize($filename));
				fclose($handle);
				$file = array(
						'userid' => $currentid,
						'img' => $contents
				);
				$this->upload_model->add_image($file);
			}else{
				echo "You've reached the limit of 4 images.";
			}
		}

		//delete temp folder after every upload
		$files = glob('uploads/temp/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
	}
}