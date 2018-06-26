<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists( "getLastVerse" ) )
{
	function getLastVerse()
	{
		$ci = &get_instance();
		$ci->load->library( "session" );
		if( array_key_exists( "history", $_SESSION ) ) {
			foreach( $_SESSION["history"] as $key => $item ) {
				return $key;
			}
		}
		session_write_close();
		return false;
	}
	
	function saveLastVerse( $ref )
	{
		$ci = &get_instance();
		$ci->load->library( "session" );
		if( getLastVerse() == $ref ) return;
		$history = $ci->session->history ?: [];
		$history = [ $ref => parseReferenceToText( shortTextToNumber( $ref ) ) ] + $history;
		$ci->session->history = array_slice( $history, 0, 10 );
		session_write_close();
	}
}