<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settingsmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}


	function save($firstname, $lastname, $timezone)
	{
		
		if (ctype_alpha($lastname) AND ctype_alpha($firstname)) {
			$last_modified = date('Y-m-d H:i:s');
			$sql = 'UPDATE user_profiles SET timezone = "'.$timezone.'", modified = "'.$last_modified.'", firstname = "'.$firstname.'", lastname = "'.$lastname.'" WHERE user_id = '.$this->tank_auth->get_user_id();
			$query = $this->db->query($sql);
		} else {
			die("You entered characters which are not allowed");
		}
	}
	function get()
	{
		
		$sql = 'SELECT * FROM user_profiles WHERE user_id = '.$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
		return $query->row_array();
	}
	function getLastPhone()
	{
		$sql = 'SELECT phone FROM reminders WHERE user_id = '.$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
	    if ($query->num_rows() > 0){
	    
	    	$result = $query->row_array();
			return $result['phone'];
		}
	}
	function insert_reminder($data)
	{
		$this->db->insert('reminders', $data);
		$returnData = array(
			'id' => $this->db->insert_id(),
			'hour' => $data['hour'],
			'min' => $data['min'],
			'days' => $data['days']
		);
		return $returnData;
	}
	function getReminders()
	{
		$sql = 'SELECT * FROM reminders WHERE user_id = '.$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
		return $query->result();
	}
	function deleteReminder($id)
	{
		if(is_numeric($id)){
			$sql = 'DELETE FROM reminders WHERE id = '.$id.' AND user_id = '.$this->tank_auth->get_user_id();
		    $query = $this->db->query($sql);
		}
	}

}