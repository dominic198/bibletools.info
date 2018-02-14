<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Questionmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper( "reference" );
	}

	function get( $slug )
	{
		if( preg_match( "/^[-a-z0-9]*$/", $slug ) ) {
			return $this->db->select( "*" )
				->from( "questions" )
				->join( "question_categories", "question_categories.question_id = questions.id", "left" )
				->where( "slug", $slug )
				->get()
				->row_array();
		}
	}
	
	function getFormatted()
	{
		$questions = $this->db->select( "*, categories.name as 'category_name'" )
			->from( "questions" )
			->join( "question_categories", "question_categories.question_id = questions.id" )
			->join( "categories", "question_categories.category_id = categories.id" )
			->get()
			->result_array();
		
		$formatted_questions = [];
		foreach( $questions as $question ) {
			$formatted_questions[$question["category_name"]][] = $question;
		}
		return $formatted_questions;
	}
	
	function getPopular()
	{
		return $this->db->select( "*" )
			->from( "questions" )
			->order_by( "upvotes", "DESC" )
			->limit( 5 )
			->get()
			->result_array();
	}
	
	function getRecent()
	{
		return $this->db->select( "*" )
			->from( "questions" )
			->order_by( "created_at", "DESC" )
			->limit( 5 )
			->get()
			->result_array();
	}
	
	function getResources( $question_id )
	{
		if( is_numeric( $question_id ) ) {
			return $this->db->select( "resources.content, resource_info.name" )
				->from( "question_resources" )
				->where( "question_id", $question_id )
				->join( "resources", "resources.id = question_resources.resource_id", "left" )
				->join( "resource_info", "resource_info.id = resources.info_id" )
				->get()
				->result_array();
		}
	}
	
	function getVerses( $question_id )
	{
		if( is_numeric( $question_id ) ) {
			$verses = $this->db->select( "start, end" )
				->from( "question_verses" )
				->where( "question_id", $question_id )
				->get()
				->result_array();
			
			return array_map( function( $verse ) {
				$verse["reference"] = parseReferenceToText( $verse["start"], $verse["end"] );
				return $verse;
			}, $verses );
		}
	}
	
	function getRelated( $question_id, $category_id )
	{
		if( is_numeric( $question_id ) ) {
			return $this->db->select( "*" )
				->from( "questions" )
				->join( "question_categories", "question_categories.question_id = questions.id" )
				->where( "question_categories.category_id =", $category_id )
				->where( "id !=", $question_id )
				->get()
				->result_array();
		}
	}
	
	function getCategories()
	{
		return $this->db->select( "*" )
			->from( "categories" )
			->get()
			->result_array();
	}
}