<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Readermodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		$this->load->database();
		$this->load->library('domparser');
		
	}
	function get_egw_from_chapter($book, $chapter, $offset)
	{

		if(isset($book) AND is_numeric($chapter)){
			$sql = 'SELECT * FROM egw_scripture_reference WHERE book = "'.$book.'" AND chapter = '.$chapter.' LIMIT 10 OFFSET '.$offset;
		    $query = $this->db->query($sql);
			return $query->result();
		}
	}
	function get_egw_by_verse($book, $chapter, $verse, $offset)
	{
		if(isset($book) AND is_numeric($chapter)){
			$sql = 'SELECT * FROM egw_scripture_reference WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND endverse >= '.$verse.' AND verse <= '.$verse.' OR book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse.' LIMIT 10 OFFSET '.$offset;
		    $query = $this->db->query($sql);
		    $egw = $query->result_array();
		    
		    $total_sql = 'SELECT count(*) as total FROM egw_scripture_reference WHERE book = "'.$book.'" AND chapter = '.$chapter.' AND endverse >= '.$verse.' AND verse <= '.$verse.' OR book = "'.$book.'" AND chapter = '.$chapter.' AND verse = '.$verse;
		    $total_query = $this->db->query($total_sql);
		    $total = $total_query->row()->total;
			$array['items'] = $egw;
			$array['total'] = $total;
			return $array;
		}
	}
	function get_score()
	{
		$signUp = $this->signUp();
		$start = date( 'Y-m-d H:i:s', strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
		$end = date('Y-m-d H:i:s');
		$monthDay = date("d");
		if($signUp > $start){
			$totalDays = $monthDay - intval(date("d", strtotime($signUp))) +1;
		} else {
			$totalDays = $monthDay;
		}
		$sql = "SELECT Count(distinct DATE(created)) AS total
  			FROM journal
 			WHERE created between '".$start."' and '".$end."' and user_id = ".$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
	    $result = $query->row_array();
		$daysPosted = $result['total'];
		if($daysPosted < 1 OR $totalDays < 1){
			$score = 0;
		} else {
			$score = $daysPosted/$totalDays*100;
		}
		return number_format($score)."%";
	}
	function fix_verses()
	{
		$sql = 'SELECT book, id FROM egw_scripture_reference WHERE book LIKE "% %"';
	    $query = $this->db->query($sql);
	    $result = $query->result();
	    //print_r($result);
	    foreach($result as $item){
	    	$new_book = str_replace(" ", "", $item->book);
	    	echo $new_book."</br>";
	    	$id = $item->id;
	    	
	    	$sql_process = 'UPDATE egw_scripture_reference SET book = "'.$new_book.'" WHERE id = '.$id;
	    	$query_process = $this->db->query($sql_process);
	    	
	    	
	    	
	    	
	    }
	    
	    //print_r($result);
		
	}
	function tour()
	{
		$sql = 'SELECT dash_tour FROM users WHERE id = '.$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
	    $result = $query->row_array();
		return $result['dash_tour'];
	}
	function doneTour()
	{
		$sql = 'UPDATE users SET dash_tour = "1" WHERE id = '.$this->tank_auth->get_user_id();
		 $query = $this->db->query($sql);
	}
	function signUp()
	{
		$sql = 'SELECT created FROM users WHERE id = '.$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
		$result = $query->row_array();
		return $result['created'];
	}
	function add_feedback($message, $url)
	{
		$data = array(
		   'content' => $message ,
		   'user_id' => $this->tank_auth->get_user_id(),
		   'date' => date('Y-m-d H:i:s'),
		   'url' => $url
		);
		$this->db->insert('feedback', $data);
		return true;
		
	}
	function add_hdh_title($title)
	{
		$data = array(
		   'title' => $title
		);
		$this->db->insert('hbh_sections', $data);
		return $this->db->insert_id();
		
	}
	function add_hdh($subTitle, $title, $text, $section_id)
	{
		$data = array(
		   'title' => $title,
		   'section_title' => $subTitle,
		   'text' => $this->db->escape_like_str($text),
		   'section_id' => $section_id
		);
		$this->db->insert('hbh', $data);
		return $this->db->insert_id();
		
	}
	function search($phrase)
	{

		if (ctype_alpha($phrase)){
			$sql = "SELECT title AS value, content, id FROM journal WHERE title OR content LIKE '%".$this->db->escape_like_str($phrase)."%' AND user_id = '".$this->tank_auth->get_user_id()."'";
			$query = $this->db->query($sql);
			$rows = array();
			foreach($query->result() as $row)
			{
			    $row->content = strip_tags($row->content);
			    $row->content = word_limiter($row->content, 15);
			    $rows[] = $row;
			}
			return $rows;
		}
	}
}