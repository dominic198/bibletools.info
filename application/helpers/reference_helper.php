<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists( "construct_reference" ) )
{
    function construct_reference( $book, $chapter, $verse )
    {
		return str_pad( $book, 2, "0", STR_PAD_LEFT )
			. str_pad( $chapter, 3, "0", STR_PAD_LEFT )
			. str_pad( $verse, 3, "0", STR_PAD_LEFT );
    }   
}