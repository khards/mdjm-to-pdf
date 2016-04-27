<?php
	defined( 'ABSPATH' ) or die( "Direct access to this page is disabled!!!" );
	
/**
 * Installation procedures for MDJM to PDF
 *
 *
 *
 */	
	if( !class_exists( 'MDJM' ) )
		exit( __( 'Mobile DJ Manager for WordPress plugin is either not installed or not activated!', 'mdjm-to-pdf' ) );
	
	if( MDJM_VERSION_NUM < '1.3' )	{
		exit( sprintf( __( 'The MDJM to PDF plugin requires the Mobile DJ Manager for WordPress plugin to be at minimum version 1.2.6. You are currently running %s. Update before activating.', 
			'mdjm-to-pdf' ), MDJM_VERSION_NUM ) );
	}
 
	if( get_option( 'mdjm_pdf_version' ) )
		return;
		
	else	{
		// Version
		add_option( 'mdjm_pdf_version', MDJM_PDF_VERSION_NUM );
		
		// Settings
		$pdf_settings = array(
			'pdf_page_size'                => 'A4',
			'pdf_page_orientation'         => '-P',
			'pdf_watermark'                => mdjm_company( 'company_name' ),
			'pdf_enquiry_template'         => mdjm_get_option( 'enquiry' ),
			'pdf_contract_template'        => mdjm_get_option( 'contract' ),
			'pdf_booking_conf_template'    => mdjm_get_option( 'booking_conf_client' )
		);
				
		foreach ( $pdf_settings as $key => $value )	{
			mdjm_update_option( $key, $value );
		}
	}