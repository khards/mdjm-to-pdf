<?php
	defined( 'ABSPATH' ) or die( "Direct access to this page is disabled!!!" );
/**
 * Plugin Name: MDJM to PDF
 * Plugin URI: http://mdjm.co.uk/products/pdf-export/
 * Description: MDJM to PDF compliments the MDJM Event Management for WordPress plugin by enabling exports of Event documentation to PDF
 * Version: 1.0
 * Date: 12th May 2016
 * Author: Mike Howard <mike@mikeandniki.co.uk>
 * Author URI: http://mdjm.co.uk
 * Text Domain: mdjm-to-pdf
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
/**
   MDJM to PDF is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License, version 2, as 
   published by the Free Software Foundation.

   MDJM to PDF is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with MDJM to PDF; if not, see https://www.gnu.org/licenses/gpl-2.0.html
 */
class MDJM_to_PDF	{
	private static $instance;
	
	public static function instance()	{
		// Do nothing if MDJM is not activated
		if( ! class_exists( 'Mobile_DJ_Manager', false ) ) {
			return;
		}
		
		if( ! isset( self::$instance ) && ! ( self::$instance instanceof MDJM_to_PDF ) ) {					
			self::$instance = new MDJM_to_PDF();
			
			self::define_constants();
			self::includes();
			self::hooks();
		}
	} // instance
					
	/**
	 * Define constants.
	 *
	 * @since	1.0
	 */
	public static function define_constants()	{

		define( 'MDJM_PDF_NAME', 'MDJM to PDF for MDJM Event Management' );
		define( 'MDJM_PDF_VERSION_NUM', '1.0' );
		define( 'MDJM_PDF_REQUIRED_WP_VERSION', '4.1' );
		define( 'MDJM_PDF_BASENAME', plugin_basename( __FILE__ ) );
		define( 'MDJM_PDF_PLUGIN_NAME', trim( dirname( MDJM_PDF_BASENAME ), '/' ) );
		define( 'MDJM_PDF_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
		define( 'MDJM_PDF_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
		define( 'MDJM_PDF_VERSION_KEY', 'mdjm_pdf_version');
		define( 'MDJM_PDF_SETTINGS_KEY', 'mdjm_pdf_settings' );
		define( 'MDJM_PDF_CONTENT_KEY', 'mdjm_pdf_content' );

	} // define_constants
	
	/**
	 * Include files.
	 *
	 * @since	1.0
	 */
	public static function includes()	{
		require_once( 'includes/admin/settings.php' );
		require_once( 'includes/processor.php' );
		require_once( 'includes/shortcodes.php' );
	} // includes
	
	/**
	 * Hooks.
	 *
	 * @since	1.0
	 */
	public static function hooks()	{
		add_action( 'plugins_loaded', array( __CLASS__, 'check_for_upgrade' ) );
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( __CLASS__, 'plugin_action_links' ) );		
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_meta_links' ), 10, 2 );
	} // hooks
	
	
	/**
	 * Load translation text domain.
	 *
	 * @since	1.0
	 */
	function load_textdomain() {
		$plugin_dir = basename( dirname( __FILE__ ) );
		
		load_plugin_textdomain( 'mdjm-to-pdf', false, $plugin_dir . '/languages' );
	} // load_textdomain
	
	/**
	 * Actions upon activation.
	 *
	 * @since	1.0
	 */
	public static function activate()	{			
		if( ! get_option( 'mdjm_pdf_version' ) )
			require_once( 'includes/admin/procedures/install.php' );
	} // activate
	
	/**
	 * Check if the plugin has been updated and if we have any update procedures to run.
	 *
	 * @since	1.0
	 */
	public static function check_for_upgrade()	{
		$stored_ver = get_option( MDJM_PDF_VERSION_KEY );
		
		if( $stored_ver < MDJM_PDF_VERSION_NUM )	{
			require_once( 'includes/admin/procedures/updates.php' );
			mdjm_pdf_updates( $stored_ver );
		}
	} // check_for_upgrade
							
	/**
	 * Create the HTML output for PDF actions to be displayed on client facing pages.
	 *
	 * @since	1.0
	 */
	public static function pdf_action_dropdown()	{
		global $post;
					
		$content = '<div id="mdjm_to_pdf_actions" align="right">' . "\r\n";
		$content .= '	<form name="mdjm_pdf_action" id="mdjm_pdf_action" method="post" action="' . get_permalink() . '">' . "\r\n";
		$content .= '        <input type="hidden" name="mdjm_to_pdf_exec" value="' . $post->ID . '">' . "\r\n";
		$content .= '        ' . "\r\n";
		$content .= '		<select name="mdjm_pdf_output" id="mdjm_pdf_output">' . "\r\n";
		$content .= '			<option value="I">' . __( 'Print' ) . '</option>' . "\r\n";
		$content .= '			<option value="D">' . __( 'Download' ) . '</option>' . "\r\n";
		$content .= '			<option value="D">' . __( 'Download' ) . '</option>' . "\r\n";
		$content .= '		</select>' . "\r\n";
		$content .= '	</form>' . "\r\n";
		$content .= '</div>' . "\r\n";
		
		if( empty( $post ) )
			return 'Error';
			
		else
			return $content;
	} // pdf_action_dropdown
	
	/**
	 * Add the Settings 'action' link to the plugin screen.
	 *
	 * @since	1.0
	 */
	public static function plugin_action_links( $links )	{
		 $pdf_links = array(
			 '<a href="' . add_query_arg( array( 'tab' => 'extensions', 'section' => 'mdjm-to-pdf' ), mdjm_get_admin_page( 'settings' ) ) . '">' . __( 'Settings', 'mdjm-to-pdf' ) . '</a>' );
		
		return array_merge( $links, $pdf_links );
	} // plugin_action_links
	
	/**
	 * Add links to the plugin row meta.
	 *
	 * @since	1.0
	 */
	public static function plugin_meta_links( $links, $file )	{
		
		$plugin = plugin_basename(__FILE__);
		
		if ( $file == $plugin ) {
			return array_merge(
				$links,
				array( '<a href="http://www.mydjplanner.co.uk/donate/" target="_blank">' . __( 'Donate' ) . '</a>',
				'<a href="http://www.mydjplanner.co.uk/product-category/mdjm/premium-add-ons/" target="_blank">' . __( 'More Extensions' ) . '</a>' ) );
		}
		return $links;
	} // plugin_meta_links
	
} // class MDJM_to_PDF

function MDJM_PDF()	{
	return MDJM_to_PDF::instance();
} // MDJM_to_PDF
add_action( 'plugins_loaded', 'MDJM_PDF' );

register_activation_hook( __FILE__, array( 'MDJM_to_PDF', 'activate' ) );