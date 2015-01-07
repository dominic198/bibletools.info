<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Readermodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		
	}
	function add_book($data)
	{
		$this->db->insert('bookshelf', $data);
	}
	function get_books()
	{
		$sql = 'SELECT * FROM bookshelf WHERE user_id = "'.$this->tank_auth->get_user_id().'"';
	    $query = $this->db->query($sql);
	    if ($query->num_rows() > 0){
			$rows = array();
			return $query->result();
		} else {
			return false;
		}

	}
	function delete_book($book_id)
	{
		$sql = 'DELETE FROM bookshelf WHERE id = '.$book_id.' AND user_id = '.$this->tank_auth->get_user_id();
		$query = $this->db->query($sql);
	}

}