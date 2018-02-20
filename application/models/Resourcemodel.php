<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resourcemodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get( $ref )
	{
		if( is_numeric( $ref ) ) {
			return $this->db->query( "SELECT * FROM resources LEFT JOIN resource_info ON resources.info_id = resource_info.id WHERE end >= $ref AND start <= $ref OR start = $ref" )
				->result_array();
		}
	}

}