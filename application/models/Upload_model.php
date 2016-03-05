<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	class Upload_model extends CI_Model {
		public function __construct(){
			parent::__construct();
		}

		function get_user_id($data){
			$query = $this->db->get_where('user', array('email' => $data));
			foreach ($query->result() as $row)
			{
				return $row->user_id;
			}
		}

		function add_note($data){
			$this->db->insert('userNotes',$data);
		}

		//each user should only have one note, this should be displayed in the form input
		function get_note($id){
			$query = $this->db->get_where('userNotes', array('userid' => $id));
			foreach ($query->result() as $row)
			{
				return $row->note;
			}
		}

		function update_note($data, $id){
			$this->db->update('userNotes', $data, "userid = " . $id);
		}

		function add_tbd($data){
			$this->db->insert('userTbd',$data);
		}

		//each user should only have one tbd, this should be displayed in the form input
		function get_tbd($id){
			$query = $this->db->get_where('userTbd', array('userid' => $id));
			foreach ($query->result() as $row)
			{
				return $row->tbd;
			}
		}

		function update_tbd($data, $id){
			$this->db->update('userTbd', $data, "userid = " . $id);
		}

		function add_link($data){
			$this->db->insert('userLink',$data);
		}

		//each user should have 4 links, return an array of links and display in link input in order
		function get_link($id){
			$query = $this->db->get_where('userLink', array('userid' => $id));
			//$linkarray = array(" ", " ", " ", " ");
			//$count = 0;
			$linkarray = array();
			foreach ($query->result() as $row)
			{
				array_push($linkarray, $row->link);
				//++$count;
			}
			return $linkarray;
		}

		function delete_link($userid, $linkid){
			$this->db->delete('userLink', array('userid' => $userid, 'id' => $linkid));
		}

		function get_link_byid($userid, $link){
			$query = $this->db->get_where('userLink', array('userid' => $userid, 'link' => $link));
			foreach ($query->result() as $row)
			{
				return $row->id;
			}
		}

		function update_link($data, $id, $link){
			$this->db->where('userid', $id);
			$this->db->update('userLink', $data, "link = " . '\'' . $link . '\'');
		}
		
		function add_image($data){
			$this->db->insert('uploads',$data);
		}

		function get_image_by_file($userid, $img){
			$query = $this->db->get_where('uploads', array('userid' => $userid, 'img' => $img));
			foreach ($query->result() as $row)
			{
				return $row->id;
			}
		}

		//need to pass in image id
		function delete_image($userid, $imgid){
			$this->db->delete('uploads', array('userid' => $userid, 'id' => $imgid));
		}

		public function get_image($userid){
			$query = $this->db->get_where('uploads', array('userid' => $userid));
			$imagearray = array();
			foreach ($query->result() as $row)
			{
				//array_push($imagearray, $row->img_name . $row->ext);
				array_push($imagearray, $row->img);

			}
			return $imagearray;
		}
	}