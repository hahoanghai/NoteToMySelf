<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Membership_login extends CI_Model{

	function login_user(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		$sql = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();

		if($result->num_rows() === 1){
			if($row->activated){
				if($row->wrong_password_counter < 3){
					if($row->password === do_hash($password, 'md5')){
						$this->reset_wrong_password_counter($email);
						//authenticated, now update the user's session
						$session_data = array(
							'user_id'	=> $row->user_id,
							'email'	=> $row->email
						);
						$this->set_session($session_data);
						return 'logged_in';
					}else{
						$this->update_wrong_password_counter($email);
						return 'incorrect_password';
					}
				}else{
					return 'block_account';
				}
			}else{
				return 'not_activated';
			}
		}else{
			return 'email_not_found';
		}
	}

	function email_exists($email){
		$sql = "SELECT email FROM user WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();

		return ($result->num_rows() === 1 && $row->email) ? $row->email : false;
	}

	function verify_reset_password_code($email, $code){
		$sql = "SELECT email FROM user WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();
		if($result->num_rows() === 1){
			return($code == md5($this->config->item('salt').$row->email)) ? true:false;			
		}else{
			return false;
		}
	}

	function update_password(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$hashed_password = do_hash($password, 'md5');
		$sql = "UPDATE user SET password = '{$hashed_password}' WHERE email = '{$email}' LIMIT 1";
		$this->db->query($sql);
		if($this->db->affected_rows() === 1){
			$this->reset_wrong_password_counter($email);
			return true;
		}
		else{
			return false;
		}
	}

	function set_session($session_data){
		$sess_data = array(
				'user_id'	=> $session_data['user_id'],
				'email' => $session_data['email'],
				'logged_in'=> 1,
			);
		$this->session->set_userdata($sess_data);
	}

	function update_wrong_password_counter($email){
		$sql = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$row = $result->row();
		$wrong_password_counter = $row->wrong_password_counter;
		$wrong_password_counter = $wrong_password_counter + 1;
		$sql = "UPDATE user SET wrong_password_counter = ". $wrong_password_counter ." WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$affected_rows = $this->db->affected_rows();
		if($affected_rows <= 1){
			return true;
		}
		else{
			echo "Error at update_wrong_password_counter in membership_login";
			return false;
		}
	}

	function reset_wrong_password_counter($email){
		$sql = "UPDATE user SET wrong_password_counter = 0 WHERE email = '{$email}' LIMIT 1";
		$result = $this->db->query($sql);
		$affected_rows = $this->db->affected_rows();
		if($affected_rows <= 1){
			return true;
		}
		else{
			echo "Error at update_wrong_password_counter in membership_login";
			return false;
		}
	}
}
