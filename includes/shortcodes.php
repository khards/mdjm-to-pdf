<?php
	defined( 'ABSPATH' ) or die( "Direct access to this page is disabled!!!" );
/**
 * Class Name: MDJM_PDF_Shortcodes
 * Description: Manage and process MDJM to PDF shortcodes
 * Version: 0.1
 * Date: 27 October 2015
 * Author: My DJ Planner <contact@mydjplanner.co.uk>
 * Author URI: http://www.mydjplanner.co.uk
 */
		
/**
 * Output the text/button to print to PDF.
 *
 * @since	0.1
 * @param	arr		$atts
 * @return	str
 */
function print_pdf( $atts )	{
	global $post, $current_user;
	
	extract( 
		shortcode_atts( 
			array( // These are our default values
				'type'	=> 'text',
				'text'	=> __( 'Print', 'mdjm-to-pdf' )
			),
			$atts
		)
	);
	
	$client = ! empty( $current_user ) && ! empty( $current_user->ID ) ? $current_user->ID : '';
	$event  = ! empty( $_GET['event_id'] )                             ? $_GET['event_id'] : '';
	
	return '<a href="' . $_SERVER['REQUEST_URI'] . '&pdf_output=' . $post->ID . '" target="_blank">' . 
		mdjm_do_content_tags( $text, $event, $client ) . '</a>';

} // print_pdf
add_shortcode( 'mdjm-pdf-print', 'print_pdf' );

/**
 * Force a download of the PDF.
 *
 *
 * @since	0.1
 * @param	arr		$atts
 * @return	str
 */
function download_pdf( $atts )	{
	global $post, $current_user;
	
	extract( 
		shortcode_atts( 
			array( // These are our default values
				'type'	=> 'text',
				'text'	=> __( 'Download', 'mdjm-to-pdf' )
			),
			$atts
		)
	);
	
	$client = ! empty( $current_user ) && ! empty( $current_user->ID ) ? $current_user->ID : '';
	$event  = ! empty( $_GET['event_id'] )                             ? $_GET['event_id'] : '';
	
	return '<a href="' . $_SERVER['REQUEST_URI'] . '&pdf_output=' . $post->ID . '&output_to=D" target="_blank">' . 
		mdjm_do_content_tags( $text, $event, $client ) . '</a>';

} // download_pdf
add_shortcode( 'mdjm-pdf-download', 'download_pdf' );

/**
 * Email the PDF to the person who clicks the link.
 *
  *
 * @since	0.1
 * @param	arr		$atts
 * @return	str
 */
function email_pdf( $atts )	{
	global $post, $current_user;
	
	extract( 
		shortcode_atts( 
			array( // These are our default values
				'type'	=> 'text',
				'text'	=> __( 'Email', 'mdjm-to-pdf' )
			),
			$atts
		)
	);
	
	$client = ! empty( $current_user ) && ! empty( $current_user->ID ) ? $current_user->ID : '';
	$event  = ! empty( $_GET['event_id'] )                             ? $_GET['event_id'] : '';
	
	return '<a href="' . $_SERVER['REQUEST_URI'] . '&pdf_output=' . $post->ID . '&output_to=D" target="_blank">' . 
		mdjm_do_content_tags( $text, $event, $client ) . '</a>';

} // email_pdf
add_shortcode( 'mdjm-pdf-email', 'email_pdf' );