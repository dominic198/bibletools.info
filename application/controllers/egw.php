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
		$ref = $this->uri->segment(3);
		if($ref != "undefined"){
			$sql = 'SELECT *, text as content FROM egw_quotes_new WHERE reference = "' . urldecode( $ref ) . '"';
		    $query = $this->db->query($sql);
		    $result = $query->result_array();
			$data = json_encode( $result[0] );
			$this->output->set_output($data);
		}
	}
	function save_all()
	{
		$sql = 'SELECT DISTINCT(reference) FROM egw_scripture_reference ORDER BY id DESC LIMIT 1000';
	    $query = $this->db->query($sql);
	    $egw = $query->result_array();
	    $i = 0;
	    foreach($egw as $item){
	    	$html = $this->domparser->file_get_html("http://m.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=".$item['reference']);
	    	if( is_object($html) ){
	    		if( !$html->find("h4", 0) ){
	    			$data['reference'] = $item['reference'];
					$this->db->insert('egw_quotes', $data);
	    		} else {
	    			
		    		$title = $html->find("h4", 0)->plaintext;
					$title = str_replace("Page ", "", $title);
					
					$html = $html->find("div.showitem", 0);
					$html = str_replace("<span name='para1'/>", "", $html);
					$html = str_replace(" class='standard-indented'", "", $html);
					
					$data['title'] = $title;
					$data['text'] = $html;
					$data['reference'] = $item['reference'];
					$this->db->insert('egw_quotes', $data);
				}
				
	    	} else {
	    		
				$data['reference'] = $item['reference'];
				$this->db->insert('egw_quotes', $data);
	    	}
	    	unset( $html );
	    	unset( $data['text'] );
	    	unset( $data );
	    	unset( $title );
	    }
	}
	
	function transfer_all()
	{
		$sql = 'SELECT * FROM egw_quotes_new WHERE text = ""';
	    $query = $this->db->query($sql);
	    $egw = $query->result_array();
	    $i = 0;
	    foreach($egw as $item){
	    	
	    	$tsql = 'SELECT * FROM egw_quotes WHERE reference = "'.$item['reference'].'"';
	    	$tquery = $this->db->query($tsql);
	    	$transfer = $tquery->result_array();
	    	
	    	if(array_key_exists( 0, $transfer )){
	    	
	    		$transfer = $transfer[0];
	    	
		    	$data = array();
		    	$data['text'] = $transfer['text'];
		    	$data['title'] = $transfer['title'];
		    	
		    	$this->db->where('id', $item['id']);
				$this->db->update('egw_quotes_new', $data); 
		    	
		    	unset( $tsql );
		    	unset( $tquery );
		    	unset( $transfer );
		    	unset( $data['text'] );
		    	unset( $data['title'] );
		    	unset( $data );
	    	
	    	}	    	
	    }
	}
	
	function save_all_n()
	{
		$sql = 'SELECT * FROM egw_quotes_new WHERE text = ""';
	    $query = $this->db->query($sql);
	    $egw = $query->result_array();
	    $i = 0;
	    foreach($egw as $item){
	    	
	    	$html = $this->domparser->file_get_html("http://m.egwwritings.org/search.php?lang=en&collection=2&section=all&QUERY=".$item['reference']."&sortBy=perbook&Search=Search");
	    	//echo "http://m.egwwritings.org/search.php?lang=en&section=all&collection=2&QUERY=".$item['reference']."<br/>";
	    	//echo $html;die;
	    	if( is_object($html) ){
	    		if( !$html->find("h4", 0) ){
	    			$data['reference'] = $item['reference'];
					$this->db->insert('egw_quotes', $data);
	    		} else {
	    			
		    		$title = $html->find("h4", 0)->plaintext;
					$title = str_replace("Page ", "", $title);
					
					$html = $html->find("div.showitem", 0);
					$html = str_replace("<span name='para1'/>", "", $html);
					$html = str_replace(" class='standard-indented'", "", $html);
					
					$data['title'] = $title;
					$data['text'] = $html;
					//$data['reference'] = $item['reference'];
					
					$this->db->where('id', $item['id']);
					$this->db->update('egw_quotes_new', $data);
				}
	    	}
	    	
	    	unset( $html );
	    	unset( $data['text'] );
	    	unset( $data );
	    	unset( $title );
	    	
	    }
		    	
	}
}