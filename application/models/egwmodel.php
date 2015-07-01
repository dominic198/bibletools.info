<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Egwmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper( "text" );
		$this->load->database();
		
	}
	function verse_references( $ref, $limit, $offset = 0 )
	{
		if( is_numeric( $ref ) ) {
			$query = "SELECT * FROM egw_scripture_reference WHERE end >= $ref AND start <= $ref OR start = $ref LIMIT $limit OFFSET $offset";
			$egw = $this->db->query( $query )->result_array();
			
			$total_query = "SELECT count(*) as total FROM egw_scripture_reference WHERE end >= $ref AND start <= $ref OR start = $ref";		    $total = $this->db->query( $total_query )->row()->total;
			
			$array['items'] = $egw;
			$array['total'] = $total;
			return $array;
		}
	}
	
	function verse_quotes( $ref )
	{
		if( is_numeric( $ref ) ){
			$query = "SELECT ref.reference, quote.content, quote.title FROM egw_scripture_reference as ref LEFT JOIN egw_quotes as quote ON ref.reference = quote.reference WHERE ref.end >= $ref AND ref.start <= $ref OR ref.start = $ref";
		
			return $this->db->query( $query )->result_array();
		}
	}
	
	function content( $ref )
	{
		$query = 'SELECT * FROM egw_quotes WHERE reference = "' . urldecode( $ref ) . '"';
		return $this->db->query( $query )->row_array();
	}
}