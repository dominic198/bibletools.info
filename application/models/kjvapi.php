<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kjvapi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	function chapter($book, $chapter)
	{

		if(is_numeric($book) AND is_numeric($chapter)){
			$sql = 'SELECT * FROM av WHERE book = '.$book.' AND chapter = '.$chapter;
		    $query = $this->db->query($sql);
			return $query->result();
		}
	}
	function search($phrase, $offset = 0)
	{

		if(is_numeric($offset)){
			$sql = "SELECT book, chapter, verse, text FROM av WHERE text LIKE '%".$this->db->escape_like_str($phrase)."%' LIMIT 15 OFFSET ".$offset;
			$query = $this->db->query($sql);
			return $query->result();
		}
	}
	function verse($book, $chapter, $verse)
	{

		if(is_numeric($book) AND is_numeric($chapter) AND is_numeric($verse)){
			$sql = 'SELECT * FROM av WHERE book = '.$book.' AND chapter = '.$chapter.' AND verse = '.$verse;
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
				return json_encode($output);
				
			}
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
	function nav($book, $chapter, $verse)
	{

		if(is_numeric($book) AND is_numeric($chapter)  AND is_numeric($verse)){
			$verse = $this->db->query('SELECT row FROM av WHERE book = '.$book.' AND chapter = '.$chapter.' AND verse = '.$verse)->row_array();
			$prev_row = $verse['row']-1;
			$next_row = $verse['row']+1;
			$prev = $this->db->query('SELECT books.book, av.chapter, av.verse FROM av LEFT JOIN books ON av.book = books.number WHERE row = '.$prev_row)->row_array();
			$next = $this->db->query('SELECT books.book, av.chapter, av.verse FROM av LEFT JOIN books ON av.book = books.number WHERE row = '.$next_row)->row_array();
			
			if($prev)
				$nav['prev'] = $prev['book']." ".$prev['chapter'].":".$prev['verse'];
			if($next)
				$nav['next'] = $next['book']." ".$next['chapter'].":".$next['verse'];
			
			return $nav;
		}
	}
	
	function numericNav($book, $chapter, $verse)
	{

		if(is_numeric($book) AND is_numeric($chapter)  AND is_numeric($verse)){
			$verse = $this->db->query('SELECT row FROM av WHERE book = '.$book.' AND chapter = '.$chapter.' AND verse = '.$verse)->row_array();
			$prev_row = $verse['row']-1;
			$next_row = $verse['row']+1;
			$prev = $this->db->query('SELECT book, av.chapter, av.verse FROM av WHERE row = '.$prev_row)->row_array();
			$next = $this->db->query('SELECT book, av.chapter, av.verse FROM av WHERE row = '.$next_row)->row_array();
			
			if($prev)
				$nav['prev'] = $prev['book']." ".$prev['chapter'].":".$prev['verse'];
			if($next)
				$nav['next'] = $next['book']." ".$next['chapter'].":".$next['verse'];
			
			return $nav;
		}
	}

}