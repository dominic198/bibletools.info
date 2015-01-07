<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Biblereadermodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		$this->load->database();
		
	}

	function add_reference($book, $chapter, $verse, $reference)
	{
		$data = array(
		   'book' => $book,
		   'chapter' => $chapter,
		   'verse' => $verse,
		   'reference' => $this->db->escape_like_str($reference)
		);
		$this->db->insert('egw_scripture_reference', $data);
		return $this->db->insert_id();
		
	}
}