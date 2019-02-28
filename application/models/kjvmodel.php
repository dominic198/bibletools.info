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
	
	function lexicon( $word_id )
	{
		if( is_numeric( $word_id ) ) {
			$lang = ( $word_id < 621719 ? "hebrew" : "greek" );
			$definition = $this->db->select( "kjv_original.word, kjv_original.id, lexicon_$lang.data, lexicon_$lang.base_word, kjv_original.pronun" )
				->from( "kjv_words" )
				->join( "kjv_original", "kjv_words.orig_id = kjv_original.id" )
				->join( "lexicon_$lang", "kjv_original.strongs = lexicon_$lang.strongs" )
				->where( "kjv_words.id", $word_id )
				->get()
				->row_array();
            
            if( ! $definition ) {
                show_404();
            }
				
			$connected_words = $this->db->select( "id" )
				->from( "kjv_words" )
				->where( "orig_id", $definition['id'] )
				->get()
				->result_array();
			
			$word = $this->db->select( "*" )
				->from( "kjv_words" )
				->join( "kjv_original", "kjv_words.orig_id = kjv_original.id" )
				->where( "kjv_words.id", $word_id )
				->get()
				->row_array();
			
			$sign = ( $word_id < 621719 ? "<" : ">=" );
			$verse_count = $this->db->select( "*" )
				->from( "kjv_original" )
				->where( "strongs", $word['strongs'] )
				->where( "ref $sign 40001001" )
				->count_all_results();
			$definition['data'] = json_decode( $definition['data'], true );
			$pronunciation = json_decode( $definition['pronun'], true );
			
			$occurences = $this->lexicon_occurances( $word_id );
			if( $verse_count > 100 ) {
				$occurences .= "<a class='load-more-occurences'>Load more</a>";
			}
			return [
				"pronunciation" => $pronunciation["dic"],
				"original_word" => $definition["word"],
				"definition" => $definition['data']['def']["short"],
				"connected_words" => $connected_words,
				"resources" => [
					[
						"title" => "More Strongs definitions",
						"content" => $this->makeUl( $definition['data']['def']["long"] ),
					],
					[
						"title" => $verse_count . " other occurrences of the root word: " . $definition['base_word'],
						"content" => $occurences,
					]
					
				],
				
			];
			
			return $definition;
		}
	}
	
	function lexicon_occurances( $word_id, $page = 1 )
	{
		if( is_numeric( $word_id ) ) {
			$sign = ( $word_id < 621719 ? "<" : ">=" );
			$offset = ( $page - 1 ) * 100;
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
				->limit( 100 )
				->offset( $offset )
				->get()
				->result_array();
						
			$html = "<ul class='occurances'>";
			foreach( $related_verses as $verse ) {
				$ref_array = parseReference( $verse['ref'] );
				$words = strip_tags( $verse['words'] );
				$html .= "<li><strong>{$ref_array['book']} {$ref_array['chapter']}:{$ref_array['verse']}</strong><p>$words</p></li>";
			}
			$html .= "</ul>";
			return count( $related_verses ) > 0 ? $html : false;
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
			
			if( $numeric ) {
				$nav['prev'] = $prev ? "{$prev['id']} {$prev['chapter']}:{$prev['verse']}" : false;
				$nav['next'] = $next ? "{$next['id']} {$next['chapter']}:{$next['verse']}" : false;
			} else {
				$book = bookNumberToAbbreviation( $prev['id'] );
				$nav['prev'] = $prev ? "{$book}_{$prev['chapter']}.{$prev['verse']}" : false;
				$book = bookNumberToAbbreviation( $next['id'] );
				$nav['next'] = $next ? "{$book}_{$next['chapter']}.{$next['verse']}" : false;
			}
			
			return $nav;
		}
	}
	
	function getCrossReferences( $ref )
	{
		if( is_numeric( $ref ) ) {
			$query = "SELECT content FROM tsk WHERE start = $ref LIMIT 1";
			$result = $this->db->query( $query )->row_array();
			return $result ? [
				"source" => "Cross References",
				"class" => "tsk-panel",
				"content" => $result["content"] ?? "",
			] : false;
		}
	}
	
	function bibletext( $ref )
	{
		if( $ref ){
			$query = "SELECT * FROM kjv_text WHERE ref = $ref";
			$result = $this->db->query( $query )->row_array();
			return [
				"text" => $result["words"],
				"title" => parseReferenceToText( $ref ) . " (KJV)",
				
			];
		}
	}
}