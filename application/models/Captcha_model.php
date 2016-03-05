<?php

class Captcha_model extends CI_Model{
	function create_image(){

		$library = array("47ab7","12gr3","48ge8","dv577","rh697","8fec6","te84q","1sd2q","36a84","51ccs","486s2","365ae");
		$word = '';
		$number = 0;
		while($number < 1){
			$word .= $library[mt_rand(0,11)];
			$number++;
		}

		$captcha = array(
			'word'		=> $word,
			'img_path'	=> './captcha/',
			'img_url'	=> base_url().'captcha/',
			'img_width'	=> 200,
			'img_height'	=> 50,
			'font_path'	=> './fonts/gillubcd.TTF',
			'expiration'	=> 60*3,
			'font_size'	=> 20,
			'time'		=> time()
		);
		$expire = $captcha['time'] - $captcha['expiration'];
		//delete expired captcha
		$this->db->where('time < ', $expire);
		$this->db->delete('captcha');


		$value = array(
			'time'		=> $captcha['time'],
			'ip_address'=> $this->input->ip_address(),
			'word'		=> $captcha['word']
		);
		//insert to the captcha table
		$this->db->insert('captcha',$value);

		$img = create_captcha($captcha);
		return $data['img'] = $img['image'];
	}


}