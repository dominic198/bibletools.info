<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cronmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}

	function messageRD()
	{
		$this->load->library('googlevoice');
		$gv = new GoogleVoice();
		
			$messages = Array("What's her name? Eh?", "Brother bloke, I'm waiting", "Hmmm?!", "Ms Walla Walla… What's her name again?", "The missionary from Walla Walla, what's her name?", "What's her name?", "Sir");
		   	
		   	$to	= "2526192885";
					
			$gv->sms($to, $messages[array_rand($messages)]);
			
			echo "Messages Sent!";
		
	}
	
	function sendReminders()
	{
		$this->load->library('googlevoice');
		$gv = new GoogleVoice();
		
		date_default_timezone_set("GMT");
		$now = date("H:i");
		$day = date("N")+1;
		$sql = 'SELECT phone, carrier FROM reminders WHERE time = "'.$now.'" AND weekdays LIKE "%'.$day.'%"';
	    $query = $this->db->query($sql);
		
		$rows = array();
		$i = 0;
		foreach($query->result() as $row)
		{
		   	$to	= $row->phone;
					
			$gv->sms($to, "DrawNigh.org:\n Study Reminder");
			
			$i++;
		}
		echo $i." Messages Sent";
		
	}

}