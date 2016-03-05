<?php
	
class Membership_model extends CI_Model{

	private $email_code;

	function create_member(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$hashed_password = do_hash($password, 'md5');
		$new_member = array(
			'email' => $this->input->post('email'),
			'password' => $hashed_password
		);

		$insert = $this->db->insert('user',$new_member);
		$this->set_session($email);
		$this->send_validation_email();

		//print_r($this->session->all_userdata()); //debug session
		return $insert;
	}

	function check_if_email_exists($email){
		$this->db->where('email',$email);
		$result = $this->db->get('user');

		if($result->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}

	}

	function validate_email($email_address, $email_code){

		$sql = "SELECT email, reg_time FROM user WHERE email = '{$email_address}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();

		if($result->num_rows() === 1){
			if(md5((string)$row->reg_time) === $email_code)
				$result = $this->activate_account($email_address);
			if($result === true){
				return true;
			}
			else{
				echo "Error at 1st else validate_email in membership_model";
				return false;
			}
		}		
		else{
			echo "Error at 2nd else validate_email in membership_model";
			return false;
		}
	}

	function set_session($email){

		$sql = "SELECT user_id, reg_time FROM user WHERE email = '" . $email . "' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();

		$sess_data = array(
				'user_id' => $row->user_id,
				'email' => $email,
				'logged_in' => 0
			);
		$this->email_code = md5((string)$row->reg_time);
		$this->session->set_userdata($sess_data);
	}

	function send_validation_email(){
		$email = $this->session->userdata('email');
		$email_code = $this->email_code;

		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
      		->setUsername('xxx@xxx.xxx')
      		->setPassword('xxx');

        //Create the message
        $message = Swift_Message::newInstance();

        //Give the message a subject
        $message->setSubject('Validation Email from MyNote.tk')
                ->setFrom('xxx@xxx.xxx')
                ->setTo($email)
                ->setBody('validation email')
                ->addPart('<p>Thank you for registering on mynote.tk!</p><br><p>please <strong><a href ="'.base_url().
                	'signup/validate_email/'.$email.'/'.$email_code.'">Click here</a></strong> to activate your account.
                	After you have activated your account, you will be able to log into mynote.tk</p>',
                	'text/html'
                	);

        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        $result = @$mailer->send($message);
	}

	function activate_account($email_address){

		$sql = "UPDATE user SET activated = 1 WHERE email = '{$email_address}' LIMIT 1";
		$result = $this->db->query($sql);
		$affected_rows = $this->db->affected_rows();
		echo $affected_rows;
		if($affected_rows <= 1){
			return true;
		}
		else{
			echo "Error at activate_account in membership_model";
			return false;
		}
	}

}