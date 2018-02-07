<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Questionmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get( $slug )
	{
		if( preg_match( "/^[-a-z0-9]*$/", $slug ) ) {
			return $this->db->select( "*" )
				->from( "questions" )
				->where( "slug", $slug )
				->get()
				->row_array();
		}
	}
	
	function getResources( $question_id )
	{
		if( is_numeric( $question_id ) ) {
			return $this->db->select( "*" )
				->from( "question_resources" )
				->where( "question_id", $question_id )
				->join( "resources", "resources.id = question_resources.id" )
				->join( "resource_info", "resource_info.id = resources.info_id" )
				->get()
				->result_array();
		}
	}
}