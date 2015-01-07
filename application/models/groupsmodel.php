<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groupsmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		
	}

	function get_entries($offset = 0)
	{
		$sql = 'SELECT * FROM journal WHERE user_id = '.$this->tank_auth->get_user_id().' ORDER BY id DESC LIMIT 5 OFFSET '.$offset;
	    $query = $this->db->query($sql);
 
		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->created = date("c", strtotime($row->created));
		    $rows[] = $row;
		}
		return $rows;
	}
	function get_friends()
	{
		$sql = 'SELECT * FROM users ORDER BY id DESC';
	    $query = $this->db->query($sql);
 
		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->email = md5($row->email);
		    $rows[] = $row;
		}
		return $rows;
	}
}