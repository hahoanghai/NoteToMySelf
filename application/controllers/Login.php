<?php 
class Login extends CI_Controller{

	function __construct() {
		parent::__construct();
		//$this->load->view('header_view');
		$this->load->model('membership_login');
	}

	function index(){
		
		$this->load->library('form_validation');
		//$this->load->view('login_view');
		//$this->login_user();	
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|numeric');
		
		if($this->form_validation->run() === FALSE){
			$this->load->view('header_view');
			$this->load->view('login_view');
		//	echo "log in error 1\n";
		}
		else{
			//login input looks ok, query database and check
			$result = $this->membership_login->login_user();

			switch($result){
				case 'logged_in':
					//authentication complete, send to logged in homepage
					$email = $this->input->post('email');
					/*grab the existing note from userNotes by getting userid by email and send that info*/
					$this->load->model('upload_model');
					$currentid = $this->upload_model->get_user_id($email);
					$usernote = $this->upload_model->get_note($currentid);
					$tbd = $this->upload_model->get_tbd($currentid);
					$linkarray = $this->upload_model->get_link($currentid);
					$imagearray = $this->upload_model->get_image($currentid);

					$this->load->view('header_view');
					$this->load->view('home_view', array('email'=>$email, 'usernote'=>$usernote, 'tbd'=>$tbd,
							'linkarray' => $linkarray, 'imagearray' => $imagearray));
					
					//echo"sucessfull";
					break;
				case 'incorrect_password':
					$this->load->view('header_view');
					$this->load->view('login_view',array('incorrect_password'=>'incorrect password'
													));
					//echo "log in error 2\n";
					break;
				case 'not_activated':
					$this->load->view('header_view');
					$this->load->view('login_view',array('not_activated'=>'not activated yet, please check your email'
													));
					//echo "log in error 3\n";
					break;
				case 'block_account':
					$email = $this->input->post('email');
					$this->send_reset_password_email($email);
					$this->load->view('header_view');
					$this->load->view('login_view',array('block_account'=>'wrong password for 3 times, your account has been blocked,
																			please check your email for instruction to reset your password'
													));
					//echo "log in error 3\n";
					break;
				case 'email_not_found':
					$this->load->view('header_view');
					$this->load->view('login_view',array('email_not_found'=>'Email not found, please register'
													));
					//echo "log in error 4\n";
			}
		}
	}

	function login_user(){

		//detect login error without query database
		$this->form_validation->set_rules('email', 'Email', 'trim|min_length[6]|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|numeric');
		
		if($this->form_validation->run() === FALSE){
			$this->load->view('header_view');
			$this->load->view('login_view');
			//echo "log in error 1\n";
		}
		else{
			//login input looks ok, query database and check
			$result = $this->membership_login->login_user();

			switch($result){
				case 'logged_in':
					//authentication complete, send to logged in homepage
					redirect('/','location');
					break;
				case 'incorrect_password':
					$this->load->view('header_view');
					$this->load->view('login_view');
					//echo "log in error 2\n";
					break;
				case 'not_activated':
					$this->load->view('header_view');
					$this->load->view('login_view');
					//echo "log in error 3\n";
					break;
			}
		}
	}

	function reset_password(){
		if(isset($_POST['email']) && !empty($_POST['email'])){
			$this->load->library('form_validation');
			//first check valid email
			$this->form_validation->set_rules('email', 'Email', 'trim|min_length[6]|required|valid_email');
			if($this->form_validation->run() == FALSE){
				$this->load->view('header_view');
				$this->load->view('reset_password_view', array('error'=>'Please supply a valid email address'));
				//echo "reset password error 1\n";
			}else{
				$email = trim($this->input->post('email'));
				$result = $this->membership_login->email_exists($email);

				if($result){
					$this->send_reset_password_email($email,$result);
					$this->load->view('header_view');
					$this->load->view('reset_password_sent_view', array('email'=>$email));
				}else{
					$this->load->view('header_view');
					$this->load->view('reset_password_view', array('error'=>'Email address not registered with mynote'));
				}
			}
		}else{
			$this->load->view('header_view');
			$this->load->view('reset_password_view');
			
		}
	}

	function reset_password_form($email, $email_code){
		if(isset($email,$email_code)){
			$email=trim($email);
			$email_hash = sha1($email.$email_code);
			
			$verified = $this->membership_login->verify_reset_password_code($email,$email_code);

			if($verified){
				$this->load->view('header_view');
				$this->load->view('update_password_view', array('email_hash'=>$email_hash, 'email_code'=>$email_code,'email'=>$email));
			}else{
				$this->load->view('header_view');
				$this->load->view('reset_password_view', array('error'=>'There is a problem with your link. Please click it again to request for another','email'=>$email));
			}
		}
	}

	function update_password(){
		if(!isset($_POST['email'],$_POST['email_hash']) || $_POST['email_hash'] !== sha1($_POST['email'].$_POST['email_code'])){
			die('Error updating your password');
		}
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email_hash','Email Hash','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|numeric');
		$this->form_validation->set_rules('password_conf', 'Confirmed Password', 'required|matches[password]');
		
		if($this->form_validation->run() == false){
			$this->load->view('header_view');
			$this->load->view('update_password_view');
		}
		else{
			$result = $this->membership_login->update_password();
			if($result){
				$this->load->view('header_view');
				$this->load->view('update_password_success_view');
			}else{
				$this->load->view('header_view');
				$this->load->view('update_password_view',array('error'=>'Problem updating your password, please contact'));
			}
		}
	}

	function send_reset_password_email($email){

		$email_code = md5($this->config->item('salt').$email);
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
      		->setUsername('xxx@xxx.xxx')
      		->setPassword('xxx');

        //Create the message
        $message = Swift_Message::newInstance();

        //Give the message a subject
        $message->setSubject('Reset Password Email from MyNote.tk')
                ->setFrom('xxx@xxx.xxx')
                ->setTo($email)
                ->setBody('Reset password email')
                ->addPart('<p>We want to help you reset your password</p><br><p>please <strong><a href ="'.base_url().
                	'login/reset_password_form/'.$email.'/'.$email_code.'">Click here</a></strong> to reset your password</p>',
                	'text/html'
                	);

        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        $result = @$mailer->send($message);
	}
}