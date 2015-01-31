<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Egw extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('readermodel');
	}
	
	function get()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
	
		if($this->uri->segment(5)){
			$offset = $this->uri->segment(5);
		} else {
			$offset = 0;
		}
		
		$data = json_encode($this->readermodel->get_egw_from_chapter($book, $chapter, $offset));
		$this->output->set_output($data);
	}
	function get_from_verse()
	{
		$book = $this->uri->segment(3);
		$chapter = $this->uri->segment(4);
		$verse = $this->uri->segment(5);
		
		if($this->uri->segment(6)){
			$offset = $this->uri->segment(6);
		} else {
			$offset = 0;
		}
		
		$data = json_encode($this->readermodel->get_egw_by_verse($book, $chapter, $verse, $offset));
		$this->output->set_output($data);
	}

	function content()
	{
		$query = $this->uri->segment(3);
		if($query != "undefined"){
			$html = $this->domparser->file_get_html("http://m.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=".$query);
			$title = $html->find("h4", 0)->plaintext;
			$title = str_replace("Page ", "", $title);
			
			$html = $html->find("div.showitem", 0);
			$html = str_replace("<span name='para1'/>", "", $html);
			$html = str_replace(" class='standard-indented'", "", $html);
			
			$data['title'] = $title;
			$data['content'] = $html;
			
			$this->output->set_output(json_encode($data));
		}
	}
	function save_all()
	{
		$sql = 'SELECT DISTINCT(reference) FROM egw_scripture_reference';
	    $query = $this->db->query($sql);
	    $egw = $query->result_array();
	    
	    foreach($egw as $item){
	    	$html = $this->domparser->file_get_html("http://m.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=".$item['reference']);
			$title = $html->find("h4", 0)->plaintext;
			$title = str_replace("Page ", "", $title);
			
			$html = $html->find("div.showitem", 0);
			$html = str_replace("<span name='para1'/>", "", $html);
			$html = str_replace(" class='standard-indented'", "", $html);
			
			$data['title'] = $title;
			$data['text'] = $html;
			$data['reference'] = $item['reference'];
			
			$this->db->insert('egw_qutoes', $data); 
			die;
	    }
	}
}