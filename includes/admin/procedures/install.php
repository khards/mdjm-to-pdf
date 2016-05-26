<?php
	defined( 'ABSPATH' ) or die( "Direct access to this page is disabled!!!" );
	
/**
 * Installation procedures for MDJM to PDF
 *
 *
 *
 */	 
function mdjm_to_pdf_install()	{
	if( get_option( 'mdjm_pdf_version' ) )	{
		return;
	} else	{
		// Version
		add_option( 'mdjm_pdf_version', MDJM_PDF_VERSION_NUM );
		
		// Settings
		$pdf_settings = array(
			'pdf_page_size'                => 'A4',
			'pdf_page_orientation'         => '-P',
			'pdf_watermark'                => mdjm_get_option( 'company_name' ),
			'pdf_enquiry_template'         => mdjm_get_option( 'enquiry' ),
			'pdf_contract_template'        => mdjm_get_option( 'contract' ),
			'pdf_booking_conf_template'    => mdjm_get_option( 'booking_conf_client' )
		);
				
		foreach ( $pdf_settings as $key => $value )	{
			mdjm_update_option( $key, $value );
		}
	}
}
register_activation_hook( MDJM_PDF_PLUGIN_FILE, 'mdjm_to_pdf_install' );
