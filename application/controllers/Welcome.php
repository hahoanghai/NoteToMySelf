<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->home();
	}

	public function home(){
		//$this->load->model('model_users');

		$data['title'] = 'MVC Cool Title';
		$data['page_header'] = 'Intro to MVC design';
		//$data['firstnames'] = $this->model_users->getFirstNames();

		//$data['users'] = $this->model_users->getUsers();

		$this->load->view('welcome_message', $data);
	}
}
