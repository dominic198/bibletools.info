<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		
		$data['ref'] = $this->uri->segment(1);
		
		$this->load->view('index', $data);
	}
	function contact_form()
	{
		$this->load->view('contact_form');
	}
	function resource_list()
	{
		$this->load->view('resource_list');
	}
	function submit_message()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$message = $this->input->post('message')."\r\n\r\n".$email;
		
		if(!empty($message)){
			mail("akjackson1@gmail.com", "BibleTools.info Feedback from ".$name, $message);
		}
	}
	function get_egw()
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
	function get_egw_from_verse()
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

	function egw_content()
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
	/*function fix()
	{
		$this->readermodel->fix_verses();

	}
	function parse()
	{
	$html = $this->domparser->file_get_html("http://drawnigh.adamjacksonphoto.com/text.html");
	$book = "Revelation";
	
	
		
		$sections = $html->find("div.text");
		//print_r($sections);
		//die();
		
		foreach($sections as $section){
			
			$text = $section->find("span.reference", 0)->plaintext;
			$text = explode(":", $text);
			
			if(array_key_exists(1, $text)){
				$verse = $text[1];
			} else { $verse = ""; }
			
			$chapter = $text[0];
			$egw = $section->find("span.egw-eng");
			foreach ($egw as $egw_item){
				echo $chapter.":".$verse." - ".$egw_item->title."<br/>";
				$this->biblereadermodel->add_reference($book, $chapter, $verse, $egw_item->title);
			}
			
			
			
			//echo $verse."<br/>";
			
		}
	
	
	}*/
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */