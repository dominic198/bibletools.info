<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
	var $template_data = array();
		
	function set( $name, $value )
	{
		$this->template_data[$name] = $value;
	}

	function load( $template = '', $view = '' , $view_data = [], $return = FALSE )
	{               
		$ci =& get_instance();
		$ci->load->library( "ion_auth" );
		$view_data["is_admin"] = false;
		if ( $ci->ion_auth->logged_in() ) {
			$view_data["is_admin"] = true;
		}
		$view_data["history"] = $_SESSION["history"];
		$this->CI =& get_instance();
		$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
		return $this->CI->load->view($template, $this->template_data, $return);
	}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */