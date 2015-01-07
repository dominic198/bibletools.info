<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blogmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		
	}

	function get_blog_entries()
	{
			$sql = 'SELECT * FROM journal WHERE public = "2"';
		    $query = $this->db->query($sql);
			$rows = array();
			foreach($query->result() as $row)
			{
			    $row->created = date("c", strtotime($row->created));
			    $rows[] = $row;
			}
			return $rows;
	}
	function get_entry($id)
	{
		if(is_numeric($id)){
			$sql = 'SELECT * FROM journal WHERE id = "'.$id.'"';
		    $query = $this->db->query($sql);
		    if ($query->num_rows() > 0){
				$result = $query->row_array();
				$result['created'] = date("c", strtotime($result['created']));
				$result['last_modified'] = date("c", strtotime($result['last_modified']));
				return $result;
			} else {
				return "0";
			}
		} else {
		
			return "0";
		}
	}
	function getAvatar($id)
	{
		if(is_numeric($id)){
			$sql = 'SELECT user_id FROM journal WHERE id = "'.$id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			$user_id = $result['user_id'];
			
			$sql = 'SELECT email FROM users WHERE id = "'.$user_id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			return md5($result['email']);
		}
	}
	function getName($id)
	{
		if(is_numeric($id)){
			$sql = 'SELECT user_id FROM journal WHERE id = "'.$id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			$user_id = $result['user_id'];
			
			$sql = 'SELECT firstname, lastname FROM user_profiles WHERE user_id = "'.$user_id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			return $result['firstname']." ".$result['lastname'];
		}
	}
	function getTitle($id)
	{
		if(is_numeric($id)){
			
			$sql = 'SELECT title FROM journal WHERE id = "'.$id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			return $result['title'];
		}
	}

}