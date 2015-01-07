<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Prayerjournalmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}


	function add_item($user_id, $content, $private)
	{
		$data = array(
		   'user_id' => $user_id ,
		   'content' => $content ,
		   'private' => $private ,
		   'date' => date('Y-m-d H:i:s')
		);
		$this->db->insert('prayer_requests', $data);
		$returnData = array(
			'id' => $this->db->insert_id(),
			'content' => $content,
			'date' => date("c", time())
		);
		return $returnData;
		
	}
	function delete_item($item_id)
	{
		if(is_numeric($item_id)){
		
			$sql = 'DELETE FROM prayer_requests WHERE id = '.$item_id.' AND user_id = '.$this->tank_auth->get_user_id();
		    $query = $this->db->query($sql);
		}
	}
	function get_items($offset = 0)
	{
		if(is_numeric($offset)){
			$sql = 'SELECT * FROM prayer_requests WHERE user_id = '.$this->tank_auth->get_user_id().' ORDER BY id DESC LIMIT 5 OFFSET '.$offset;
		    $query = $this->db->query($sql);
	 
			//if($query->num_rows() > 0) return $query->result();
			//else return FALSE;
			$rows = array();
			foreach($query->result() as $row)
			{
			    $row->date = date("c", strtotime($row->date));
			    $rows[] = $row;
			}
			return $rows;
		}
	}
	function get_all_items()
	{
		$sql = 'SELECT * FROM prayer_requests WHERE user_id = '.$this->tank_auth->get_user_id().' ORDER BY id DESC';
	    $query = $this->db->query($sql);

		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->date = date("c", strtotime($row->date));
		    $rows[] = $row;
		}
		return $rows;
	}

}