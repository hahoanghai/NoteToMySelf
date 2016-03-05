<?php 
class Signup extends CI_Controller{

	function __construct() {
		parent::__construct();
		$this->load->helper('captcha');
		$this->load->model('captcha_model');
	}

	function index(){
		$this->load->library('form_validation');
		//$this->load->helper('form');
		//$this->form_validation->set_rules('password','Password','required|trim');
		//$this->form_validation->set_rules('email','Email','required|trim');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_if_email_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|numeric');
		$this->form_validation->set_rules('re_password', 'Re-password', 'required|matches[password]');
		$this->form_validation->set_rules('captcha','captcha','trim|strip_tags|callback_captcha_check|match_captcha[captcha.word]');

		if($this->form_validation->run() === FALSE){
			$this->load->view('header_view');
			$data['image'] = $this-> captcha_model->create_image();
			$this->load->view('signup_view',$data);
		}
		else{
			$this->load->model('membership_model');

			if($query = $this->membership_model->create_member()){
				$data['image'] = $this-> captcha_model->create_image();
				$this->load->view('header_view');
				$this->load->view('signup_view',$data);
				echo '<p class = "text-center">Your account has been created.<br/>
					An email with a link has been sent to your email address<br/>
					Please click the link to active your account</p>';
			}
		}
	}

	function check_if_email_exists($requested_email){
		$this->load->model('membership_model');
		$email_available = $this->membership_model->check_if_email_exists($requested_email);

		if($email_available){
			return true;
		}
		else{
			return false;
		}
	}

	function captcha_check($value){
		if($value == ''){
			$this->form_validation->set_message('captcha_check','Please enter the text from the image above.');
			return false;
		}	
		else{
			return true;
		}
	}

	function validate_email($email_address, $email_code){
		$this->load->model('membership_model');
		$email_code = trim($email_code);
		$validated = $this->membership_model->validate_email($email_address,$email_code);

		if($validated === true){
			$this->load->view('header_view');
			$this->load->view('activated_account_view',array('email_address' => $email_address));
		}
		else{
			echo "Activated email confirmation error";
		}
	}
}