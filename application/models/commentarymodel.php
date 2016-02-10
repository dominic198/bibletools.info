<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Commentarymodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get( $ref, $table, $title, $range = false )
	{
		if( is_numeric( $ref ) ) {
			if( $range ) {
				$query = "SELECT content FROM $table WHERE end >= $ref AND start <= $ref OR start = $ref LIMIT 1";
			} else {
				$query = "SELECT content FROM $table WHERE start = $ref LIMIT 1";
			}
			$result = $this->db->query( $query )->row_array();
		
			if( $result ) {
				$result['title'] = $title;
			}
			return $result;
		}
	}
}