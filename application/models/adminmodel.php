<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Adminmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}


	function get_users()
	{
		$sql = 'SELECT * FROM users ORDER BY created DESC';
	    $query = $this->db->query($sql);
		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->created = date("c", strtotime($row->created));
		    $row->last_login = date("c", strtotime($row->last_login));
		    if($row->last_login == "-001-11-30T00:00:00-08:00"){
		    	$row->last_login = "---------------";
		    }
		    $row->name = $this->get_name($row->id);
		    $rows[] = $row;
		}
		return $rows;
	}
	function get_feedback()
	{
		$sql = 'SELECT * FROM feedback ORDER BY date DESC';
	    $query = $this->db->query($sql);
		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->date = date("c", strtotime($row->date));
		    $row->name = $this->get_name($row->user_id);
		    $row->email = $this->get_email($row->user_id);
		    $rows[] = $row;
		}
		return $rows;
	}
	function get_name($user_id)
	{
		$sql = 'SELECT firstname, lastname FROM user_profiles WHERE user_id = '.$user_id;
	    $query = $this->db->query($sql);
	    $results = $query->row_array();
		$name = $results['firstname']." ".$results['lastname'];
		return $name;
	}
	function get_email($user_id)
	{
		$sql = 'SELECT email FROM users WHERE id = '.$user_id;
	    $query = $this->db->query($sql);
	    $results = $query->row_array();
		return md5($results['email']);
	}
	function total_users()
	{
		$sql = 'SELECT Count(id) AS total FROM users';
	    $query = $this->db->query($sql);
	    $total = $query->row_array();
		return $total['total'];
	}

}