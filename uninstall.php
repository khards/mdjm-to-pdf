<?php
/**
 * The MDJM to PDF uninstallation procedure
 * removes all settings relating to the plugin
 *
 *
 *
 */
	// If uninstall is not called from WordPress, exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit();
 
	// All our settings in one array
	$base = plugin_basename( __FILE__ );
	list( $__plug, $__file ) = explode( '/', $base );
	
	$pdf_options = array(
		'mdjm_pdf_plugin_settings',
		'mdjm_pdf_content',
		'mdjm_pdf_version'
	);
	
	$pdf_settings = array(
		'pdf_page_size'                => 'A4',
		'pdf_page_orientation'         => '-P',
		'pdf_watermark'                => mdjm_company( 'company_name' ),
		'pdf_enquiry_template'         => mdjm_get_option( 'enquiry' ),
		'pdf_contract_template'        => mdjm_get_option( 'contract' ),
		'pdf_booking_conf_template'    => mdjm_get_option( 'booking_conf_client' )
	);
	 
	 // Loop through the array removing the settings from the database
	foreach( $pdf_options as $pdf_option )	{
		delete_option( $pdf_option );
	}
	
	foreach( $pdf_settings as $key => $value )	{
		mdjm_delete_option( $key );
	}