<?php
/**
 * Update procedures for the MDJM to PDF plugin.
 * Determine if an update is needed and execute update procedures
 *
 *
 */
	/**
	 * Determine if any update procedures are required.
	 * If so, execute them
	 *
	 *
	 *
	 */
	function mdjm_pdf_updates( $stored_ver )	{
		if( $stored_ver < MDJM_PDF_VERSION_NUM )	{
			// Execute the update procedures	
			mdjm_pdf_update_procedures( $stored_ver );
		}
		else
			return;
	} // mdjm_pdf_updates
	
	/**
	 * Execute required update procedures.
	 * Always use if( function_exists...
	 *
	 *
	 *
	 */
	function mdjm_pdf_update_procedures( $stored_ver )	{
		if( $stored_ver < '0.2' && function_exists( 'mdjm_pdf_to_0_2' ) )
			mdjm_pdf_to_0_2();
			
		if( $stored_ver < '0.3' && function_exists( 'mdjm_pdf_to_0_3' ) )
			mdjm_pdf_to_0_3();
			
		if( $stored_ver < '1.0' && function_exists( 'mdjm_pdf_to_1_0' ) )
			mdjm_pdf_to_1_0();
						
		// Update the DB stored version number
		update_option( MDJM_PDF_VERSION_KEY, MDJM_PDF_VERSION_NUM );
	} // mdjm_pdf_update_procedures
	
	/**
	 * Execute update procedures for version 0.2.
	 *
	 *
	 *
	 *
	 */
	function mdjm_pdf_to_0_2()	{
		MDJM()->debug->log_it( 'MDJM to PDF - Updating to version 0.2', true );
		
		MDJM()->debug->log_it( 'MDJM to PDF - Update to version 0.2 completed', true );
	} // mdjm_pdf_to_0_2
	
	/**
	 * Execute update procedures for version 0.3.
	 *
	 *
	 *
	 *
	 */
	function mdjm_pdf_to_0_3()	{
		MDJM()->debug->log_it( 'MDJM to PDF - Updating to version 0.3', true );
		
		MDJM()->debug->log_it( 'MDJM to PDF - Update to version 0.3 completed', true );
	} // mdjm_pdf_to_0_3
	
	/**
	 * Execute update procedures for version 1.0.
	 *
	 *
	 *
	 *
	 */
	function mdjm_pdf_to_1_0()	{
		
		MDJM()->debug->log_it( 'MDJM to PDF - Updating to version 0.4', true );
		
		$pdf_settings = get_option( 'mdjm_pdf_settings' );
		
		if ( ! empty( $pdf_settings ) )	{
			foreach ( $pdf_settings as $key => $value )	{
				if ( empty( mdjm_get_option( $key, false ) ) )	{
					mdjm_update_option( $key, $value );
				}
			}
		}
		
		$old_pdf_options = array(
			'mdjm_pdf_plugin_settings',
			'mdjm_pdf_content',
			'mdjm_pdf_version'
		);
		
		foreach( $old_pdf_options as $old_pdf_option )	{
			delete_option( $old_pdf_option );
		}
		
		MDJM()->debug->log_it( 'MDJM to PDF - Update to version 0.4 completed', true );
	} // mdjm_pdf_to_1_0
?>