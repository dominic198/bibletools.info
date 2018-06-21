<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kjvmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper( "reference" );
	}
	
	function plain_verse( $ref )
	{
		if( $ref ){
			$query = "SELECT * FROM kjv_verses LEFT JOIN kjv_books ON kjv_verses.book = kjv_books.id WHERE ref = $ref";
			$results = $this->db->query( $query )->row_array();
			
			$return = array();
			$return['text'] = $results['text'];
			$return['title'] = "{$results['book']} {$results['chapter']}:{$results['verse']}";
			$link_book = str_replace( " ", "", $results['book'] );
			$return['link'] = "http://bibletools.info/{$link_book}_{$results['chapter']}:{$results['verse']}";
			$return += $this->nav( $ref, true );
			return $return;
		}
	}
	
	function html_verse( $ref )
	{
		if( $ref ){
			$query = "SELECT * FROM kjv_html WHERE ref = $ref";
			$results = $this->db->query( $query )->row_array();
			return $results['words'];
		}
	}
	
	function lexicon( $ref, $word )
	{
		if( is_numeric( $word ) ) {
			$lang = ( $ref < 40001001 ? "hebrew" : "greek" );
			$definition = $this->db->select( "kjv_original.word, kjv_original.id, lexicon_$lang.data, lexicon_$lang.base_word, kjv_original.pronun" )
				->from( "kjv_words" )
				->join( "kjv_original", "kjv_words.orig_id = kjv_original.id" )
				->join( "lexicon_$lang", "kjv_original.strongs = lexicon_$lang.strongs" )
				->where( "kjv_words.id", $word )
				->get()
				->row_array();
				
			$connected_words = $this->db->select( "id" )
				->from( "kjv_words" )
				->where( "orig_id", $definition['id'] )
				->get()
				->result_array();
			
			$definition['data'] = json_decode( $definition['data'], true );
			$definition['pronun'] = json_decode( $definition['pronun'], true );
			$definition['data']['def']['long']['content'] = $this->makeUl( $definition['data']['def']['long'] );
			$definition['data']['def']['long']['title'] = "More Strongs Definitions";
			$definition['connected_words'] = $connected_words;
			
			return $definition;
		}
	}
	
	function lexicon_occurances( $ref, $word_id, $base_word )
	{
		if( is_numeric( $word_id ) ) {
			$sign = ( $ref < 40001001 ? "<" : ">=" );
			
			$word = $this->db->select( "*" )
				->from( "kjv_words" )
				->join( "kjv_original", "kjv_words.orig_id = kjv_original.id" )
				->where( "kjv_words.id", $word_id )
				->get()
				->row_array();
							
			$related_verses = $this->db->select( "kjv_html.ref, kjv_html.words" )
				->from( "kjv_html" )
				->join( "kjv_original", "kjv_html.ref = kjv_original.ref" )
				->where( "kjv_original.strongs", $word['strongs'] )
				->where( "kjv_html.ref $sign 40001001" )
				->get()
				->result_array();
				
			/*$related_words = $this->db->select( "kjv_words.id" )
				->from( "kjv_words" )
				->join( "kjv_original", "kjv_words.orig_id = kjv_original.id" )
				->where( "kjv_original.strongs", $word['strongs'] )
				->where( "kjv_words.ref $sign 40001001" )
				->get()
				->result_array();*/
						
			$html = "<ul class='occurances'>";
			foreach( $related_verses as $verse ) {
				$ref_array = parseReference( $verse['ref'] );
				$words = strip_tags( $verse['words'] );
				$html .= "<li><strong>{$ref_array['book']} {$ref_array['chapter']}:{$ref_array['verse']}</strong><p>$words</p></li>";
			}
			
			return [
				"title" => count( $related_verses ) . " other occurances of the root word: $base_word" ,
				"content" => $html,
			];
		}
	}
	
	function makeUl( $array )
	{
		$html = "<ol>";
		foreach( $array as $item ) {
			if( is_array( $item ) ) {
				$html .= $this->makeUl( $item );
			} else {
				$html .= "<li>$item</li>";
			}
		}
		$html .= "</ol>";
		return $html;
	}
	
	function nav( $ref, $numeric = false )
	{

		if( is_numeric( $ref ) ) {
			$verse = $this->db->query( "SELECT id FROM kjv_verses WHERE ref = $ref" )->row_array();
			$prev_id = $verse['id']-1;
			$next_id = $verse['id']+1;
			
			$prev = $this->db->query("SELECT * FROM kjv_verses LEFT JOIN kjv_books ON kjv_verses.book = kjv_books.id WHERE kjv_verses.id = $prev_id")->row_array();
			$next = $this->db->query("SELECT * FROM kjv_verses LEFT JOIN kjv_books ON kjv_verses.book = kjv_books.id WHERE kjv_verses.id = $next_id")->row_array();
			
			if($prev) {
				$book = bookNumberToAbbreviation( $prev['id'] );
				$nav['prev'] = "{$book}_{$prev['chapter']}.{$prev['verse']}";
			}
			if($next) {
				$book = bookNumberToAbbreviation( $next['id'] );
				$nav['next'] = "{$book}_{$next['chapter']}.{$next['verse']}";
			}
			
			return $nav;
		}
	}
}