<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kjvmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	function chapter($book, $chapter)
	{

		if(is_numeric($book) AND is_numeric($chapter)){
			$sql = 'SELECT * FROM kjv_verses WHERE book = '.$book.' AND chapter = '.$chapter;
		    $query = $this->db->query($sql);
			return $query->result();
		}
	}
	function search($phrase, $offset = 0)
	{

		if(is_numeric($offset)){
			$sql = "SELECT book, chapter, verse, text FROM kjv_verses WHERE text LIKE '%".$this->db->escape_like_str($phrase)."%' LIMIT 15 OFFSET ".$offset;
			$query = $this->db->query($sql);
			return $query->result();
		}
	}
	function verse( $ref )
	{
		if( is_numeric( $ref ) ){
			$sql = "SELECT * FROM kjv_verses WHERE ref = $ref";
		    $query = $this->db->query($sql);
			foreach ($query->result() as $line)
			{
				$encoding = str_replace("¶ ", "", $line->coding);
				$encoding = explode(' ', $encoding);
				$text_array = explode(' ', $line->text);
				$html = array();
				foreach ($encoding as $code){ //Create array of 
					$wordNumberArray = explode("~", $code);
					$wordNumber = $wordNumberArray[0];
					$strongsCode = $this->get_string_between($code, "~", "~");
					
					if(strstr($code, "*")){ 
						$position = $wordNumber;
						array_splice($text_array, $position,0,"°");
						
					}
					
					
					$html[$wordNumber]['strongsNum'] = $strongsCode;
					$html[$wordNumber]['wordNum'] = $wordNumber;
					
				}
				$i = -1;
				foreach ($text_array as $word){ //Create array of 
				$i++;
					if(isset($html[$i])) { $strongs = $html[$i]['strongsNum']; } else { $strongs = NULL; }
					if(isset($html[$i]['ast'])){
					}
					$output[] = array(
					  'word' => $word,
					  'strongs' => $strongs,
					);
	
				}
				return $output;
				
			}
		}
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
	
	function greek_lex($number)
	{
		if(is_numeric($number)){
			$sql = 'SELECT * FROM greek_lexicon WHERE number = '.$number;
		    $query = $this->db->query($sql);
			return $query->result();
		}
	}
	function hebrew_lex($number)
	{
		if(is_numeric($number)){
			$sql = 'SELECT * FROM hebrew_lexicon WHERE number = '.$number;
		    $query = $this->db->query($sql);
			return $query->result();
		}
	}
	function get_string_between($string, $start, $end){
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
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
				$book = $numeric ? $prev['id'] : $prev['book'];
				$nav['prev'] = "$book {$prev['chapter']}:{$prev['verse']}";
			}
			if($next) {
				$book = $numeric ? $next['id'] : $next['book'];
				$nav['next'] = "$book {$next['chapter']}:{$next['verse']}";
			}
			
			return $nav;
		}
	}
}