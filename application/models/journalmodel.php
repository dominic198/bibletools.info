<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Journalmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('text');
		
	}

	function get_journal_entries($offset = 0)
	{
		$sql = 'SELECT * FROM journal WHERE user_id = '.$this->tank_auth->get_user_id().' ORDER BY id DESC LIMIT 5 OFFSET '.$offset;
	    $query = $this->db->query($sql);
 
		//if($query->num_rows() > 0) return $query->result();
		//else return FALSE;
		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->created = date("c", strtotime($row->created));
		    $row->content = word_limiter($row->content, 55);
		    $row->content = strip_tags($row->content);
		    $rows[] = $row;
		}
		return $rows;
	}
	function get_journal_entries_by_month($year = NULL, $month = NULL)
	{
		if($month == NULL){
			$newMonth = date("m");
			$newYear = date("Y");
		} else {
			$newMonth = $month;
			$newYear = $year;
		}
		$sql = 'SELECT * FROM journal WHERE user_id = '.$this->tank_auth->get_user_id().' AND MONTH(created) = "'.$newMonth.'" AND YEAR(created) = "'.$newYear.'" ORDER BY id DESC';
	    $query = $this->db->query($sql);
 
		//if($query->num_rows() > 0) return $query->result();
		//else return FALSE;
		$rows = array();
		foreach($query->result() as $row)
		{
		    $row->created = date("c", strtotime($row->created));
		    $row->content = word_limiter($row->content, 55);
		    $row->content = strip_tags($row->content);
		    $rows[] = $row;
		}
		return $rows;
	}
	function get_journal_entry($id)
	{
		if(is_numeric($id)){
			$sql = 'SELECT * FROM journal WHERE user_id = '.$this->tank_auth->get_user_id().' AND id = '.$id;
		    $query = $this->db->query($sql);
		    if ($query->num_rows() > 0){
				$result = $query->row_array();
				$result['created'] = date("c", strtotime($result['created']));
				$result['last_modified'] = date("c", strtotime($result['last_modified']));
				return $result;
			} else {
				return "0";
			}
		} else {
		
			redirect("/");
		}
	}
	function get_public_entry($key)
	{
		if(is_numeric($key)){
			$sql = 'SELECT * FROM journal WHERE secret_key = "'.$key.'"';
		    $query = $this->db->query($sql);
		    if ($query->num_rows() > 0){
				$result = $query->row_array();
				$result['created'] = date("c", strtotime($result['created']));
				$result['last_modified'] = date("c", strtotime($result['last_modified']));
				return $result;
			} else {
				return "0";
			}
		} else {
		
			die("Invalid Entry");
		}
	}
	function get_shared_emails($phrase)
	{
		$sql = 'SELECT DISTINCT email AS value FROM shared_emails WHERE email LIKE "%'.$phrase.'%" AND user_id = "'.$this->tank_auth->get_user_id().'"';
	    $query = $this->db->query($sql);
	    if ($query->num_rows() > 0){
			$rows = array();
			foreach($query->result() as $row)
			{
			    $rows[] = $row;
			}
			return $rows;
		} else {
		}
		
	}
	function oldest_entry()
	{
		$sql = 'SELECT * FROM journal WHERE user_id = '.$this->tank_auth->get_user_id().' ORDER BY created ASC LIMIT 1';
	    $query = $this->db->query($sql);
		$result = $query->row_array();
		
		if(empty($result)){ 
		   return "0"; 
		}else{ 
		   $result['created'] = date("Y-m", strtotime($result['created']));
			return $result['created']; 
		} 
		
	}
	function getAvatar($secretKey)
	{
		if(is_numeric($secretKey)){
			$sql = 'SELECT user_id FROM journal WHERE secret_key = "'.$secretKey.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			$user_id = $result['user_id'];
			
			$sql = 'SELECT email FROM users WHERE id = "'.$user_id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			return md5($result['email']);
		}
	}
	function getName($secretKey)
	{
		if(is_numeric($secretKey)){
			$sql = 'SELECT user_id FROM journal WHERE secret_key = "'.$secretKey.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			$user_id = $result['user_id'];
			
			$sql = 'SELECT firstname, lastname FROM user_profiles WHERE user_id = "'.$user_id.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			return $result['firstname']." ".$result['lastname'];
		}
	}
	function getTitle($secretKey)
	{
		if(is_numeric($secretKey)){
			
			$sql = 'SELECT title FROM journal WHERE secret_key = "'.$secretKey.'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			return $result['title'];
		}
	}
	function convert_smart_quotes($string) 
	{ 
	    $search = array(chr(145), 
	                    chr(146), 
	                    chr(147), 
	                    chr(148), 
	                    chr(151)); 
	 
	    $replace = array("'", 
	                     "'", 
	                     '"', 
	                     '"', 
	                     '-'); 
	 
	    return str_replace($search, $replace, $string); 
	}
	function new_journal_entry($content, $user_id)
	{
		$characters = '0123456789';
		$length = 24;
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
		$data = array(
		   'user_id' => $user_id ,
		   'content' => $content ,
		   'last_modified' => date('Y-m-d H:i:s'),
		   'created' => date('Y-m-d H:i:s'),
		   'secret_key' => $randomString
		);
		$this->db->insert('journal', $data);
		return $this->db->insert_id();
	}
	function update_journal_entry($content, $title, $id, $privacy)
	{
		
		$last_modified = date('Y-m-d H:i:s');
		//die($last_updated);
		$sql = 'UPDATE journal SET content = "'.mysql_real_escape_string($content).'", public = "'.$privacy.'", title = "'.mysql_real_escape_string($title).'", last_modified = "'.$last_modified.'" WHERE user_id = '.$this->tank_auth->get_user_id().' AND id = '.$id;
		 $query = $this->db->query($sql);
	}
	function delete_entry($entry_id)
	{
		if(is_numeric($entry_id)){
			$sql = 'DELETE FROM journal WHERE id = '.$entry_id.' AND user_id = '.$this->tank_auth->get_user_id();
		    $query = $this->db->query($sql);
		}
	}
	function tour()
	{
		$sql = 'SELECT journal_tour FROM users WHERE id = '.$this->tank_auth->get_user_id();
	    $query = $this->db->query($sql);
	    $result = $query->row_array();
		return $result['journal_tour'];
	}
	function doneTour()
	{
		$sql = 'UPDATE users SET journal_tour = "1" WHERE id = '.$this->tank_auth->get_user_id();
		 $query = $this->db->query($sql);
	}
	function add_email($data)
	{
		
		$sql = 'SELECT id FROM shared_emails WHERE email = "'.$data['email'].'" AND entry_id = "'.$data['entry_id'].'"';
	    $query = $this->db->query($sql);
	    if ($query->num_rows() > 0){
			return false;
		} else {
			$this->db->insert('shared_emails', $data);
			return true;
		}
		
	}
	function email_share($emails, $entry_id)
	{
		if(is_numeric($entry_id)){
			
			$sql = 'SELECT title, secret_key FROM journal WHERE id = "'.$entry_id.'" AND user_id = "'.$this->tank_auth->get_user_id().'"';
		    $query = $this->db->query($sql);
			$result = $query->row_array();
			$title = $result['title'];
			$key = $result['secret_key'];
			
			$saveEmails = preg_replace( '/\s+/', ' ', $emails );
			$emailsArray = explode(",", $saveEmails);
			
			foreach($emailsArray as $item){
				$characters = '0123456789';
				$length = 24;
			    $randomString = '';
			    for ($i = 0; $i < $length; $i++) {
			        $randomString .= $characters[rand(0, strlen($characters) - 1)];
			    }
			    $data['key'] = $randomString;
				$data['email'] = trim($item);
				$data['user_id'] = $this->tank_auth->get_user_id();
				$data['entry_id'] = $entry_id;
				if(trim($item) != ""){
					$this->add_email($data);
				}
			}
			
			$data = array(
			   'name' => $this->getName($key),
			   'key' => $key,
			   'title' => $title,
			   'avatar' => $this->getAvatar($key)
			);
			$this->load->library('email');
			$this->email->from("info@drawnigh.org", "DrawNigh.org");
			$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
			$this->email->to($emails);
			$this->email->subject($this->getName($key)." Shared a Journal Entry with You");
			$this->email->message($this->load->view('email/share-html', $data, TRUE));
			$this->email->send();
		}
	}

}