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
if( !class_exists( 'MDJM_PDF_Shortcodes' ) ) :
	class MDJM_PDF_Shortcodes	{
		/**
		 * Class constructor
		 *
		 *
		 *
		 */
		function __construct()	{
			add_action( 'init', array( &$this, 'register_shortcodes' ) );
		} // __construct
		
		/**
		 * Register the shortcodes for use within MDJM_to_PDF
		 *
		 *
		 *
		 *
		 */
		function register_shortcodes()	{
			add_shortcode( 'mdjm-pdf-print', array( &$this, 'print_pdf' ) );
			add_shortcode( 'mdjm-pdf-download', array( &$this, 'download_pdf' ) );
			add_shortcode( 'mdjm-pdf-email', array( &$this, 'email_pdf' ) );
		} // register_shortcodes
		
		/**
		 * Output the text/button to print to PDF
		 *
		 *
		 *
		 *
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
			
			$client = !empty( $current_user ) && !empty( $current_user->ID ) ? $current_user->ID : '';
			$event = !empty( $_GET['event_id'] ) ? $_GET['event_id'] : '';
			
			return '<a href="' . $_SERVER['REQUEST_URI'] . '&pdf_output=' . $post->ID . '" target="_blank">' . 
				$GLOBALS['mdjm']->filter_content( $client, $event, $text ) . '</a>';	
		
		} // print_pdf
		
		/**
		 * Force a download of the PDF
		 *
		 *
		 *
		 *
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
			
			$client = !empty( $current_user ) && !empty( $current_user->ID ) ? $current_user->ID : '';
			$event = !empty( $_GET['event_id'] ) ? $_GET['event_id'] : '';
			
			return '<a href="' . $_SERVER['REQUEST_URI'] . '&pdf_output=' . $post->ID . '&output_to=D" target="_blank">' . 
				$GLOBALS['mdjm']->filter_content( $client, $event, $text ) . '</a>';	
		
		} // download_pdf
		
		/**
		 * Email the PDF to the person who clicks the link
		 *
		 *
		 *
		 *
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
			
			$client = !empty( $current_user ) && !empty( $current_user->ID ) ? $current_user->ID : '';
			$event = !empty( $_GET['event_id'] ) ? $_GET['event_id'] : '';
			
			return '<a href="' . $_SERVER['REQUEST_URI'] . '&pdf_output=' . $post->ID . '&output_to=D" target="_blank">' . 
				$GLOBALS['mdjm']->filter_content( $client, $event, $text ) . '</a>';	
		
		} // email_pdf
		
	} // MDJM_PDF_Shortcodes
endif;
	new MDJM_PDF_Shortcodes();